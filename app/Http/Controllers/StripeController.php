<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Stripe\StripeClient;

class StripeController extends Controller
{
    /* ============================================================
        SHOW STRIPE PAYMENT PAGE (BOOKING OR PURCHASE)
    ============================================================ */
    public function index($bookingId = null, $purchaseId = null)
    {
        $booking = null;
        $purchase = null;

        // Booking payment
        if ($bookingId) {
            $booking = DB::table('booking')
                ->join('services', 'booking.id', '=', 'services.id') 
                ->where('booking.BookingID', $bookingId)
                ->select(
                    'booking.*',
                    'services.name as ServiceName',
                    'services.price as ServicePrice',
                    'services.description as ServiceDescription'
                )
                ->first();
        }

        // Purchase payment
        if ($purchaseId) {
            $purchase = DB::table('purchase')
                ->where('PurchaseID', $purchaseId)
                ->first();
        }

        // Nothing found
        if (!$booking && !$purchase) {
            return redirect()->route('customer.mypayments')
                ->with('error', 'Item not found.');
        }

        return view('customer.stripePayment', compact('booking', 'purchase'));
    }

    /* ============================================================
        PROCESS STRIPE PAYMENT
    ============================================================ */
 public function charge(Request $request)
{
    $request->validate([
        'stripeToken' => 'required|string',
        'booking_id'  => 'nullable|integer',
        'purchase_id' => 'nullable|integer',
    ]);

    $stripe = new StripeClient(env('STRIPE_SECRET'));

    try {
        $amount = 0;
        $description = '';

        // Booking payment
        if ($request->booking_id) {
            $booking = DB::table('booking')
                ->join('services', 'booking.id', '=', 'services.id')
                ->where('booking.BookingID', $request->booking_id)
                ->select('booking.*', 'services.name as ServiceName', 'services.price as ServicePrice')
                ->first();

            if (!$booking) {
                return back()->with('error', 'Booking not found.');
            }

            $amount = $booking->ServicePrice;
            $description = "Payment for Service: {$booking->ServiceName}";
        }
        // Purchase payment
        elseif ($request->purchase_id) {
            $purchase = DB::table('purchase')
                ->where('PurchaseID', $request->purchase_id)
                ->first();

            if (!$purchase) {
                return back()->with('error', 'Purchase not found.');
            }

            $amount = $purchase->TotalAmount;
            $description = "Payment for Purchase ID: {$purchase->PurchaseID}";
        }
        else {
            return back()->with('error', 'No payment target selected.');
        }

        // Stripe charge
        $charge = $stripe->charges->create([
            'amount'      => $amount * 100,
            'currency'    => 'usd',
            'source'      => $request->stripeToken,
            'description' => $description,
        ]);

        $customer = DB::table('customer')->where('UserID', Auth::id())->first();

        // Insert payment
        DB::table('payments')->insert([
            'CustomerID'      => $customer->CustomerID,
            'BookingID'       => $request->booking_id,
            'PurchaseID'      => $request->purchase_id,
            'Amount'          => $amount,
            'PaymentMethod'   => 'Stripe',
            'ReferenceNumber' => $charge->id,
            'PaymentStatus'   => 'Paid',
            'created_at'      => now(),
        ]);

        if ($request->purchase_id) {

    // 1️⃣ Mark purchase as completed
    DB::table('purchase')
        ->where('PurchaseID', $request->purchase_id)
        ->update([
            'PurchaseStatus' => 'Completed'
        ]);

    // 2️⃣ Get purchase items
    $items = DB::table('purchaseitem')
        ->where('PurchaseID', $request->purchase_id)
        ->get();

    // 3️⃣ Reduce stock for each product
    foreach ($items as $item) {
        DB::table('product')
            ->where('ProductID', $item->ProductID)
            ->decrement('StockQuantity', $item->Quantity);
    }
}



        // ❌ Removed booking status update here

        return redirect()->route('customer.mypayments')
            ->with('success', '✅ Stripe payment successful!');

    } catch (\Exception $e) {

        if ($request->booking_id) {
            DB::table('payments')->insert([
                'CustomerID'      => Auth::id(),
                'BookingID'       => $request->booking_id,
                'Amount'          => $amount ?? 0,
                'PaymentMethod'   => 'Stripe',
                'PaymentStatus'   => 'Failed',
                'ReferenceNumber' => null,
                'created_at'      => now(),
            ]);
        }

        return back()->with('error', '❌ Payment failed: ' . $e->getMessage());
    }
}


}
