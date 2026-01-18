<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ToyyibPayController extends Controller
{
    /**
     * CREATE BILL (Using DEV URL)
     */
    public function createBill($bookingId)
    {
        // Fetch booking + service price
        $booking = DB::table('booking')
            ->join('services', 'booking.id', '=', 'services.id')
            ->where('booking.BookingID', $bookingId)
            ->select(
                'booking.*',
                'services.name as ServiceName',
                'services.price as ServicePrice'
            )
            ->first();

        if (!$booking) {
            return back()->with('error', 'Booking not found.');
        }

        // Customer
        $customer = DB::table('customer')
            ->where('UserID', Auth::id())
            ->first();

        if (!$customer) {
            return back()->with('error', 'Customer profile missing.');
        }

        $amountCents = intval($booking->ServicePrice * 100);

        // Payload
        $payload = [
            "userSecretKey"            => env("TOYYIBPAY_SECRET_KEY"),
            "categoryCode"             => env("TOYYIBPAY_CATEGORY_CODE"),
            "billName"                 => "Carwash Online Banking Payment",
            "billDescription"          => "Payment for " . $booking->ServiceName,
            "billPriceSetting"         => 1,
            "billPayorInfo"            => 1,
            "billAmount"               => $amountCents,
            "billReturnUrl"            => route('toyyibpay.return'),
            "billCallbackUrl"          => route('toyyibpay.callback'),
            "billExternalReferenceNo"  => $bookingId, // IMPORTANT
            "billTo"                   => $customer->CustomerName,
            "billEmail"                => Auth::user()->Email,
            "billPhone"                => $customer->CustomerPhone,
        ];

        $url = rtrim(env("TOYYIBPAY_BASE_URL"), "/") . "/index.php/api/createBill";

        // cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        curl_close($ch);

        // Log
        file_put_contents(
            storage_path("logs/toyyib-create.log"),
            "RESPONSE:\n$response\nERROR:\n$curlError\n\n",
            FILE_APPEND
        );

        if (!$response) {
            return back()->with('error', 'ToyyibPay Error: ' . $curlError);
        }

        $json = json_decode($response, true);

        if (!isset($json[0]["BillCode"])) {
            return back()->with('error', "ToyyibPay Error: " . $response);
        }

        $billCode = $json[0]["BillCode"];

        return redirect(env("TOYYIBPAY_BASE_URL") . "/$billCode");
    }

    /**
     * CALLBACK (only for real server – usually NOT fired in XAMPP)
     */
    public function callback(Request $request)
{
    file_put_contents(
        storage_path("logs/toyyib-callback.log"),
        json_encode($request->all(), JSON_PRETTY_PRINT) . "\n\n",
        FILE_APPEND
    );

    $bookingId = $request->billExternalReferenceNo ?? $request->order_id;
    $status    = $request->status ?? $request->status_id;
    $trx       = $request->transaction_id ?? $request->refno;

    if (!$bookingId) {
        return response()->json(["error" => "Missing booking ID"], 400);
    }

    $price = DB::table('services')
        ->join('booking', 'services.id', '=', 'booking.id')
        ->where('booking.BookingID', $bookingId)
        ->value('services.price');

    DB::table('payments')->updateOrInsert(
        ["BookingID" => $bookingId],
        [
            "CustomerID"      => DB::table("booking")->where("BookingID",$bookingId)->value("CustomerID"),
            "PaymentMethod"   => "ToyyibPay (FPX)",
            "PaymentStatus"   => ($status == 1 ? "Paid" : "Failed"),
            "ReferenceNumber" => $trx ?? "CALLBACK-" . rand(1000,9999),
            "Amount"          => $price,
            "created_at"      => now(),
        ]
    );

    // ❌ Removed ALL booking status updates here

    return response()->json(["message" => "OK"]);
}

    /**
     * RETURN URL (always fires – used to save LOCAL PAYMENT RESULT)
     */
    public function returnUrl(Request $request)
{
    $bookingId = $request->billExternalReferenceNo 
              ?? $request->order_id 
              ?? null;

    if (!$bookingId) {
        return redirect()->route('customer.mypayments')
            ->with('error', 'Unable to identify booking.');
    }

    $status = $request->status ?? $request->status_id;
    $trx    = $request->transaction_id ?? $request->refno;

    $price = DB::table('services')
        ->join('booking', 'services.id', '=', 'booking.id')
        ->where('booking.BookingID', $bookingId)
        ->value('services.price');

    if ($status == 1) {

    

        DB::table('payments')->updateOrInsert(
            ["BookingID" => $bookingId],
            [
                "CustomerID"      => DB::table("booking")->where("BookingID",$bookingId)->value("CustomerID"),
                "PaymentMethod"   => "ToyyibPay (FPX)",
                "PaymentStatus"   => "Paid",
                "ReferenceNumber" => $trx ?? "RETURN-" . rand(1000,9999),
                "Amount"          => $price,
                "created_at"      => now(),
            ]
        );

        // ❌ Removed booking status update completely

        return redirect()->route('customer.mypayments')
            ->with('success', 'Payment successful!');
    }

    return redirect()->route('customer.mypayments')
        ->with('error', 'Payment failed.');
}

}
