<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /* ============================================================
        CUSTOMER — SHOW PAYMENT FORM
    ============================================================ */
    public function showPaymentForm($bookingId)
    {
        $customer = DB::table('customer')->where('UserID', Auth::id())->first();
        if (!$customer) return back()->with('error', 'Customer profile not found.');

        $booking = DB::table('booking')
            ->join('services', 'booking.id', '=', 'services.id')
            ->where('booking.BookingID', $bookingId)
            ->select(
                'booking.*',
                'services.name as ServiceName',
                'services.description as ServiceDescription',
                'services.price'
            )
            ->first();

        if (!$booking) return back()->with('error', 'Booking not found.');

        return view('customer.paymentForm', compact('booking'));
    }

    /* ============================================================
        CUSTOMER — STORE PAYMENT
    ============================================================ */
    public function storePayment(Request $request)
    {
        $request->validate([
            'booking_id'       => 'required|integer',
            'amount'           => 'required|numeric|min:1',
            'payment_method'   => 'required|string',
            'reference_number' => 'nullable|string',
            'bank_type'        => 'nullable|string',
        ]);

        $customer = DB::table('customer')->where('UserID', Auth::id())->first();
        if (!$customer) return back()->with('error', 'Customer profile not found.');

        DB::table('payments')->insert([
            'CustomerID'      => $customer->CustomerID,
            'BookingID'       => $request->booking_id,
            'Amount'          => $request->amount,
            'PaymentMethod'   => $request->payment_method,
            'ReferenceNumber' => $request->reference_number,
            'BankType'        => $request->bank_type,
            'PaymentStatus'   => 'Unpaid',
            'created_at'      => Carbon::now('Asia/Kuala_Lumpur'),
        ]);

        return redirect()->route('customer.mypayments')
            ->with('success', 'Payment submitted. Waiting for staff confirmation.');
    }

    /* ============================================================
        CUSTOMER — VIEW OWN PAYMENTS
    ============================================================ */
    public function myPayments()
    {
        $customer = DB::table('customer')->where('UserID', Auth::id())->first();
        if (!$customer) return back()->with('error', 'Customer profile not found.');

    $payments = DB::table('payments')
    ->leftJoin('booking', 'payments.BookingID', '=', 'booking.BookingID')
    ->leftJoin('services', 'booking.id', '=', 'services.id')
    ->leftJoin('purchase', 'payments.PurchaseID', '=', 'purchase.PurchaseID')
    ->leftJoin('purchaseitem', 'purchase.PurchaseID', '=', 'purchaseitem.PurchaseID')
    ->leftJoin('product', 'purchaseitem.ProductID', '=', 'product.ProductID')
    ->where('payments.CustomerID', $customer->CustomerID)
    ->select(
        'payments.*',
        'services.name as ServiceName',
        'product.ProductName as ProductName'
    )
    ->orderBy('payments.created_at', 'desc')
    ->get();



        return view('customer.myPayments', compact('payments'));
    }

    /* ============================================================
        ADMIN — PAYMENT MANAGEMENT + SUMMARY
    ============================================================ */
    public function payments(Request $request)
    {
        /* Base Query */
        $query = DB::table('payments')
            ->leftJoin('customer', 'payments.CustomerID', '=', 'customer.CustomerID')
            ->leftJoin('booking', 'payments.BookingID', '=', 'booking.BookingID')
            ->leftJoin('services', 'booking.id', '=', 'services.id')
            ->select(
                'payments.*',
                'customer.CustomerName',
                'customer.CustomerPhone',
                'booking.BookingDate',
                'booking.BookingTime',
                'services.name as ServiceName',
                'services.description as ServiceDescription',
                'services.price'
            );

        /* --- Search --- */
        if ($request->search) {
            $query->where('customer.CustomerName', 'LIKE', "%{$request->search}%");
        }

        /* --- Payment Method Filter --- */
        if ($request->method == "online") {
            $query->whereIn('payments.PaymentMethod', [
                "Online", "Stripe", "FPX", "ToyyibPay", "ToyyibPay (FPX)", "Card", "Credit Card"
            ]);
        }

        if ($request->method == "cash") {
            $query->where('payments.PaymentMethod', "Cash");
        }

        /* Get Results */
        $payments = $query->orderBy('payments.created_at', 'desc')->get();

        /* ============================================================
            FIXED DAILY REVENUE — USING MALAYSIA TIME
        ============================================================ */
        $today = Carbon::now('Asia/Kuala_Lumpur')->toDateString();

        /* Total Paid */
        $todayRevenue = DB::table('payments')
            ->where('PaymentStatus', 'Paid')
            ->whereDate('created_at', $today)
            ->sum('Amount');

        /* Cash */
        $todayCash = DB::table('payments')
            ->where('PaymentStatus', 'Paid')
            ->where('PaymentMethod', 'Cash')
            ->whereDate('created_at', $today)
            ->sum('Amount');

        /* Online */
        $todayOnline = DB::table('payments')
            ->where('PaymentStatus', 'Paid')
            ->whereIn('PaymentMethod', [
                "Online", "Stripe", "FPX", "ToyyibPay", "ToyyibPay (FPX)", "Card", "Credit Card"
            ])
            ->whereDate('created_at', $today)
            ->sum('Amount');

        /* Count Paid */
        $todayVerifiedCount = DB::table('payments')
            ->where('PaymentStatus', 'Paid')
            ->whereDate('created_at', $today)
            ->count();

        /* Summary Blocks */
        $countPending =
            $payments->where('PaymentStatus', 'Unpaid')->count() +
            $payments->where('PaymentStatus', 'Awaiting Cash Payment')->count();

        $countPaid   = $payments->where('PaymentStatus', 'Paid')->count();
        $countFailed = $payments->where('PaymentStatus', 'Failed')->count();


        return view('admin.payments', compact(
            'payments',
            'countPending',
            'countPaid',
            'countFailed',
            'todayRevenue',
            'todayCash',
            'todayOnline',
            'todayVerifiedCount'
        ));
    }

    /* ============================================================
        ADMIN — APPROVE / REJECT PAYMENT
    ============================================================ */
    public function approvePayment($id)
    {
        DB::table('payments')->where('PaymentID', $id)->update([
    'PaymentStatus' => 'Paid',
    'updated_at'    => Carbon::now('Asia/Kuala_Lumpur'),
]);


        return back()->with('success', 'Payment approved successfully.');
    }

    public function rejectPayment($id)
    {
        DB::table('payments')->where('PaymentID', $id)->update([
    'PaymentStatus' => 'Failed',
    'updated_at'    => Carbon::now('Asia/Kuala_Lumpur'),
]);


        return back()->with('success', 'Payment rejected.');
    }

    public function showPurchasePayment($purchaseId)
{
    $purchase = DB::table('purchase')
        ->where('PurchaseID', $purchaseId)
        ->first();

    if (!$purchase) {
        return back()->with('error', 'Purchase not found.');
    }

    return view('customer.paymentPurchase', compact('purchase'));
}
public function purchasePaymentForm($purchaseId)
{
    $purchase = DB::table('purchase')->where('PurchaseID', $purchaseId)->first();
    if (!$purchase) abort(404);

    return view('customer.paymentPurchase', compact('purchase'));
}

}
