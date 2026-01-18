@extends('layouts.customer')

@section('title', 'Payment')

@section('content')
<h2>💳 Payment</h2>

<p><strong>Total Amount:</strong> RM {{ number_format($purchase->TotalAmount,2) }}</p>
<p><strong>Delivery Type:</strong> {{ $purchase->DeliveryType }}</p>

<form method="POST" action="#">
    @csrf
    <button style="background:#28a745;color:#fff;padding:10px 20px;border:none;border-radius:6px;">
        Pay Cash
    </button>
</form>

<p style="margin-top:10px;color:#777;">
    (Payment logic can be extended later)
</p>
@endsection
