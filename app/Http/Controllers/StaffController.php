<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Booking;
use App\Models\Product;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\StaffAccountCreated;


class StaffController extends Controller
{
    /* ============================================================
       OWNER — STAFF MANAGEMENT
    ============================================================ */

    public function index()
    {
        $staff = User::where('UserRole', 'Staff')->get();
        return view('admin.staff.index', compact('staff'));
    }

    public function create()
    {
        return view('admin.staff.create');
    }

    public function deactivate($id)
    {
        User::where('UserID', $id)->update(['Status' => 'Inactive']);
        return back()->with('success', 'Staff deactivated.');
    }

    public function activate($id)
    {
        User::where('UserID', $id)->update(['Status' => 'Active']);
        return back()->with('success', 'Staff activated.');
    }

   public function store(Request $request)
{
    $request->validate([
        'FullName'     => 'required|string|max:255',
        'PhoneNumber'  => 'required|string|max:20',
        'Email'        => 'required|email|max:255|unique:users,Email',
        'UserName'     => 'required|string|max:100|unique:users,UserName',
        'UserPassword' => 'required|string|min:6',
        'Address'      => 'nullable|string|max:255',
        'Image'        => 'nullable|image|max:2048',
    ]);

    $fileName = null;

    if ($request->hasFile('Image')) {
        $file = $request->file('Image');
        $fileName = time().'_'.$file->getClientOriginalName();
        $file->storeAs('staff', $fileName, 'public');
    }

    // ✅ 1. CREATE STAFF USER
    $staff = User::create([
        'FullName'          => $request->FullName,
        'PhoneNumber'       => $request->PhoneNumber,
        'Email'             => $request->Email,
        'Address'           => $request->Address,
        'UserName'          => $request->UserName,
        'UserPassword'      => bcrypt($request->UserPassword),
        'UserRole'          => 'Staff',
        'Image'             => $fileName,
        'Status'            => 'Active',
        'email_verified_at' => now(),
    ]);

    // ✅ 2. SEND EMAIL TO STAFF
    Mail::to($staff->Email)->send(
        new StaffAccountCreated(
            $staff->UserName,
            $request->UserPassword // plain password ONLY for email
        )
    );

    return redirect()
        ->route('staff.index')
        ->with('success', 'Staff created successfully and email sent.');
}

    public function edit($id)
    {
        $staff = User::findOrFail($id);
        return view('admin.staff.edit', compact('staff'));
    }

    public function update(Request $request, $id)
    {
        $staff = User::findOrFail($id);

        $request->validate([
            'FullName'    => 'required|string|max:255',
            'PhoneNumber' => 'required|string|max:20',
            'Email'       => 'required|email|unique:users,Email,' . $staff->UserID . ',UserID',
            'UserName'    => 'required|string|max:100|unique:users,UserName,' . $staff->UserID . ',UserID',
            'Address'     => 'nullable|string|max:255',
            'UserRole'    => 'required|string',
            'Image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('Image')) {

            if ($staff->Image && Storage::disk('public')->exists('staff/' . $staff->Image)) {
                Storage::disk('public')->delete('staff/' . $staff->Image);
            }

            $imageName = time().'_'.$request->file('Image')->getClientOriginalName();
            $request->file('Image')->storeAs('staff', $imageName, 'public');
            $staff->Image = $imageName;
        }

        if ($request->UserPassword) {
            $staff->UserPassword = bcrypt($request->UserPassword);
        }

        $staff->FullName    = $request->FullName;
        $staff->PhoneNumber = $request->PhoneNumber;
        $staff->Email       = $request->Email;
        $staff->Address     = $request->Address;
        $staff->UserName    = $request->UserName;
        $staff->UserRole    = $request->UserRole;
        $staff->save();

        return redirect()->route('staff.index')->with('success', 'Staff updated successfully!');
    }

    /* ============================================================
       STAFF DASHBOARD
    ============================================================ */

    public function dashboard()
{
    $today = now()->toDateString();

    // Today's stats
    $todayBookings     = Booking::where('BookingDate', $today)->count();
    $pendingBookings   = Booking::where('BookingStatus', 'Pending')->count();
    $completedBookings = Booking::where('BookingStatus', 'Completed')->count();
    $cancelledToday    = Booking::where('BookingDate', $today)
                                ->where('BookingStatus', 'Cancelled')
                                ->count();

    // Upcoming bookings for today (sorted by time)
    $upcomingBookings = Booking::with(['customer', 'service'])
        ->where('BookingDate', $today)
        ->where('BookingStatus', 'Pending')
        ->orderByRaw("STR_TO_DATE(REPLACE(BookingTime, '.', ':'), '%H:%i')")
        ->take(5)
        ->get();

    // Full queue for today
    $todayQueue = Booking::with(['customer', 'service'])
        ->where('BookingDate', $today)
        ->orderByRaw("STR_TO_DATE(REPLACE(BookingTime, '.', ':'), '%H:%i')")
        ->get();

    // Recently completed today
    $recentCompleted = Booking::with(['customer', 'service'])
        ->where('BookingDate', $today)
        ->where('BookingStatus', 'Completed')
        ->orderByRaw("STR_TO_DATE(REPLACE(BookingTime, '.', ':'), '%H:%i') DESC")
        ->take(5)
        ->get();

    // Recent activity (last 5 bookings overall, more professional fields)
    $recentBookings = Booking::with(['customer', 'service'])
        ->orderBy('BookingID', 'desc')
        ->take(5)
        ->get();

    $recentActivity = [];

    foreach ($recentBookings as $b) {
        $recentActivity[] = [
            'customer' => $b->customer->CustomerName ?? 'Unknown Customer',
            'service'  => $b->service->name ?? 'Unknown Service',
            'plate'    => $b->PlateNumber ?? 'N/A',
            'status'   => $b->BookingStatus ?? 'Unknown',
            'time'     => $b->BookingDate && $b->BookingTime
                            ? Carbon::parse($b->BookingDate.' '.$b->BookingTime)->format('d M Y • h:i A')
                            : 'No time',
        ];
    }

    return view('staff.dashboard', compact(
        'todayBookings',
        'pendingBookings',
        'completedBookings',
        'cancelledToday',
        'upcomingBookings',
        'todayQueue',
        'recentCompleted',
        'recentActivity'
    ));
}

    /* ============================================================
       STAFF BOOKINGS
    ============================================================ */

public function bookings(Request $request)
{
    $filter       = $request->query('filter', 'all');
    $customerName = $request->query('customer_name');
    $today        = now()->toDateString();

    // ---- TOP STATS ----
    $totalBookings      = DB::table('booking')->count();
    $todayBookingsCount = DB::table('booking')
                            ->where('BookingDate', $today)
                            ->count();
    $pendingCount       = DB::table('booking')
                            ->where('BookingStatus', 'Pending')
                            ->count();
    $completedCount     = DB::table('booking')
                            ->where('BookingStatus', 'Completed')
                            ->count();
    $cancelledCount     = DB::table('booking')
                            ->where('BookingStatus', 'Cancelled')
                            ->count();

    // ---- MAIN LIST QUERY (ALL / FILTERED) ----
    $query = DB::table('booking')
        ->leftJoin('customer', 'booking.CustomerID', '=', 'customer.CustomerID')
        ->leftJoin('services', 'booking.id', '=', 'services.id')
        ->leftJoin('payments', 'payments.BookingID', '=', 'booking.BookingID')
        ->select(
            'booking.*',
            'customer.CustomerName',
            'customer.CustomerPhone',
            'customer.CustomerEmail',
            'services.name as ServiceName',
            'services.description as ServiceDescription',
            'services.price as ServicePrice',
            'payments.PaymentStatus as payment_status',
            'payments.PaymentMethod as payment_method',
            'payments.Amount as payment_amount'
        );

    // Status / date filter for main table
    if ($filter === 'pending') {
        $query->where('booking.BookingStatus', 'Pending');
    } elseif ($filter === 'completed') {
        $query->where('booking.BookingStatus', 'Completed');
    } elseif ($filter === 'cancelled') {
        $query->where('booking.BookingStatus', 'Cancelled');
    } elseif ($filter === 'today') {
        $query->where('booking.BookingDate', $today);
    }

    // Filter by customer name
    if (!empty($customerName)) {
        $query->where('customer.CustomerName', 'LIKE', "%{$customerName}%");
    }

    $bookings = $query
        ->orderBy('booking.BookingDate', 'desc')
        ->orderBy('booking.BookingTime', 'desc')
        ->get();

    // ---- TODAY'S BOOKINGS STRIP DATA ----
    $todayBookings = DB::table('booking')
        ->leftJoin('customer', 'booking.CustomerID', '=', 'customer.CustomerID')
        ->leftJoin('services', 'booking.id', '=', 'services.id')
        ->leftJoin('payments', 'payments.BookingID', '=', 'booking.BookingID')
        ->select(
            'booking.*',
            'customer.CustomerName',
            'customer.CustomerPhone',
            'customer.CustomerEmail',
            'services.name as ServiceName',
            'services.description as ServiceDescription',
            'services.price as ServicePrice',
            'payments.PaymentStatus as payment_status',
            'payments.PaymentMethod as payment_method',
            'payments.Amount as payment_amount'
        )
        ->where('booking.BookingDate', $today)
        ->orderByRaw("STR_TO_DATE(REPLACE(booking.BookingTime, '.', ':'), '%H:%i')")
        ->get();

    return view('staff.bookings', compact(
        'bookings',
        'filter',
        'customerName',
        'totalBookings',
        'todayBookingsCount',
        'pendingCount',
        'completedCount',
        'cancelledCount',
        'todayBookings'
    ));
}

public function updateBookingStatus($id, $status)
{
    if (!in_array($status, ['Pending','Completed','Cancelled'])) {
        abort(400, 'Invalid status');
    }

    DB::table('booking')
        ->where('BookingID', $id)
        ->update([
            'BookingStatus' => $status
        ]);

    return back()->with('success', "Booking updated to {$status}.");
}

    /* ============================================================
       STAFF PAYMENTS
    ============================================================ */

   public function payments(Request $request)
    {
        // filter values used in URL: pending | verified | failed | today | all
        $filter = $request->query('filter', 'pending');
        $today  = Carbon::now('Asia/Kuala_Lumpur')->toDateString();

        $query = DB::table('payments')
            ->leftJoin('booking', 'payments.BookingID', '=', 'booking.BookingID')
            ->leftJoin('customer', 'payments.CustomerID', '=', 'customer.CustomerID')
            ->select(
                'payments.*',
                'booking.BookingDate',
                'booking.BookingTime',
                'customer.CustomerName'
            );

        // Map filter -> actual DB values (ENUM)
        if ($filter === 'pending') {
            // Anything not yet confirmed
            $query->whereIn('payments.PaymentStatus', ['Unpaid', 'Awaiting Cash Payment']);
        } elseif ($filter === 'verified') {
            // Verified = Paid
            $query->where('payments.PaymentStatus', 'Paid');
        } elseif ($filter === 'failed') {
            $query->where('payments.PaymentStatus', 'Failed');
        } elseif ($filter === 'today') {
            $query->whereDate('payments.created_at', $today);
        }
        // filter === 'all' → no extra where

        $payments = $query
            ->orderBy('payments.created_at', 'desc')
            ->get();

        // ---------- TODAY SUMMARY (for the cards) ----------
        $todayTotal = DB::table('payments')
            ->whereDate('created_at', $today)
            ->sum('Amount');

        $todayPaidCount = DB::table('payments')
            ->whereDate('created_at', $today)
            ->where('PaymentStatus', 'Paid')
            ->count();

        $todayPendingCount = DB::table('payments')
            ->whereDate('created_at', $today)
            ->whereIn('PaymentStatus', ['Unpaid', 'Awaiting Cash Payment'])
            ->count();

        $todayFailedCount = DB::table('payments')
            ->whereDate('created_at', $today)
            ->where('PaymentStatus', 'Failed')
            ->count();

        return view('staff.payments', compact(
            'payments',
            'filter',
            'today',
            'todayTotal',
            'todayPaidCount',
            'todayPendingCount',
            'todayFailedCount'
        ));
    }

    public function updatePaymentStatus($id, $status)
    {
        /**
         * Status from URL:
         *  - "Verified" → PaymentStatus = "Paid"
         *  - "Rejected" → PaymentStatus = "Failed"
         *  - "AwaitingCash" (optional) → "Awaiting Cash Payment"
         */

        if ($status === 'Verified') {
            $dbStatus = 'Paid';
        } elseif ($status === 'Rejected') {
            $dbStatus = 'Failed';
        } elseif ($status === 'AwaitingCash') {
            $dbStatus = 'Awaiting Cash Payment';
        } else {
            abort(400, 'Invalid payment status');
        }

        DB::table('payments')
            ->where('PaymentID', $id)
            ->update([
                'PaymentStatus' => $dbStatus,
                'updated_at'    => Carbon::now('Asia/Kuala_Lumpur'),
            ]);

        return back()->with('success', "Payment updated to {$dbStatus}.");
    }

    /* ============================================================
       STAFF PRODUCTS (READ ONLY)
    ============================================================ */

    public function products()
    {
        $products = Product::orderBy('Category')->orderBy('ProductName')->get();
        return view('staff.products', compact('products'));
    }

    /* ============================================================
       STAFF PROFILE
    ============================================================ */

    public function profile()
    {
        return view('staff.profile', ['user' => Auth::user()]);
    }

    public function editProfile()
    {
        return view('staff.editProfile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'FullName'    => 'required|string|max:255',
        'PhoneNumber' => 'required|string|max:20',
        'Email'       => 'required|email|max:255|unique:users,Email,' . $user->UserID . ',UserID',
        'UserName'    => 'required|string|max:100|unique:users,UserName,' . $user->UserID . ',UserID',
        'Address'     => 'nullable|string|max:255',
        'Image'       => 'nullable|image|max:2048',
    ]);

    // ----- SAVE PROFILE IMAGE -----
    if ($request->hasFile('Image')) {

        // Delete old image
        if ($user->Image && Storage::disk('public')->exists('staff/' . $user->Image)) {
            Storage::disk('public')->delete('staff/' . $user->Image);
        }

        $imageName = time().'_'.$request->file('Image')->getClientOriginalName();
        $request->file('Image')->storeAs('staff', $imageName, 'public');
        $user->Image = $imageName;
    }

    // ----- UPDATE OTHER FIELDS -----
    $user->update([
        'FullName'    => $request->FullName,
        'PhoneNumber' => $request->PhoneNumber,
        'Email'       => $request->Email,
        'UserName'    => $request->UserName,
        'Address'     => $request->Address,
    ]);

    return redirect()->route('staff.profile')->with('success','Profile updated successfully!');
}

}
