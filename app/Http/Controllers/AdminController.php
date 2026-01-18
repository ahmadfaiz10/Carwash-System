<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage;
use App\Models\Booking;
use App\Models\Product;
use App\Models\Service;
use Carbon\Carbon;

class AdminController extends Controller
{

    public function profile()
{
    return view('admin.profile');
}

public function editProfile()
{
    return view('admin.editProfile');
}

public function updateProfile(Request $request)
{
    $request->validate([
        'FullName' => 'required|string',
        'PhoneNumber' => 'required|string',
        'Email' => 'required|email',
        'UserName' => 'required|string',
        'Address' => 'nullable|string',
    ]);

    $user = Auth::user();

    $user->FullName = $request->FullName;
    $user->PhoneNumber = $request->PhoneNumber;
    $user->Email = $request->Email;
    $user->UserName = $request->UserName;
    $user->Address = $request->Address;
    $user->save();

    // NEW: sync owner table
    DB::table('owner')
        ->where('UserID', $user->UserID)
        ->update([
            'OwnerName'   => $request->FullName,
            'OwnerEmail'  => $request->Email,
            'OwnerPhone'  => $request->PhoneNumber,
            'OwnerAddress'=> $request->Address ?? 'Not provided',
        ]);

    return redirect()->route('admin.profile')->with('success', 'Profile updated successfully!');
}


    // ✅ Dashboard
public function dashboard()
{
    $today = \Carbon\Carbon::today();

    // ===== BOOKINGS =====
    $totalBookings = Booking::count();
    $pendingBookings = Booking::where('BookingStatus', 'Pending')->count();
    $completedBookings = Booking::where('BookingStatus', 'Completed')->count();

    $todayBookings = Booking::whereDate('BookingDate', $today)->count();
    $todayCompletedBookings = Booking::whereDate('BookingDate', $today)
                                     ->where('BookingStatus', 'Completed')
                                     ->count();
    $todayPendingBookings = Booking::whereDate('BookingDate', $today)
                                   ->where('BookingStatus', 'Pending')
                                   ->count();

    $todayUniqueCustomers = DB::table('booking')
        ->whereDate('BookingDate', $today)
        ->distinct()
        ->count('CustomerID');

    // ===== PRODUCTS =====
    $totalProducts = Product::count();
    $products = Product::orderBy('StockQuantity', 'asc')
                       ->take(6)
                       ->get();

    $lowStockCount = Product::where('StockQuantity', '<=', 5)->count();

    // ===== SERVICES =====
    $activeServices = DB::table('services')
        ->where('status', 'Active')
        ->count();

    // ===== PAYMENTS =====
    $totalPayments = DB::table('payments')->count();
    $pendingPayments = DB::table('payments')
        ->where('PaymentStatus', 'Pending')
        ->count();

    $verifiedPayments = DB::table('payments')
        ->where('PaymentStatus', 'Verified')
        ->count();

    $totalRevenue = DB::table('payments')
        ->where('PaymentStatus', 'Verified')
        ->sum('Amount');

    $todayRevenue = DB::table('payments')
        ->where('PaymentStatus', 'Verified')
        ->whereDate('created_at', $today)
        ->sum('Amount');

    // ===== RECENT BOOKINGS =====
    $recentBookings = Booking::with(['customer', 'service'])
        ->orderBy('BookingDate', 'desc')
        ->orderBy('BookingTime', 'desc')
        ->limit(6)
        ->get();

    // ===== WEEKLY BOOKING HEAT TABLE DATA (LAST 7 DAYS) =====
    $startDate = $today->copy()->subDays(6);

    $weekData = [];

    for ($i = 0; $i < 7; $i++) {
        $dateObj = $startDate->copy()->addDays($i);
        $dateStr = $dateObj->toDateString();

        $pending = Booking::whereDate('BookingDate', $dateStr)
            ->where('BookingStatus', 'Pending')
            ->count();

        $completed = Booking::whereDate('BookingDate', $dateStr)
            ->where('BookingStatus', 'Completed')
            ->count();

        $cancelled = Booking::whereDate('BookingDate', $dateStr)
            ->where('BookingStatus', 'Cancelled')
            ->count();

        $weekData[] = [
            'date' => $dateObj->format('d M'),
            'pending' => $pending,
            'completed' => $completed,
            'cancelled' => $cancelled,
            'total' => $pending + $completed + $cancelled,
        ];
    }

    return view('admin.dashboard', compact(
        'totalBookings','pendingBookings','completedBookings',
        'todayBookings','todayCompletedBookings','todayPendingBookings',
        'todayRevenue','todayUniqueCustomers',

        'totalProducts','products','lowStockCount',

        'activeServices',

        'totalPayments','pendingPayments','verifiedPayments','totalRevenue',

        'recentBookings',
        'weekData'
    ));
}


    // ✅ Category List Page
    public function services()
    {
        $categories = DB::table('service_categories')->get();

        return view('admin.serviceCategory', [
            'categories' => $categories
        ]);
    }

    // ✅ Store New Category
    public function storeCategory(Request $request)
    {
        $request->validate([
            'ServiceCategory' => 'required|string|max:255'
        ]);

        DB::table('service_categories')->insert([
            'name' => $request->ServiceCategory,
        ]);

        return redirect()->route('admin.services')->with('success', '✅ Category added successfully!');
    }

    // ✅ Show Services by Category
    public function serviceListByCategory($categoryId)
    {
        $categories = DB::table('service_categories')->get();

        $services = DB::table('services')
            ->where('ServiceCategory', $categoryId)
            ->get();

        return view('admin.serviceList', [
            'categories' => $categories,
            'services' => $services,
            'selectedCategory' => $categoryId
        ]);
    }

    // ✅ Show Add Service Form
    public function addService(Request $request)
    {
        $categories = DB::table('service_categories')->get();
        $selectedCategory = $request->query('category', null);

        return view('admin.services.add', compact('categories', 'selectedCategory'));
    }

    // ✅ Store New Service
   public function storeService(Request $request)
{
    $request->validate([
        'ServiceCategory' => 'required|integer',
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'duration' => 'required|integer|min:5',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $imagePath = null;

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('services', 'public');
    }

    DB::table('services')->insert([
        'ServiceCategory' => $request->ServiceCategory,
        'name' => $request->name,
        'description' => $request->description,
        'image' => $imagePath,
        'price' => $request->price,
        'duration' => $request->duration,
    ]);

    return redirect()->route('admin.services.byCategory', $request->ServiceCategory)
                     ->with('success', '✅ Service added successfully!');
}

    // ✅ Delete Service
    public function deleteService($id)
    {
        DB::table('services')->where('id', $id)->delete();

        return back()->with('success', '🗑️ Service deleted successfully!');
    }

    public function updateServiceCategory(Request $request, $id)
{
    $request->validate(['ServiceCategory' => 'required|string|max:255']);

    DB::table('service_categories')
        ->where('id', $id)
        ->update(['name' => $request->ServiceCategory]);

    return back()->with('success', 'Category updated successfully!');
}

public function deleteServiceCategory($id)
{
    DB::table('service_categories')->where('id', $id)->delete();

    return back()->with('success', 'Category deleted successfully!');
}

    // ✅ Show Edit Service Form
public function editService($id)
{
    $service = DB::table('services')->where('id', $id)->first();
    $categories = DB::table('service_categories')->get();

    if (!$service) {
        return redirect()->route('admin.services')->with('error', 'Service not found.');
    }

    return view('admin.services.edit', compact('service', 'categories'));
}

// ✅ Update Service in Database
public function updateService(Request $request, $id)
{
    $request->validate([
        'ServiceCategory' => 'required|integer',
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'duration' => 'required|integer',
        'status' => 'required|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $service = DB::table('services')->where('id', $id)->first();
    $imagePath = $service->image;

    if ($request->hasFile('image')) {
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }

        $imagePath = $request->file('image')->store('services', 'public');
    }

    DB::table('services')->where('id', $id)->update([
        'ServiceCategory' => $request->ServiceCategory,
        'name' => $request->name,
        'description' => $request->description,
        'image' => $imagePath,
        'price' => $request->price,
        'duration' => $request->duration,
        'status' => $request->status,
    ]);

    return redirect()->route('admin.services.byCategory', $request->ServiceCategory)
                     ->with('success', '✅ Service updated successfully!');
}

public function bookings(Request $request)
{
    $filter       = $request->query('filter', 'all');
    $customerName = $request->query('customer_name');

    // base query (your table is "booking")
    $query = Booking::with(['customer', 'service'])
        ->leftJoin('payments', 'payments.BookingID', '=', 'booking.BookingID')
        ->select(
            'booking.*',
            'payments.PaymentStatus as payment_status',
            'payments.Amount as payment_amount'
        );

    // filter by status
    if ($filter === 'pending') {
        $query->where('booking.BookingStatus', 'Pending');
    } elseif ($filter === 'completed') {
        $query->where('booking.BookingStatus', 'Completed');
    } elseif ($filter === 'cancelled') {
        $query->where('booking.BookingStatus', 'Cancelled');
    }

    // filter by customer name
    if ($customerName) {
        $query->whereHas('customer', function ($q) use ($customerName) {
            $q->where('CustomerName', 'like', "%{$customerName}%");
        });
    }

    $bookings = $query
        ->orderBy('booking.BookingDate', 'desc')
        ->orderBy('booking.BookingTime', 'desc')
        ->get();

    // top counters
    $pendingBookings   = Booking::where('BookingStatus', 'Pending')->count();
    $completedBookings = Booking::where('BookingStatus', 'Completed')->count();
    $cancelledBookings = Booking::where('BookingStatus', 'Cancelled')->count();

    $paidPayments   = DB::table('payments')->where('PaymentStatus', 'Paid')->count();
    $unpaidPayments = DB::table('payments')
        ->whereIn('PaymentStatus', ['Awaiting Cash Payment', 'Pending'])
        ->count();

    return view('admin.bookings', compact(
        'bookings',
        'filter',
        'customerName',
        'pendingBookings',
        'completedBookings',
        'cancelledBookings',
        'paidPayments',
        'unpaidPayments'
    ));
}

public function markCompleted($id)
{
    $booking = Booking::findOrFail($id);
    $booking->BookingStatus = 'Completed';
    $booking->save();

    return redirect()->back()->with('success', '✅ Booking marked as completed.');
}

public function getPendingQueue()
{
    $pending = Booking::with(['customer', 'service'])
        ->leftJoin('payments', 'payments.BookingID', '=', 'booking.BookingID')
        ->where('BookingStatus', 'Pending')
        ->orderBy('BookingDate')
        ->orderBy('BookingTime')
        ->select('booking.*', 'payments.PaymentStatus as payment_status')
        ->get();

    return view('admin.queue.pendingList', compact('pending'));
}

    /**
     * ✅ Display all product categories (grouped by Category field)
     */
    public function productCategories()
    {
        $categories = Product::select(
                'Category',
                DB::raw('COUNT(*) as product_count'),
                DB::raw('SUM(CASE WHEN StockQuantity <= 5 THEN 1 ELSE 0 END) as low_stock_count')
            )
            ->whereNotNull('Category')
            ->groupBy('Category')
            ->orderBy('Category')
            ->get();

        return view('admin.productCategory', compact('categories'));
    }

    /**
     * ✅ Show edit category form
     */
    public function editProductCategory($category)
    {
        // $category is just a string value from the URL
        return view('admin.editProductCategory', ['category' => $category]);
    }

    /**
     * ✅ Update category name (mass update for all products in that category)
     */
    public function updateProductCategory(Request $request, $oldCategory)
    {
        $request->validate([
            'newCategory' => 'required|string|max:50',
        ]);

        Product::where('Category', $oldCategory)
            ->update(['Category' => $request->newCategory]);

        return redirect()
            ->route('admin.products.categories')
            ->with('success', 'Category updated successfully!');
    }

    /**
     * ✅ Delete a category (and all products under that category)
     */
    public function deleteProductCategory($category)
    {
        Product::where('Category', $category)->delete();

        return redirect()
            ->route('admin.products.categories')
            ->with('success', 'Category deleted successfully!');
    }

    /**
     * ✅ Create category via "placeholder product"
     */
    public function storeProductCategory(Request $request)
{
    $request->validate([
        'Category' => 'required|string|max:50',
    ]);

    Product::create([
        'ProductName'   => 'Placeholder Product',
        'Category'      => $request->Category,
        'Price'         => 0.00,
        'StockQuantity' => 0,
        'Description'   => null,
        'Image'         => null,
    ]);

    return redirect()
        ->route('admin.products.categories')
        ->with('success', 'Category added successfully!');
}


    /**
     * ✅ Show products by category (admin)
     */
    public function productsByCategory($category)
    {
        $categories = Product::select('Category')
            ->distinct()
            ->orderBy('Category')
            ->get();

        $products = Product::where('Category', $category)
            ->orderBy('ProductName')
            ->get();

        return view('admin.productsByCategory', compact('categories', 'products', 'category'));
    }

    /**
     * ✅ Show Add Product form
     */
    public function addProduct()
    {
        $categories = Product::select('Category')
            ->distinct()
            ->orderBy('Category')
            ->pluck('Category');

        return view('admin.addProduct', compact('categories'));
    }

    /**
     * ✅ Store new product
     */
    public function storeProduct(Request $request)
    {
        $request->validate([
    'ProductName'   => 'required|string|max:255',
    'Category'      => 'required|string|max:50',
    'Description'   => 'nullable|string',
    'Price'         => 'required|numeric|min:0',
    'StockQuantity' => 'required|integer|min:0',
    'Image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
]);


        $imagePath = null;

        if ($request->hasFile('Image')) {
            $imagePath = $request->file('Image')->store('products', 'public');
        }

        Product::create([
    'ProductName'   => $request->ProductName,
    'Category'      => $request->Category,
    'Description'   => $request->Description,
    'Price'         => $request->Price,
    'StockQuantity' => $request->StockQuantity,
    'Image'         => $imagePath,
]);


        return redirect()
            ->route('admin.products.byCategory', $request->Category)
            ->with('success', '✅ Product created successfully!');
    }

    /**
     * ✅ Show Edit Product form
     */
    public function editProduct($id)
    {
        $product = Product::findOrFail($id);

        $categories = Product::select('Category')
            ->distinct()
            ->orderBy('Category')
            ->pluck('Category');

        return view('admin.editProduct', compact('product', 'categories'));
    }

    /**
     * ✅ Update product
     */
    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

      $request->validate([
    'ProductName'   => 'required|string|max:255',
    'Category'      => 'required|string|max:50',
    'Description'   => 'nullable|string',
    'Price'         => 'required|numeric|min:0',
    'StockQuantity' => 'required|integer|min:0',
    'Image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
]);


        $imagePath = $product->Image;

        if ($request->hasFile('Image')) {
            // Optional: delete old image
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            $imagePath = $request->file('Image')->store('products', 'public');
        }

       $product->update([
    'ProductName'   => $request->ProductName,
    'Category'      => $request->Category,
    'Description'   => $request->Description,
    'Price'         => $request->Price,
    'StockQuantity' => $request->StockQuantity,
    'Image'         => $imagePath,
]);


        return redirect()
            ->route('admin.products.byCategory', $request->Category)
            ->with('success', '✅ Product updated successfully!');
    }

    /**
     * ✅ Delete product
     */
    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);

        if ($product->Image && Storage::disk('public')->exists($product->Image)) {
            Storage::disk('public')->delete($product->Image);
        }

        $category = $product->Category;

        $product->delete();

        return redirect()
            ->route('admin.products.byCategory', $category)
            ->with('success', '🗑️ Product deleted successfully!');
    }

    /* ============================================================
   PRODUCT PURCHASES (ADMIN / OWNER)
============================================================ */

/**
 * Show all product purchases (list)
 */
public function productPurchases()
{
    $purchases = DB::table('purchase')
        ->join('customer', 'purchase.CustomerID', '=', 'customer.CustomerID')
        ->select(
            'purchase.*',
            'customer.CustomerName',
            'customer.CustomerPhone'
        )
        ->orderBy('purchase.PurchaseDate', 'desc')
        ->get();

    return view('admin.productPurchases', compact('purchases'));
}

/**
 * Show single product purchase detail
 */
public function productPurchaseDetail($purchaseId)
{
    $purchase = DB::table('purchase')
        ->join('customer', 'purchase.CustomerID', '=', 'customer.CustomerID')
        ->join('users', 'customer.UserID', '=', 'users.UserID') // ✅ ADD THIS
        ->where('purchase.PurchaseID', $purchaseId)
        ->select(
            'purchase.*',
            'customer.CustomerName',
            'customer.CustomerPhone',
            'users.Address as CustomerAddress' // ✅ FIX
        )
        ->first();

    if (!$purchase) {
        return redirect()->route('admin.product.purchases')
            ->with('error', 'Purchase not found.');
    }

    $items = DB::table('purchaseitem')
        ->join('product', 'purchaseitem.ProductID', '=', 'product.ProductID')
        ->where('purchaseitem.PurchaseID', $purchaseId)
        ->select(
            'product.ProductName',
            'product.Image',
            'purchaseitem.Quantity',
            'purchaseitem.UnitPrice',
            'purchaseitem.Subtotal'
        )
        ->get();

    return view('admin.productPurchaseDetail', compact('purchase', 'items'));
}



}
