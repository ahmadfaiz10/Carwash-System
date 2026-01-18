@extends('layouts.customer')

@section('title', 'My Payments')

@section('content')

<style>
body{
    background:#f8fafc !important;
    font-family:Poppins, sans-serif;
}

/* ================= WRAPPER ================= */
.payment-wrapper{
    width:100%;
    padding:24px 24px 60px;
}

/* ================= HEADER ================= */
.page-header h2{
    font-size:26px;
    font-weight:800;
    color:#0f172a;
}
.page-header p{
    font-size:14px;
    color:#64748b;
    margin-top:4px;
}

/* ================= SUMMARY ================= */
.summary-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:18px;
    margin-top:24px;
}

.summary-card{
    background:#ffffff;
    border-radius:18px;
    padding:18px;
    box-shadow:0 10px 30px rgba(15,23,42,.1);
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.summary-card h4{
    font-size:14px;
    color:#64748b;
}
.summary-card strong{
    font-size:22px;
    color:#0f172a;
}

.summary-icon{
    width:44px;
    height:44px;
    border-radius:14px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:20px;
    color:#fff;
}

.bg-paid{ background:#22c55e; }
.bg-pending{ background:#f59e0b; }
.bg-cash{ background:#0ea5e9; }

/* ================= TABLE ================= */
.table-card{
    background:#ffffff;
    border-radius:20px;
    padding:22px;
    margin-top:30px;
    box-shadow:0 14px 40px rgba(15,23,42,.12);
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse:collapse;
    font-size:14px;
}

thead{
    background:#e0f2fe;
}

th, td{
    padding:14px;
    text-align:left;
    white-space:nowrap;
}

th{
    font-weight:800;
    color:#0369a1;
}

tbody tr{
    border-bottom:1px solid #e5e7eb;
}

tbody tr:hover{
    background:#f1f5f9;
}

/* ================= BADGES ================= */
.badge{
    padding:5px 14px;
    border-radius:999px;
    font-size:11px;
    font-weight:800;
}

.badge-paid{
    background:#dcfce7;
    color:#166534;
}
.badge-awaiting{
    background:#e0f2fe;
    color:#0369a1;
}
.badge-pending{
    background:#fef3c7;
    color:#92400e;
}
.badge-failed{
    background:#fee2e2;
    color:#b91c1c;
}

/* ================= EMPTY ================= */
.empty-box{
    background:#ffffff;
    border-radius:20px;
    padding:48px;
    margin-top:40px;
    text-align:center;
    color:#64748b;
    box-shadow:0 14px 40px rgba(15,23,42,.1);
}
</style>

<div class="payment-wrapper">

    {{-- HEADER --}}
    <div class="page-header">
        <h2>💳 My Payments</h2>
        <p>View and track all your payment transactions securely.</p>
    </div>

    {{-- SUMMARY --}}
    <div class="summary-grid">
        <div class="summary-card">
            <div>
                <h4>Total Paid</h4>
                <strong>
                    RM {{ number_format($payments->whereIn('PaymentStatus',['Paid','Verified'])->sum('Amount'),2) }}
                </strong>
            </div>
            <div class="summary-icon bg-paid">✔</div>
        </div>

        <div class="summary-card">
            <div>
                <h4>Pending</h4>
                <strong>
                    RM {{ number_format($payments->where('PaymentStatus','Pending')->sum('Amount'),2) }}
                </strong>
            </div>
            <div class="summary-icon bg-pending">⏳</div>
        </div>

        <div class="summary-card">
            <div>
                <h4>Cash Payments</h4>
                <strong>
                    RM {{ number_format($payments->where('PaymentMethod','Cash')->sum('Amount'),2) }}
                </strong>
            </div>
            <div class="summary-icon bg-cash">💵</div>
        </div>
    </div>

    {{-- EMPTY --}}
    @if($payments->isEmpty())
        <div class="empty-box">
            <h3>No payments yet</h3>
            <p>Your payment history will appear here once a transaction is made.</p>
        </div>
    @else

    {{-- TABLE --}}
    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Method</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Reference</th>
                    <th>Purpose</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $p)
                <tr>
                    <td>{{ ucfirst($p->PaymentMethod) }}</td>

                    <td><strong>RM {{ number_format($p->Amount,2) }}</strong></td>

                    <td>
                        @if(in_array($p->PaymentStatus,['Paid','Verified']))
                            <span class="badge badge-paid">Paid</span>
                        @elseif($p->PaymentStatus=='Awaiting Cash Payment')
                            <span class="badge badge-awaiting">Awaiting Cash</span>
                        @elseif($p->PaymentStatus=='Pending')
                            <span class="badge badge-pending">Pending</span>
                        @else
                            <span class="badge badge-failed">{{ $p->PaymentStatus }}</span>
                        @endif
                    </td>

                    <td>{{ $p->ReferenceNumber ?? '-' }}</td>

                    <td>
                        @if(!empty($p->ServiceName))
                            Service: {{ $p->ServiceName }}
                        @elseif(!empty($p->ProductName))
                            Product: {{ $p->ProductName }}
                        @else
                            -
                        @endif
                    </td>

                    <td>
                        {{ $p->created_at ? \Carbon\Carbon::parse($p->created_at)->format('d M Y, h:i A') : '-' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @endif

</div>

@endsection
