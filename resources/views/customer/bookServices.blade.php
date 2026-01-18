@extends('layouts.customer')

@section('title', 'Book Service')

@section('content')

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<style>
body {
    background:#f8fafc !important;
    font-family:Poppins,sans-serif;
}

.booking-wrapper {
    width:100%;
    max-width:none;
    margin:0;
    padding:32px 48px 60px;
}


.booking-shell {
    max-width:1600px;
    margin:0 auto;
}

/* ================= HEADER ================= */
.booking-header {
    display:flex;
    justify-content:space-between;
    align-items:flex-end;
    flex-wrap:wrap;
    gap:16px;
    margin-bottom:24px;
}

.booking-title {
    font-size:26px;
    font-weight:800;
    color:#0f172a;
}

.booking-subtitle {
    font-size:14px;
    color:#64748b;
    margin-top:6px;
}

/* ================= STEPS ================= */
.stepper {
    display:flex;
    align-items:center;
    gap:8px;
    font-size:13px;
    font-weight:600;
    color:#64748b;
}

.step {
    width:30px;
    height:30px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    border:2px solid #cbd5e1;
}

.step.active {
    background:#2563eb;
    border-color:#2563eb;
    color:#fff;
}

.step-line {
    width:32px;
    height:2px;
    background:#cbd5e1;
}

/* ================= MAIN GRID ================= */
.booking-grid {
    display:grid;
    grid-template-columns:2fr 1fr;
    gap:26px;
}

/* ================= SERVICE HERO ================= */
.service-hero {
    display:flex;
    gap:16px;
    margin-bottom:20px;
}

.service-img {
    width:120px;
    height:120px;
    border-radius:16px;
    overflow:hidden;
    background:#e2e8f0;
}

.service-img img {
    width:100%;
    height:100%;
    object-fit:cover;
}

.service-info h3 {
    font-size:20px;
    font-weight:800;
}

.service-info p {
    font-size:13px;
    color:#64748b;
}

.service-meta {
    display:flex;
    gap:14px;
    margin-top:8px;
    font-size:13px;
    font-weight:600;
}

.service-meta span {
    background:#e0f2fe;
    color:#0369a1;
    padding:4px 10px;
    border-radius:999px;
}

/* ================= INPUTS ================= */
.section-label {
    font-size:15px;
    font-weight:700;
    margin:16px 0 6px;
}

.input {
    padding:10px 14px;
    border-radius:12px;
    border:1px solid #cbd5e1;
    font-size:14px;
    width:260px;
}

.btn-today {
    padding:10px 16px;
    border-radius:999px;
    background:#e0f2fe;
    border:none;
    color:#0369a1;
    font-weight:600;
    cursor:pointer;
}

/* ================= SLOTS ================= */
.slots-group-title {
    font-weight:700;
    margin:10px 0 6px;
}

.slots-grid {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(120px,1fr));
    gap:10px;
}

.slot {
    border-radius:14px;
    padding:12px;
    text-align:center;
    font-size:14px;
    border:1px solid #cbd5e1;
}

.slot.available {
    background:#ecfdf3;
    border-color:#22c55e;
    color:#166534;
    cursor:pointer;
}

.slot.full {
    background:#fee2e2;
    color:#b91c1c;
}

.slot.selected {
    background:linear-gradient(135deg,#2563eb,#0ea5e9);
    color:#fff;
    border:none;
}

/* ================= SUMMARY ================= */
.summary {
    background:linear-gradient(135deg,#2563eb,#0ea5e9);
    color:#fff;
    border-radius:22px;
    padding:24px;
    position:sticky;
    top:96px;
}

.summary h4 {
    font-size:18px;
    font-weight:700;
}

.summary-row {
    display:flex;
    justify-content:space-between;
    font-size:14px;
    margin-top:10px;
}

.confirm-btn {
    margin-top:20px;
    width:100%;
    padding:12px;
    border-radius:999px;
    border:none;
    background:#f97316;
    font-weight:800;
    color:#fff;
    display:none;
}
.confirm-btn.enabled:hover {
    background:#ea580c;
}

.slot.available small {
    font-weight: 600;
}

.slot.available small:contains("Only 1") {
    color: #f97316;
}

</style>

<div class="booking-wrapper">

<div class="booking-shell">

{{-- HEADER --}}
<div class="booking-header">
    <div>
        <div class="booking-title">Book Service</div>
        <div class="booking-subtitle">Choose date & time to proceed</div>
    </div>

    <div class="stepper">
        <div class="step active">1</div> Date
        <div class="step-line"></div>
        <div class="step active">2</div> Time
        <div class="step-line"></div>
        <div class="step">3</div> Confirm
    </div>
</div>

<div class="booking-grid">

{{-- LEFT --}}
<div>

<div class="service-hero">
    <div class="service-img">
        <img src="{{ $service->image ? asset('storage/'.$service->image) : asset('images/default-service.png') }}">
    </div>
    <div class="service-info">
        <h3>{{ $service->name }}</h3>
        <p>{{ $service->description }}</p>
        <div class="service-meta">
            <span>RM {{ number_format($service->price,2) }}</span>
            <span>{{ $service->duration }} mins</span>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('customer.booking.store') }}" id="bookingForm">
@csrf
<input type="hidden" name="service_id" value="{{ $service->id }}">
<input type="hidden" id="selectedDate" name="BookingDate">
<input type="hidden" id="selectedTime" name="BookingTime">

<div class="section-label">Select Date</div>
<div style="display:flex;gap:10px;flex-wrap:wrap;">
    <input type="date" id="bookingDate" class="input"
        min="{{ now()->format('Y-m-d') }}"
        max="{{ now()->addDays(14)->format('Y-m-d') }}">
    <button type="button" class="btn-today" id="btnToday">Today</button>
</div>

<div class="section-label">Vehicle Plate Number</div>
<input type="text" name="PlateNumber" class="input" placeholder="ABC1234" required>

<div id="slotsContainer" style="margin-top:20px;"></div>

</form>
</div>

{{-- RIGHT --}}
<div class="summary">
    <h4>Booking Summary</h4>

    <div class="summary-row">
        <span>Service</span>
        <span>{{ $service->name }}</span>
    </div>

    <div class="summary-row">
        <span>Date</span>
        <span id="summaryDate">Not selected</span>
    </div>

    <div class="summary-row">
        <span>Time</span>
        <span id="summaryTime">Not selected</span>
    </div>

    <div class="summary-row">
        <span>Location</span>
        <span>AutoShineX</span>
    </div>

    <button id="btnConfirm" class="confirm-btn">Confirm & Book</button>
</div>

</div>
</div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {

    const today = "{{ now()->format('Y-m-d') }}";

    const dateInput    = document.getElementById("bookingDate");
    const summaryDate  = document.getElementById("summaryDate");
    const summaryTime  = document.getElementById("summaryTime");
    const selectedDate = document.getElementById("selectedDate");
    const selectedTime = document.getElementById("selectedTime");
    const slots        = document.getElementById("slotsContainer");
    const btnConfirm   = document.getElementById("btnConfirm");

    // ================= INIT =================
    dateInput.value = today;
    selectedDate.value = today;
    summaryDate.textContent = formatDate(today);
    loadSlots();

    // ================= EVENTS =================
    dateInput.addEventListener("change", () => {
        selectedDate.value = dateInput.value;
        summaryDate.textContent = formatDate(dateInput.value);
        resetSelection();
        loadSlots();
    });

    document.getElementById("btnToday").addEventListener("click", () => {
        dateInput.value = today;
        selectedDate.value = today;
        summaryDate.textContent = formatDate(today);
        resetSelection();
        loadSlots();
    });

   btnConfirm.addEventListener("click", () => {
    if (!selectedTime.value) {
        alert("⚠️ Please select a time slot before confirming.");
        return;
    }
    document.getElementById("bookingForm").submit();
});


    // ================= FUNCTIONS =================
    function resetSelection() {
        selectedTime.value = "";
        summaryTime.textContent = "Not selected";
        btnConfirm.style.display = "none";
        btnConfirm.classList.remove("enabled");
    }

    function loadSlots() {
        slots.innerHTML = "Loading slots...";

        fetch(`{{ route('booking.availableTimes') }}?service_id={{ $service->id }}&date=${dateInput.value}`)
            .then(res => res.json())
            .then(data => {
                slots.innerHTML = "";

                if (!data.times || !data.times.length) {
                    slots.innerHTML = "<p>No slots available</p>";
                    return;
                }

                // ✅ CORRECT NON-OVERLAPPING GROUPS
                const groups = {
                    Morning:   data.times.filter(t => getHour(t.time) < 12),
                    Afternoon: data.times.filter(t => {
                        const h = getHour(t.time);
                        return h >= 12 && h < 17;
                    }),
                    Evening:   data.times.filter(t => getHour(t.time) >= 17)
                };

                Object.keys(groups).forEach(period => {
                    if (!groups[period].length) return;

                    const title = document.createElement("div");
                    title.className = "slots-group-title";
                    title.textContent = period;
                    slots.appendChild(title);

                    const grid = document.createElement("div");
                    grid.className = "slots-grid";

                    groups[period].forEach(slot => {
                        const div = document.createElement("div");
                        div.className = "slot " + (slot.available ? "available" : "full");
                   let label = "Available";
let badge = "";

if (!slot.available) {
    label = "Full";
} else if (slot.remaining === 1) {
    label = "Only 1 slot left";
    badge = "⚠️";
}

div.innerHTML = `
    ${slot.time}<br>
    <small>${badge} ${label}</small>
`;



                        if (slot.available) {
                            div.addEventListener("click", () => {
                                document.querySelectorAll(".slot").forEach(s => s.classList.remove("selected"));
                                div.classList.add("selected");
                                selectedTime.value = slot.time;
                                summaryTime.textContent = slot.time;
                                btnConfirm.style.display = "block";
                                btnConfirm.classList.add("enabled");
                            });
                        }

                        grid.appendChild(div);
                    });

                    slots.appendChild(grid);
                });
            });
    }

    function getHour(time) {
        return parseInt(time.split(":")[0], 10);
    }

    function formatDate(date) {
        const [y, m, d] = date.split("-");
        return `${d}/${m}/${y}`;
    }

});
</script>


@endsection
