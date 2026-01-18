<?php

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function productList()
    {
        $products = Product::where('AvailabilityStatus', 'Active')->get();
        return view('customer.products.index', compact('products'));
    }

    public function buyNow($id)
    {
        $product = Product::findOrFail($id);
        return view('customer.products.buy_now', compact('product'));
    }

    public function confirmPurchase(Request $request)
{
    $customer = DB::table('customer')->where('UserID', Auth::id())->first();
    if (!$customer) {
        return back()->with('error', 'Customer profile not found.');
    }

    $request->validate([
        'product_id' => 'required',
        'quantity' => 'required|integer|min:1',
        'delivery_type' => 'required',
        'delivery_address' => 'required_if:delivery_type,Delivery'
    ]);

    $product = Product::findOrFail($request->product_id);

    if ($request->quantity > $product->StockQuantity) {
        return back()->with('error', 'Insufficient stock');
    }

    $deliveryFee = $request->delivery_type === 'Delivery' ? 5.00 : 0.00;
    $subtotal = $product->Price * $request->quantity;
    $total = $subtotal + $deliveryFee;

   $purchaseId = DB::table('purchase')->insertGetId([
    'CustomerID'      => $customer->CustomerID,
    'TotalAmount'     => $total,
    'DeliveryType'    => $request->delivery_type,
    'DeliveryFee'     => $deliveryFee,
    'DeliveryAddress' => $request->delivery_type === 'delivery'
        ? $customer->Address
        : null,
    'PurchaseDate'    => now(),
    'PurchaseStatus'  => 'Completed',
]);


    DB::table('purchaseitem')->insert([
        'PurchaseID' => $purchaseId,
        'ProductID' => $product->ProductID,
        'Quantity' => $request->quantity,
        'UnitPrice' => $product->Price,
        'Subtotal' => $subtotal
    ]);

    DB::table('payments')->insert([
        'CustomerID' => $customer->CustomerID,
        'PurchaseID' => $purchaseId,
        'Amount' => $total,
        'PaymentMethod' => 'Cash',
        'PaymentStatus' => 'Paid',
        'created_at' => now()
    ]);

    $product->decrement('StockQuantity', $request->quantity);

    return redirect()->route('customer.shop')
        ->with('success', 'Purchase successful!');
}
}
