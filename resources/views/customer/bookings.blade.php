@extends('layouts.customer')
@php use Carbon\Carbon; @endphp

@section('title','My Bookings')

@section('content')

@if (session('success'))
<div class="alert alert-success text-center fw-bold">
    {{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="alert alert-danger text-center fw-bold">
    {{ session('error') }}
</div>
@endif

<style>
body {
    background: #f8fafc; /* light neutral gray */
    font-family: 'Poppins', sans-serif;
}


.bk-page {
    width: 100%;
    padding: 0 12px;
}

.bk-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 20px;
}

.bk-card {
    border-radius: 18px;
    padding: 16px;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    box-shadow: 0 8px 20px rgba(15,23,42,0.12);
}

.bk-status-pill {
    font-size: 11px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 999px;
}

.bk-status-pending { background:#fef9c3; color:#854d0e; }
.bk-status-completed { background:#dcfce7; color:#166534; }
.bk-status-cancelled { background:#fee2e2; color:#b91c1c; }

.bk-footer {
    display:flex;
    justify-content:space-between;
    gap:10px;
}

.btn-pay {
    background: linear-gradient(135deg,#22c55e,#16a34a);
    color:#fff;
    border:none;
    border-radius:999px;
    padding:6px 14px;
}

.btn-cancel {
    background:#ef4444;
    color:#fff;
    border:none;
    border-radius:999px;
    padding:6px 14px;
}
</style>

<div class="bk-page">

   <h2 class="text-2xl font-extrabold mb-1">My Bookings</h2>
<p class="text-slate-600 mb-4">
    View and manage all your service bookings. You can make payments, track booking status,
    or cancel upcoming bookings if needed.
</p>

<div class="bg-white border border-slate-200 rounded-xl p-4 mb-5 text-sm text-slate-700">
    <div class="flex flex-wrap gap-4">
        <div>
            <span class="bk-status-pill bk-status-pending">Pending</span>
            <span class="ml-2">Booking created but not yet completed.</span>
        </div>
        <div>
            <span class="bk-status-pill bk-status-completed">Completed</span>
            <span class="ml-2">Service has been completed successfully.</span>
        </div>
        <div>
            <span class="bk-status-pill bk-status-cancelled">Cancelled</span>
            <span class="ml-2">Booking was cancelled and will not be processed.</span>
        </div>
    </div>
</div>


    @if ($bookings->isEmpty())
       <div class="text-center py-12 bg-white rounded-xl border border-slate-200">
    <i class="bi bi-calendar-x text-4xl text-sky-500"></i>
    <h5 class="mt-3 font-bold text-lg">No bookings yet</h5>
    <p class="text-slate-500 mt-1">
        You haven’t made any service bookings. Browse our services to get started.
    </p>
</div>

    @else
        @php $now = Carbon::now(); @endphp

        <div class="bk-grid">
            @foreach ($bookings as $booking)
                @php
                    $bookingDateTime = Carbon::parse($booking->BookingDate.' '.$booking->BookingTime);
                    $canCancel = $booking->BookingStatus==='Pending'
                                 && $now->diffInMinutes($bookingDateTime,false)>=30;
                    $payment = $payments->get($booking->BookingID);
                    $paymentStatus = $payment->PaymentStatus ?? null;
                @endphp

                <div class="bk-card">

                    <div class="flex justify-between mb-2">
                        <strong>{{ $booking->name }}</strong>
                        <span class="bk-status-pill bk-status-{{ strtolower($booking->BookingStatus) }}">
                            {{ $booking->BookingStatus }}
                        </span>
                    </div>

                    <p><strong>Date:</strong> {{ Carbon::parse($booking->BookingDate)->format('d M Y') }}</p>
                    <p><strong>Time:</strong> {{ Carbon::parse($booking->BookingTime)->format('h:i A') }}</p>
                    <p class="text-sm">{{ $booking->description }}</p>

                    <p class="mt-2 font-semibold">RM {{ number_format($booking->price,2) }}</p>
                    <p class="text-sm">Plate: {{ $booking->PlateNumber }}</p>
                    <p class="text-xs text-slate-500 mb-2">
    Actions available depend on your booking status and payment progress.
</p>


                    <div class="bk-footer mt-3">

                        {{-- PAYMENT STATUS --}}
                        <div>
                            @if($booking->BookingStatus === 'Cancelled')
                                <strong class="text-red-600">Cancelled</strong>

                            @elseif($payment && $paymentStatus === 'Paid')
                                <strong class="text-green-600">
                                    <i class="bi bi-check-circle"></i> Paid
                                </strong>

                            @elseif($payment && $paymentStatus === 'Awaiting Cash Payment')
                                <strong style="color:#b45309;">
                                    <i class="bi bi-hourglass-split"></i>
                                    Awaiting cash payment
                                </strong>

                            @elseif(!$payment || in_array($paymentStatus,['Rejected','Failed']))
                                <select class="payment-method-select"
                                        data-booking-id="{{ $booking->BookingID }}"
                                        data-stripe-url="{{ route('stripe.booking.form',$booking->BookingID) }}">
                                    <option value="counter">Cash</option>
                                    <option value="onlinebank">FPX</option>
                                    <option value="card">Card</option>
                                </select>
                            @endif
                        </div>

                        {{-- ACTION BUTTONS --}}
                        <div class="flex gap-2">
                            @if(
                                $booking->BookingStatus !== 'Cancelled' &&
                                (!$payment || in_array($paymentStatus,['Rejected','Failed']))
                            )
                                <button type="button"
                                        class="btn-pay pay-now-btn"
                                        data-booking-id="{{ $booking->BookingID }}">
                                    Pay Now
                                </button>
                            @endif

                            @if($canCancel)
                                <form id="cancelForm{{ $booking->BookingID }}"
                                      method="POST"
                                      action="{{ route('customer.cancelBooking',$booking->BookingID) }}">
                                    @csrf @method('PUT')
                                    <button type="button"
                                            class="btn-cancel"
                                            onclick="confirmCancel('{{ $booking->BookingID }}')">
                                        Cancel
                                    </button>
                                </form>
                            @endif
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.pay-now-btn').forEach(btn => {
        btn.addEventListener('click', function () {

            const bookingId = this.dataset.bookingId;
            const select = document.querySelector(
                `.payment-method-select[data-booking-id="${bookingId}"]`
            );

            if (!select) {
                Swal.fire('Error','Select payment method','error');
                return;
            }

            const method = select.value;
            const stripeUrl = select.dataset.stripeUrl;

            // CASH
            if (method === 'counter') {
                Swal.fire({
                    title: 'Cash Payment',
                    text: 'This booking will be marked as awaiting cash payment.',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm'
                }).then(res => {
                    if (res.isConfirmed) {
                        fetch("{{ route('booking.markPaid',['booking'=>'__ID__']) }}"
                            .replace('__ID__',bookingId), {
                            method:'POST',
                            headers:{
                                'X-CSRF-TOKEN':'{{ csrf_token() }}',
                                'Content-Type':'application/json'
                            },
                            body: JSON.stringify({method:'counter'})
                        })
                        .then(r=>r.json())
                        .then(()=>location.reload());
                    }
                });
            }

            // FPX
            else if (method === 'onlinebank') {
                const form = document.createElement('form');
                form.method='POST';
                form.action="{{ route('toyyibpay.create',['bookingId'=>'__ID__']) }}"
                            .replace('__ID__',bookingId);
                form.innerHTML=`<input type="hidden" name="_token" value="{{ csrf_token() }}">`;
                document.body.appendChild(form);
                form.submit();
            }

            // CARD
            else if (method === 'card') {
                window.location.href = stripeUrl;
            }

        });
    });

    window.confirmCancel = function(id) {
        Swal.fire({
            title:'Cancel booking?',
            icon:'warning',
            showCancelButton:true,
            confirmButtonColor:'#e11d48'
        }).then(res=>{
            if(res.isConfirmed){
                document.getElementById('cancelForm'+id).submit();
            }
        });
    };
});
</script>

@endsection
