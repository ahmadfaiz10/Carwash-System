<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\ServiceCategory;
use App\Models\Service;
use App\Models\Booking;
use App\Models\Product;
use App\Models\Customer;


class CustomerController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Kuala_Lumpur');
    }

    /* ==========================
       PROFILE
    ===========================*/
    public function profile()
    {
        $user = Auth::user();
        return view('customer.profile', compact('user'));
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('customer.editProfile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'FullName' => 'required|string',
            'PhoneNumber' => 'required|string',
            'Email' => 'required|email',
            'UserName' => 'required|string',
            'Address' => 'nullable|string'
        ]);

        $user = Auth::user();
        $user->FullName = $request->FullName;
        $user->PhoneNumber = $request->PhoneNumber;
        $user->Email = $request->Email;
        $user->UserName = $request->UserName;
        $user->Address = $request->Address;
        $user->save();

        return redirect()->route('customer.profile')->with('success', 'Profile updated successfully!');
    }

    /* ==========================
       DASHBOARD
    ===========================*/
   public function dashboard()
{
    $user = Auth::user();

    // Find matching customer profile
    $customer = DB::table('customer')
        ->where('UserID', $user->UserID)
        ->first();

    if (!$customer) {
        return view('customer.dashboard')->with([
            'user'             => $user,
            'customer'         => null,
            'totalBookings'    => 0,
            'completedBookings'=> 0,
            'pendingBookings'  => 0,
            'upcomingBooking'  => null,
            'recentBookings'   => collect(),
            'recentPayments'   => collect(),
        ]);
    }

    // STATS
    $totalBookings = DB::table('booking')
        ->where('CustomerID', $customer->CustomerID)
        ->count();

    $completedBookings = DB::table('booking')
        ->where('CustomerID', $customer->CustomerID)
        ->where('BookingStatus', 'Completed')
        ->count();

    $pendingBookings = DB::table('booking')
        ->where('CustomerID', $customer->CustomerID)
        ->where('BookingStatus', 'Pending')
        ->count();

    // UPCOMING BOOKING (next one)
    $now = Carbon::now('Asia/Kuala_Lumpur');

    $upcomingBooking = DB::table('booking')
        ->join('services', 'booking.id', '=', 'services.id')
        ->where('booking.CustomerID', $customer->CustomerID)
        ->where('booking.BookingStatus', '!=', 'Cancelled')
        ->where(function ($q) use ($now) {
            $q->where('booking.BookingDate', '>', $now->toDateString())
              ->orWhere(function ($q2) use ($now) {
                  $q2->where('booking.BookingDate', $now->toDateString())
                     ->where('booking.BookingTime', '>=', $now->format('H:i:s'));
              });
        })
        ->orderBy('booking.BookingDate')
        ->orderBy('booking.BookingTime')
        ->select(
            'booking.*',
            'services.name as ServiceName',
            'services.description as ServiceDescription',
            'services.price as ServicePrice',
            'services.duration as ServiceDuration'
        )
        ->first();

    // RECENT BOOKINGS (latest 3)
    $recentBookings = DB::table('booking')
        ->join('services', 'booking.id', '=', 'services.id')
        ->where('booking.CustomerID', $customer->CustomerID)
        ->orderBy('booking.BookingDate', 'desc')
        ->orderBy('booking.BookingTime', 'desc')
        ->limit(3)
        ->select(
            'booking.*',
            'services.name as ServiceName',
            'services.price as ServicePrice'
        )
        ->get();

    // RECENT PAYMENTS (latest 3)
    $recentPayments = DB::table('payments')
        ->where('CustomerID', $customer->CustomerID)
        ->orderBy('created_at', 'desc')
        ->limit(3)
        ->get();

    return view('customer.dashboard', [
        'user'              => $user,
        'customer'          => $customer,
        'totalBookings'     => $totalBookings,
        'completedBookings' => $completedBookings,
        'pendingBookings'   => $pendingBookings,
        'upcomingBooking'   => $upcomingBooking,
        'recentBookings'    => $recentBookings,
        'recentPayments'    => $recentPayments,
    ]);
}


    /* ==========================
       SERVICES & BOOKINGS
    ===========================*/
    public function viewServices()
    {
        $categories = ServiceCategory::all();
        return view('customer.services', compact('categories'));
    }

    public function servicesByCategory($id)
    {
        $categories = ServiceCategory::all();
        $category = ServiceCategory::with('services')->findOrFail($id);

        return view('customer.servicesByCategory', [
            'categories' => $categories,
            'services' => $category->services,
            'selectedCategory' => $id
        ]);
    }

    private function getBookingConfig()
{
    return [
        // Opening and closing time (per day)
        'open_time'         => '09:00',
        'close_time'        => '17:00',

        // Time granularity for the slot generator (in minutes)
        'slot_step_minutes' => 30,

        // Maximum number of overlapping cars per time window
        'max_overlap'       => 2,

        // How many days ahead customers are allowed to book
        'max_days_ahead'    => 14,

        // Daily breaks (e.g. lunch, maintenance) – by time of day
        'breaks' => [
            ['start' => '13:00', 'end' => '14:00'], // lunch break
        ],

        // Days that are closed: 0 = Sunday, 6 = Saturday
        'closed_weekdays' => [0], // closed on Sunday
    ];
}


    public function bookServices($id)
{
    $service = Service::findOrFail($id);

    $bookedSlots = DB::table('booking')
        ->where('BookingStatus', '!=', 'Cancelled')
        ->where('id', $service->id) // service ID column
        ->select('BookingDate', 'BookingTime')
        ->get();

    return view('customer.bookServices', compact('service', 'bookedSlots'));
}

public function storeBooking(Request $request)
{
    $request->validate([
        'service_id'  => 'required|exists:services,id',
        'BookingDate' => 'required|date',
        'BookingTime' => 'required',
        'PlateNumber' => 'required|string|max:20'
    ]);

    $config = $this->getBookingConfig();

    // Get customer
    $customer = DB::table('customer')
        ->where('UserID', auth()->id())
        ->first();

    if (!$customer) {
        return back()->with('error', 'Customer profile not found.');
    }

    // Get service
    $service = DB::table('services')
        ->where('id', $request->service_id)
        ->first();

    if (!$service) {
        return back()->with('error', 'Service not found.');
    }

    /* ===========================
       DATE VALIDATION
    =========================== */
    $today       = Carbon::today('Asia/Kuala_Lumpur');
    $bookingDate = Carbon::parse($request->BookingDate, 'Asia/Kuala_Lumpur')->startOfDay();

    if ($bookingDate->lt($today)) {
        return back()->with('error', 'You cannot book a past date.');
    }

    if ($bookingDate->gt($today->copy()->addDays($config['max_days_ahead']))) {
        return back()->with('error', 'You can only book up to '.$config['max_days_ahead'].' days in advance.');
    }

    if (in_array($bookingDate->dayOfWeek, $config['closed_weekdays'])) {
        return back()->with('error', 'We are closed on the selected day.');
    }

    /* ===========================
       TIME VALIDATION
    =========================== */
    $slotStart = strtotime($request->BookingTime);
    $slotEnd   = $slotStart + ($service->duration * 60);

    $openTime  = strtotime($config['open_time']);
    $closeTime = strtotime($config['close_time']);

    if ($slotStart < $openTime || $slotEnd > $closeTime) {
        return back()->with('error', 'Selected time is outside our working hours.');
    }

    // Prevent past-time booking today
    $now = Carbon::now('Asia/Kuala_Lumpur');
    if ($bookingDate->isSameDay($now) && $slotStart <= strtotime($now->format('H:i'))) {
        return back()->with('error', 'You cannot book a time that has already passed.');
    }

    // Break time check
    foreach ($config['breaks'] as $break) {
        $breakStart = strtotime($break['start']);
        $breakEnd   = strtotime($break['end']);

        if ($slotStart < $breakEnd && $slotEnd > $breakStart) {
            return back()->with('error', 'This time is not available due to a scheduled break.');
        }
    }

    /* ===========================
       ✅ FIXED OVERLAP CHECK
       (MATCHES getAvailableTimes)
    =========================== */
 $exists = DB::table('booking')
    ->where('BookingDate', $request->BookingDate)
    ->where('BookingTime', $request->BookingTime)
    ->where('BookingStatus', 'Pending')
    ->exists();

if ($exists) {
    return back()->with('error', 
        '❌ Sorry! The time slot ' .
        Carbon::parse($request->BookingTime)->format('h:i A') .
        ' on ' .
        Carbon::parse($request->BookingDate)->format('d/m/Y') .
        ' has just been booked by another customer. Please choose a different time.'
    );
}



    /* ===========================
       SAVE BOOKING
    =========================== */
    DB::table('booking')->insert([
        'CustomerID'    => $customer->CustomerID,
        'id'            => $request->service_id,
        'BookingDate'   => $request->BookingDate,
        'BookingTime'   => $request->BookingTime,
        'PlateNumber'   => strtoupper($request->PlateNumber),
        'BookingStatus' => 'Pending',
        'created_at'    => now(),
    ]);

    return redirect()->route('customer.dashboard')
        ->with('success', '✅ Booking confirmed successfully.');
}

public function getAvailableTimes(Request $request)
{
    $serviceId = $request->input('service_id');
    $date      = $request->input('date');

    $config = $this->getBookingConfig();

    // Get service
    $service = DB::table('services')->where('id', $serviceId)->first();
    if (!$service) {
        return response()->json(['times' => []], 404);
    }

    $durationMinutes = (int) $service->duration;

    $bookingDate = Carbon::parse($date, 'Asia/Kuala_Lumpur')->startOfDay();
    $today       = Carbon::today('Asia/Kuala_Lumpur');
    $now         = Carbon::now('Asia/Kuala_Lumpur');

    // ❌ Invalid day (past, too far, or closed)
    if (
        $bookingDate->lt($today) ||
        $bookingDate->gt($today->copy()->addDays($config['max_days_ahead'])) ||
        in_array($bookingDate->dayOfWeek, $config['closed_weekdays'])
    ) {
        return response()->json(['times' => []]);
    }

    /**
     * ✅ Get existing bookings COUNT per time
     * Only Pending bookings block slots
     */
    $existingBookings = DB::table('booking')
        ->where('BookingDate', $date)
        ->where('BookingStatus', 'Pending')
        ->select('BookingTime', DB::raw('COUNT(*) as total'))
        ->groupBy('BookingTime')
        ->get()
        ->keyBy('BookingTime');

    $openTime        = strtotime($config['open_time']);
    $closeTime       = strtotime($config['close_time']);
    $stepSeconds     = $config['slot_step_minutes'] * 60;
    $durationSeconds = $durationMinutes * 60;

    $availableTimes = [];

    for ($time = $openTime; $time + $durationSeconds <= $closeTime; $time += $stepSeconds) {

        $slotStart = $time;
        $slotEnd   = $time + $durationSeconds;
        $slotTime  = date("H:i:s", $slotStart);

        // ❌ Skip break time
        foreach ($config['breaks'] as $break) {
            if (
                $slotStart < strtotime($break['end']) &&
                $slotEnd > strtotime($break['start'])
            ) {
                continue 2;
            }
        }

        // ❌ Block past time (today only)
        if ($bookingDate->isSameDay($today)) {
            $slotDateTime = Carbon::createFromFormat(
                'Y-m-d H:i',
                $date . ' ' . date('H:i', $slotStart),
                'Asia/Kuala_Lumpur'
            );

            if ($slotDateTime->lte($now)) {
                $availableTimes[] = [
                    'time'      => date("H:i", $slotStart),
                    'available' => false,
                    'remaining' => 0
                ];
                continue;
            }
        }

        // ✅ Count bookings for this exact slot
        $bookedCount = $existingBookings[$slotTime]->total ?? 0;
        $remaining   = $config['max_overlap'] - $bookedCount;

        $availableTimes[] = [
            'time'      => date("H:i", $slotStart),
            'available' => $remaining > 0,
            'remaining' => max(0, $remaining)
        ];
    }

    return response()->json(['times' => $availableTimes]);
}




    public function showBookings(Request $request)
{
    $customer = DB::table('customer')->where('UserID', Auth::id())->first();
    if (!$customer) return back()->with('error', 'Customer profile not found.');

    $filter = $request->query('filter', 'all');

    // Fetch bookings
    $query = DB::table('booking')
        ->join('services', 'booking.id', '=', 'services.id')
        ->where('booking.CustomerID', $customer->CustomerID)
        ->select(
    'booking.BookingID',
    'booking.BookingDate',
    'booking.BookingTime',
    'booking.BookingStatus',
    'booking.PlateNumber',
    'services.name',
    'services.description',
    'services.price'
)

        ->orderBy('booking.created_at', 'desc');

    if ($filter === 'completed') $query->where('booking.BookingStatus', 'Completed');

    $bookings = $query->get();

    // Fetch payments for this customer
    $payments = DB::table('payments')
        ->where('CustomerID', $customer->CustomerID)
        ->get()
        ->keyBy('BookingID'); // key by BookingID for easy lookup in Blade

    return view('customer.bookings', compact('bookings', 'filter', 'payments'));
}

    public function cancelBooking($id)
    {
        $booking = Booking::findOrFail($id);

        $bookingTime = Carbon::parse($booking->BookingDate . ' ' . $booking->BookingTime, 'Asia/Kuala_Lumpur');

        if ($bookingTime->diffInMinutes(now(), false) > -30) {
            return back()->with('error', '⛔ Cannot cancel within 30 minutes before booking.');
        }

        $booking->BookingStatus = 'Cancelled';
        $booking->save();

        return back()->with('success', '✅ Your booking has been cancelled successfully.');
    }

  // Confirm payment for Cash / Online Banking
public function confirmPayment(Request $request, $bookingId)
{
    $customer = DB::table('customer')->where('UserID', auth()->id())->first();
    if (!$customer) {
        return response()->json(['success' => false, 'message' => 'Customer profile not found']);
    }

    // Fetch booking and join service to get price
    $booking = DB::table('booking')
        ->join('services', 'booking.id', '=', 'services.id')
        ->where('booking.BookingID', $bookingId)
        ->select('booking.*', 'services.price as ServicePrice')
        ->first();

    if (!$booking) {
        return response()->json(['success' => false, 'message' => 'Booking not found']);
    }

    $method = $request->method ?? 'counter';
    $reference = $method === 'counter' ? 'COUNTER-' . $bookingId : null;

    // Insert or update payment
    DB::table('payments')->updateOrInsert(
        ['BookingID' => $bookingId],
        [
            'CustomerID'      => $customer->CustomerID,
            'PaymentMethod'   => $method,
            'PaymentStatus'   => 'Verified',
            'Amount'          => $booking->ServicePrice ?? 0,
            'ReferenceNumber' => $reference,
        ]
    );

    // ❌ REMOVED booking status update (this was: BookingStatus = Completed)

    return response()->json(['success' => true]);
}

// Mark payment as paid (AJAX / Pay Now)
public function markPaid(Request $request, $bookingId)
{
    $customer = DB::table('customer')->where('UserID', auth()->id())->first();
    if (!$customer) {
        return response()->json(['success' => false, 'message' => 'Customer profile not found']);
    }

    // Fetch booking
    $booking = DB::table('booking')
        ->join('services', 'booking.id', '=', 'services.id')
        ->where('booking.BookingID', $bookingId)
        ->select('booking.*', 'services.price as ServicePrice')
        ->first();

    if (!$booking) {
        return response()->json(['success' => false, 'message' => 'Booking not found']);
    }

    if ($booking->BookingStatus === 'Cancelled') {
        return response()->json(['success' => false, 'message' => 'Cannot pay for cancelled booking.']);
    }

    // CASH = Not paid yet, so DO NOT mark as paid.
    DB::table('payments')->updateOrInsert(
        ['BookingID' => $bookingId],
        [
            'CustomerID'      => $customer->CustomerID,
            'PaymentMethod'   => 'Cash',
            'PaymentStatus'   => 'Awaiting Cash Payment',
            'Amount'          => $booking->ServicePrice,
            'ReferenceNumber' => "CASH-" . $bookingId,
            'created_at'      => now()
        ]
    );

    return response()->json(['success' => true]);
}


    /* ==========================
       PRODUCTS & CART
    ===========================*/
    public function productCategories()
    {
        $categories = Product::select('Category')->distinct()->orderBy('Category')->get();
        return view('customer.productCategory', compact('categories'));
    }

    public function productsByCategory($category)
    {
        $categories = Product::select('Category')->distinct()->orderBy('Category')->get();
        $products = Product::where('Category', $category)->get();

        return view('customer.productByCategory', compact('categories', 'products', 'category'));
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) return redirect()->route('customer.cart')->with('error', 'Cart is empty.');

        $customer = DB::table('customer')->where('UserID', Auth::id())->first();
        if (!$customer) return back()->with('error', 'Customer not found.');

        DB::beginTransaction();

        try {
            $totalAmount = collect($cart)->sum('subtotal');

            $purchaseId = DB::table('purchase')->insertGetId([
                'CustomerID' => $customer->CustomerID,
                'TotalAmount' => $totalAmount,
                'PaymentMethod' => 'Cash',
                'PurchaseDate' => now(),
                'PurchaseStatus' => 'Completed',
            ]);

            foreach ($cart as $item) {
                DB::table('purchaseitem')->insert([
                    'PurchaseID' => $purchaseId,
                    'ProductID' => $item['product_id'],
                    'Quantity' => $item['quantity'],
                    'UnitPrice' => $item['price'],
                    'Subtotal' => $item['subtotal'],
                ]);

                DB::table('product')->where('ProductID', $item['product_id'])
                    ->decrement('StockQuantity', $item['quantity']);
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('customer.dashboard')
    ->with('success', "✅ Purchase successful! RM " . number_format($totalAmount, 2));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }
    public function myPurchases()
{
    $customer = DB::table('customer')->where('UserID', Auth::id())->first();
    if (!$customer) {
        return back()->with('error', 'Customer profile not found.');
    }

    // Fetch all purchases for this customer, with items
    $purchases = DB::table('purchase')
        ->where('CustomerID', $customer->CustomerID)
        ->orderBy('PurchaseDate', 'desc')
        ->get()
        ->map(function($purchase) {
            $items = DB::table('purchaseitem')
                ->join('product', 'purchaseitem.ProductID', '=', 'product.ProductID')
                ->where('purchaseitem.PurchaseID', $purchase->PurchaseID)
                ->select('product.ProductName', 'product.Image', 'purchaseitem.Quantity', 'purchaseitem.UnitPrice', 'purchaseitem.Subtotal')
                ->get();
            $purchase->items = $items;
            return $purchase;
        });

    return view('customer.myPurchase', compact('purchases'));
}

public function checkoutReview(Request $request)
{
    $productId = $request->product_id;
    $quantity = max(1, (int) $request->quantity);

    $product = Product::find($productId);
    if (!$product) return back()->with('error', 'Product not found.');

   $user = Auth::user();
$customer = DB::table('customer')->where('UserID', $user->UserID)->first();
    if (!$customer) return back()->with('error', 'Customer profile not found.');

    $cartItems = [
        (object)[
            'product_id' => $product->ProductID,
            'product_name' => $product->ProductName,
            'price' => $product->Price,
            'quantity' => $quantity,
            'image' => $product->Image,
            'subtotal' => $product->Price * $quantity,
        ]
    ];

    $total = array_sum(array_column($cartItems, 'subtotal')) + 3; // RM3 delivery

   return view('customer.checkoutReview', [
    'user' => $user,
    'customer' => $customer,
    'cartItems' => $cartItems,
    'total' => $total
]);
}

public function checkoutConfirm(Request $request)
{
    $request->validate([
        'product_id'     => 'required',
        'quantity'       => 'required|integer|min:1',
        'delivery_type'  => 'required',
        'payment_method' => 'required'
    ]);

    $product  = Product::findOrFail($request->product_id);

    // ✅ USER (address is HERE)
    $user = Auth::user();

    // ✅ CUSTOMER (for CustomerID only)
    $customer = DB::table('customer')
        ->where('UserID', $user->UserID)
        ->first();

    $deliveryFee = $request->delivery_type === 'delivery' ? 3 : 0;
    $totalAmount = ($product->Price * $request->quantity) + $deliveryFee;

    DB::beginTransaction();

    try {
        $purchaseId = DB::table('purchase')->insertGetId([
            'CustomerID'      => $customer->CustomerID,
            'TotalAmount'     => $totalAmount,
            'DeliveryType'    => $request->delivery_type === 'delivery' ? 'Delivery' : 'Pickup',
            'DeliveryFee'     => $deliveryFee,

            // ✅ FIXED HERE
            'DeliveryAddress' => $request->delivery_type === 'delivery'
                ? $user->Address
                : null,

            'PurchaseDate'    => now(),
            'PurchaseStatus'  => 'Pending',
        ]);

        DB::table('purchaseitem')->insert([
            'PurchaseID' => $purchaseId,
            'ProductID'  => $product->ProductID,
            'Quantity'   => $request->quantity,
            'UnitPrice'  => $product->Price,
            'Subtotal'   => $product->Price * $request->quantity,
        ]);

        DB::commit();

        if ($request->payment_method === 'stripe') {
            return redirect()->route('stripe.purchase.form', $purchaseId);
        }

        return redirect()->route('toyyibpay.purchase.create', $purchaseId);

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', $e->getMessage());
    }
}


}
