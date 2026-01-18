@foreach ($pending as $b)
<div class="queue-card">

    <div class="queue-left">
        <div class="q-name">{{ $b->customer->CustomerName }}</div>
        <div class="q-phone">{{ $b->customer->CustomerPhone }}</div>
        <div class="q-plate"><i class="fa-solid fa-car"></i> {{ $b->PlateNumber }}</div>
    </div>

    <div class="queue-mid">
        <div class="q-service">{{ $b->service->name }}</div>
        <div class="q-desc">{{ $b->service->description }}</div>

        @php
            $ps = $b->payment_status;
            $txt = "No Payment";
            $class = "pill-none";
            if ($ps == "Paid" || $ps == "Verified") { $txt = $ps; $class = "pill-paid"; }
            elseif ($ps == "Awaiting Cash Payment") { $txt = $ps; $class = "pill-await"; }
        @endphp

        <span class="pill pill-pending">Pending</span>
        <span class="pill {{ $class }}">{{ $txt }}</span>
    </div>

    <div class="queue-right">
        <div class="q-date">{{ \Carbon\Carbon::parse($b->BookingDate)->format('D, d M Y') }}</div>
        <div class="q-time">{{ \Carbon\Carbon::parse($b->BookingTime)->format('h:i A') }}</div>
        <div class="q-price">RM{{ number_format($b->service->price,2) }}</div>

        <form action="{{ route('admin.markCompleted', $b->BookingID) }}" method="POST">
            @csrf
            @method('PUT')
            <button class="btn-complete">✔ Mark Completed</button>
        </form>
    </div>

</div>
@endforeach

@if ($pending->isEmpty())
<p class="text-center text-muted">No pending queue right now</p>
@endif
