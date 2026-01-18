@extends('layouts.staff')

@section('title', 'Staff - Manage Payments')
@section('page_title', 'Payments')
@section('page_subtitle', 'Verify and track customer payments')

@section('content')

@php
    // fallback if not passed for some reason
    $today = $today ?? \Carbon\Carbon::now('Asia/Kuala_Lumpur')->toDateString();
@endphp

<style>
    .payments-stat-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 18px;
    }
    @media(max-width:900px){
        .payments-stat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    @media(max-width:550px){
        .payments-stat-grid {
            grid-template-columns: 1fr;
        }
    }
    .pay-stat-card {
        border-radius: 18px;
        padding: 14px 16px;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 8px 20px rgba(15,23,42,0.08);
        border: 1px solid rgba(148,163,184,0.4);
        text-decoration: none;
    }
    .pay-stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: white;
    }
    .pay-stat-body h4 {
        margin: 0;
        font-size: 13px;
        font-weight: 600;
        color: #475569;
    }
    .pay-stat-body span {
        font-size: 20px;
        display: block;
        font-weight: 700;
    }

    .icon-blue { background: linear-gradient(135deg,#1D4ED8,#38BDF8); }
    .icon-green { background: linear-gradient(135deg,#16A34A,#4ADE80); }
    .icon-amber { background: linear-gradient(135deg,#F59E0B,#FACC15); }
    .icon-red { background: linear-gradient(135deg,#DC2626,#FB7185); }

    .pay-filter-bar {
        margin-bottom: 14px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        justify-content: space-between;
    }
    .pay-filter-left {
        display:flex;
        flex-wrap:wrap;
        align-items:center;
        gap:6px;
        font-size:13px;
        color:#6b7280;
    }
    .filter-pill {
        font-size: 12px;
        padding: 5px 11px;
        border-radius: 999px;
        border: 1px solid #e5e7eb;
        background: white;
        color: #4b5563;
        text-decoration: none;
        font-weight: 600;
    }
    .filter-pill.active {
        background: linear-gradient(135deg,#2563EB,#1D4ED8);
        color: white;
        border-color: transparent;
    }

    .payments-table-wrapper {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 8px 24px rgba(15,23,42,0.07);
        overflow: hidden;
    }
    .payments-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    .payments-table thead {
        background: #0f172a;
        color: #e5e7eb;
    }
    .payments-table th,
    .payments-table td {
        padding: 9px 10px;
        text-align: left;
    }
    .payments-table tbody tr:nth-child(even) {
        background: #f9fafb;
    }

    .status-badge {
        padding: 3px 9px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
    }
    .status-unpaid    { background:#fee2e2; color:#b91c1c; }
    .status-awaiting  { background:#fef9c3; color:#92400e; }
    .status-paid      { background:#dcfce7; color:#166534; }
    .status-failed    { background:#fee2e2; color:#b91c1c; }

    .pay-action-btn {
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 999px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        margin-right: 4px;
    }
    .btn-verify {
        background: #22c55e;
        color: #ecfdf5;
    }
    .btn-reject {
        background: #ef4444;
        color: #fee2e2;
    }
    .pay-no-action {
        font-size: 11px;
        color: #9ca3af;
    }
</style>

{{-- ====== TOP SUMMARY CARDS ====== --}}
<div class="payments-stat-grid">

    {{-- Today Revenue – clickable (today filter) --}}
    <a href="{{ route('staff.payments', ['filter' => 'today']) }}" class="pay-stat-card">
        <div class="pay-stat-icon icon-blue">
            <i class="bi bi-currency-dollar"></i>
        </div>
        <div class="pay-stat-body">
            <h4>Today’s Revenue</h4>
            <span>RM {{ number_format($todayTotal ?? 0, 2) }}</span>
            <small style="font-size:11px; color:#6b7280;">
                {{ $today }}
            </small>
        </div>
    </a>

    {{-- Today Paid count --}}
    <div class="pay-stat-card">
        <div class="pay-stat-icon icon-green">
            <i class="bi bi-check-circle"></i>
        </div>
        <div class="pay-stat-body">
            <h4>Paid Today</h4>
            <span>{{ $todayPaidCount ?? 0 }}</span>
            <small style="font-size:11px; color:#6b7280;">Confirmed payments</small>
        </div>
    </div>

    {{-- Today Pending count --}}
    <div class="pay-stat-card">
        <div class="pay-stat-icon icon-amber">
            <i class="bi bi-hourglass-split"></i>
        </div>
        <div class="pay-stat-body">
            <h4>Pending Today</h4>
            <span>{{ $todayPendingCount ?? 0 }}</span>
            <small style="font-size:11px; color:#6b7280;">Unpaid / cash waiting</small>
        </div>
    </div>

    {{-- Today Failed count --}}
    <div class="pay-stat-card">
        <div class="pay-stat-icon icon-red">
            <i class="bi bi-x-circle"></i>
        </div>
        <div class="pay-stat-body">
            <h4>Failed Today</h4>
            <span>{{ $todayFailedCount ?? 0 }}</span>
            <small style="font-size:11px; color:#6b7280;">Failed or rejected</small>
        </div>
    </div>

</div>

{{-- ====== FILTER BAR ====== --}}
<div class="pay-filter-bar">
    <div class="pay-filter-left">
        <span>View:</span>
        <a href="{{ route('staff.payments', ['filter' => 'today']) }}"
           class="filter-pill {{ $filter === 'today' ? 'active' : '' }}">
            Today
        </a>
        <a href="{{ route('staff.payments', ['filter' => 'pending']) }}"
           class="filter-pill {{ $filter === 'pending' ? 'active' : '' }}">
            Pending
        </a>
        <a href="{{ route('staff.payments', ['filter' => 'verified']) }}"
           class="filter-pill {{ $filter === 'verified' ? 'active' : '' }}">
            Verified (Paid)
        </a>
        <a href="{{ route('staff.payments', ['filter' => 'failed']) }}"
           class="filter-pill {{ $filter === 'failed' ? 'active' : '' }}">
            Failed
        </a>
        <a href="{{ route('staff.payments', ['filter' => 'all']) }}"
           class="filter-pill {{ $filter === 'all' ? 'active' : '' }}">
            All
        </a>
    </div>
</div>

{{-- ====== TABLE ====== --}}
<div class="payments-table-wrapper">
    <table class="payments-table">
        <thead>
        <tr>
            <th>Payment #</th>
            <th>Customer</th>
            <th>Booking</th>
            <th>Amount (RM)</th>
            <th>Method</th>
            <th>Status</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>
        </thead>

        <tbody>
        @forelse($payments as $payment)

            @php
                $status = $payment->PaymentStatus;
                $badgeClass = 'status-unpaid';

                if ($status === 'Paid') {
                    $badgeClass = 'status-paid';
                } elseif ($status === 'Failed') {
                    $badgeClass = 'status-failed';
                } elseif ($status === 'Awaiting Cash Payment') {
                    $badgeClass = 'status-awaiting';
                }

                // only allow actions for unpaid / awaiting cash
                $isActionable = in_array($status, ['Unpaid', 'Awaiting Cash Payment']);
            @endphp

            <tr>
                <td>{{ $payment->PaymentID }}</td>

                <td>{{ $payment->CustomerName ?? 'N/A' }}</td>

                <td>
                    @if($payment->BookingID)
                        #{{ $payment->BookingID }}
                        @if($payment->BookingDate)
                            <span style="font-size:11px; color:#6b7280;">
                                — {{ $payment->BookingDate }} {{ $payment->BookingTime }}
                            </span>
                        @endif
                    @else
                        -
                    @endif
                </td>

                <td>{{ number_format($payment->Amount, 2) }}</td>
                <td>{{ $payment->PaymentMethod }}</td>

                <td>
                    <span class="status-badge {{ $badgeClass }}">
                        {{ $status }}
                    </span>
                </td>

                <td>
                    {{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y • h:i A') }}
                </td>

                <td>
                    @if($isActionable)
                        {{-- Mark as Paid --}}
                        <form action="{{ route('staff.payments.updateStatus', ['id' => $payment->PaymentID, 'status' => 'Verified']) }}"
                              method="POST"
                              style="display:inline;">
                            @csrf
                            <button type="submit" class="pay-action-btn btn-verify">
                                Mark Paid
                            </button>
                        </form>

                        {{-- Mark as Failed --}}
                        <form action="{{ route('staff.payments.updateStatus', ['id' => $payment->PaymentID, 'status' => 'Rejected']) }}"
                              method="POST"
                              style="display:inline;">
                            @csrf
                            <button type="submit" class="pay-action-btn btn-reject">
                                Mark Failed
                            </button>
                        </form>
                    @else
                        <span class="pay-no-action">No action</span>
                    @endif
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="8" style="padding:14px; text-align:center; color:#9ca3af;">
                    No payments found.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

@endsection
