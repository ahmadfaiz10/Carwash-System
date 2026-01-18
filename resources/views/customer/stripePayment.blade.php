@extends('layouts.customer')
@section('title', 'Secure Payment')
@section('fullwidth', true)

@section('content')

<style>
/* PAGE */
.payment-page {
    background:#ffffff;
    min-height:100vh;
    padding:40px 40px 60px;
    font-family:'Poppins',sans-serif;
}

/* GRID */
.payment-layout {
    max-width:1300px;
    margin:0 auto;
    display:grid;
    grid-template-columns:420px 1fr;
    gap:48px;
}

/* LEFT — SUMMARY */
.summary-panel {
    border:1px solid #e5e7eb;
    border-radius:12px;
    padding:24px;
    background:#fafafa;
}

.summary-title {
    font-size:18px;
    font-weight:700;
    margin-bottom:16px;
}

.summary-row {
    display:flex;
    justify-content:space-between;
    font-size:14px;
    margin-bottom:8px;
}

.summary-muted {
    color:#6b7280;
    font-size:13px;
}

.summary-total {
    font-size:22px;
    font-weight:800;
    color:#111827;
}

/* RIGHT — PAYMENT */
.payment-panel {
    border:1px solid #e5e7eb;
    border-radius:12px;
    padding:32px;
}

.section-title {
    font-size:18px;
    font-weight:700;
    margin-bottom:6px;
}

.section-desc {
    font-size:14px;
    color:#6b7280;
    margin-bottom:18px;
}

/* STRIPE */
#card-element {
    padding:14px;
    border:1px solid #d1d5db;
    border-radius:8px;
    background:#ffffff;
}

/* BRAND ICONS */
.brand-icons img {
    width:42px;
    opacity:.25;
    transition:.2s;
}
.brand-icons img.active {
    opacity:1;
}

/* PAY BUTTON */
.pay-btn {
    width:100%;
    margin-top:24px;
    padding:14px;
    border-radius:8px;
    background:#111827;
    color:#ffffff;
    font-size:16px;
    font-weight:700;
    border:none;
}
.pay-btn:hover {
    background:#000000;
}

/* SECURITY */
.secure-row {
    margin-top:14px;
    font-size:13px;
    color:#6b7280;
    display:flex;
    align-items:center;
    gap:8px;
}
</style>

<div class="payment-page">

    <div class="payment-layout">

        {{-- ORDER SUMMARY --}}
        <div class="summary-panel">

            <div class="summary-title">Order Summary</div>

            @php
                $itemName  = $booking->ServiceName ?? $purchase->ProductName ?? 'Item';
                $itemDesc  = $booking->ServiceDescription ?? $purchase->Description ?? '';
                $itemPrice = $booking->ServicePrice ?? $purchase->TotalAmount ?? 0;
            @endphp

            <div class="fw-semibold">{{ $itemName }}</div>
            <div class="summary-muted">{{ $itemDesc }}</div>

            @if(isset($booking))
            <hr class="my-3">
            <div class="summary-muted">Date: {{ $booking->BookingDate }}</div>
            <div class="summary-muted">Time: {{ $booking->BookingTime }}</div>
            <div class="summary-muted">Plate: {{ $booking->PlateNumber }}</div>
            @endif

            <hr class="my-3">

            <div class="summary-row">
                <span>Subtotal</span>
                <span>RM {{ number_format($itemPrice,2) }}</span>
            </div>

            <div class="summary-row">
                <strong>Total</strong>
                <strong class="summary-total">
                    RM {{ number_format($itemPrice,2) }}
                </strong>
            </div>

        </div>

        {{-- PAYMENT FORM --}}
        <div class="payment-panel">

            <div class="section-title">Card Payment</div>
            <div class="section-desc">
                Enter your card details to complete the payment.
            </div>

            <div id="card-element"></div>

            <div class="brand-icons d-flex gap-3 mt-3">
                <img id="visa-icon" src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png">
                <img id="mastercard-icon" src="https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png">
                <img id="amex-icon" src="https://upload.wikimedia.org/wikipedia/commons/3/30/American_Express_logo.svg">
            </div>

            <form id="payment-form" method="POST" action="{{ route('stripe.charge') }}">
                @csrf
                <input type="hidden" name="stripeToken" id="stripe-token">
                <input type="hidden" name="booking_id" value="{{ $booking->BookingID ?? '' }}">
                <input type="hidden" name="purchase_id" value="{{ $purchase->PurchaseID ?? '' }}">
            </form>

            <div class="secure-row">
                <i class="bi bi-lock-fill"></i>
                Secured by Stripe · No card data stored
            </div>

            <button class="pay-btn" id="submit-btn">
                Pay RM {{ number_format($itemPrice,2) }}
                <span class="spinner-border spinner-border-sm d-none ms-2" id="loading"></span>
            </button>

        </div>

    </div>

</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
const stripe = Stripe("{{ env('STRIPE_KEY') }}");
const elements = stripe.elements();
const card = elements.create('card',{hidePostalCode:true});
card.mount('#card-element');

card.on('change', e=>{
    ['visa','mastercard','amex'].forEach(b=>{
        document.getElementById(b+'-icon').classList.remove('active');
    });
    if(e.brand && document.getElementById(e.brand+'-icon')){
        document.getElementById(e.brand+'-icon').classList.add('active');
    }
});

document.getElementById('submit-btn').onclick = ()=>{
    const loading=document.getElementById('loading');
    loading.classList.remove('d-none');
    stripe.createToken(card).then(r=>{
        if(r.error){
            alert(r.error.message);
            loading.classList.add('d-none');
        }else{
            document.getElementById('stripe-token').value=r.token.id;
            document.getElementById('payment-form').submit();
        }
    });
};
</script>

@endsection
