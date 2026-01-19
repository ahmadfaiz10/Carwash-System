<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle user login
     */
    public function login(Request $request)
    {
        $request->validate([
            'UserName' => 'required|string',
            'UserPassword' => 'required|string',
        ]);

        $user = User::where('UserName', $request->UserName)->first();

        // If user exists AND password matches
        if ($user && Hash::check($request->UserPassword, $user->UserPassword)) {

            // 🔥 BLOCK LOGIN IF ACCOUNT IS DEACTIVATED
            if ($user->Status === 'Inactive') {
                return back()->withErrors([
                    'error' => 'Your account has been deactivated. Please contact the admin.'
                ]);
            }

            
        // if (!$user->email_verified_at) {
//     return back()->withErrors([
//         'error' => 'Please verify your email before logging in.'
//     ]);
// }

// Login user
            Auth::login($user);

            // Redirect based on role
            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors(['error' => 'Invalid username or password.']);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
{
    session()->flush();                   // Clear all session data
    $request->session()->invalidate();    // Invalidate session
    $request->session()->regenerateToken();

    return redirect('/login');
}


    /**
     * Show registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
       $request->validate([
    'FullName' => 'required|string|max:255',
    'PhoneNumber' => 'required|string|max:20',
    'Email' => 'required|email|max:255|unique:users,Email',
    'UserName' => 'required|string|max:50|unique:users,UserName',
    'UserPassword' => [
        'required',
        'string',
        'min:8',
        'regex:/[a-z]/',
        'regex:/[A-Z]/',
        'regex:/[0-9]/',
        'regex:/[@$!%*#?&]/',
    ],
    'ConfirmPassword' => 'required|string|same:UserPassword',
], [
    'UserPassword.min' => 'Password must be at least 8 characters.',
    'UserPassword.regex' => 'Password must include uppercase, lowercase, number, and special character.',
]);

        // Determine role: owner if password matches
        $role = ($request->UserPassword === 'adminxx123') ? 'Owner' : 'Customer';

        // Create base user
        $user = new User();
        $user->FullName = $request->FullName;
        $user->UserName = $request->UserName;
        $user->UserPassword = Hash::make($request->UserPassword);
        $user->UserRole = $role;
        $user->PhoneNumber = $request->PhoneNumber;
        $user->Email = $request->Email;
        $user->Status = 'Active';   // Ensure new accounts are active
        $token = Str::random(60);
$user->email_verification_token = $token;
$user->email_verified_at = null;
        $user->save();

        Mail::send('emails.verify', ['token' => $token], function ($message) use ($user) {
    $message->from(config('mail.from.address'), config('mail.from.name'));
    $message->to($user->Email);
    $message->subject('Verify Your AutoShineX Account');
});



        // Insert into role-specific table
        if ($role === 'Owner') {
            DB::table('owner')->insert([
                'OwnerName' => $request->FullName,
                'OwnerEmail' => $request->Email,
                'OwnerPhone' => $request->PhoneNumber,
                'OwnerAddress' => $request->Address ?? 'Not provided',
                'UserID' => $user->UserID,
            ]);
        } else {
            DB::table('customer')->insert([
                'CustomerName' => $request->FullName,
                'CustomerEmail' => $request->Email,
                'CustomerPhone' => $request->PhoneNumber,
                'CustomerAddress' => $request->Address ?? 'Not provided',
                'UserID' => $user->UserID,
            ]);
        }

        return redirect()->route('login')->with('success', 'Account registered successfully! Please log in.');
    }

    public function verifyEmail($token)
{
    $user = User::where('email_verification_token', $token)->first();

    if (!$user) {
        return redirect()->route('login')
            ->withErrors(['error' => 'Invalid or expired verification link.']);
    }

    $user->email_verified_at = now();
    $user->email_verification_token = null;
    $user->save();

    return redirect()->route('login')
        ->with('success', 'Email verified successfully! You can now log in.');
}

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot');
    }

    /**
     * Reset password
     */
  public function sendResetLink(Request $request)
{
    $request->validate([
        'Email' => 'required|email|exists:users,Email',
    ]);

    $user = User::where('Email', $request->Email)->first();

    $token = Str::random(60);

    $user->password_reset_token = $token;
    $user->password_reset_expires = now()->addMinutes(30);
    $user->save();

    Mail::send('emails.reset-password', ['token' => $token], function ($message) use ($user) {
        $message->to($user->Email);
        $message->subject('Reset Your AutoShineX Password');
    });

    return back()->with('success', 'Password reset link sent to your email.');
}

public function showResetForm($token)
{
    $user = User::where('password_reset_token', $token)
        ->where('password_reset_expires', '>', now())
        ->first();

    if (!$user) {
        return redirect()->route('login')
            ->withErrors(['error' => 'Reset link is invalid or expired.']);
    }

    return view('auth.reset', compact('token'));
}

public function updatePassword(Request $request)
{
    $request->validate([
        'token' => 'required',
        'NewPassword' => [
            'required',
            'min:8',
            'regex:/[a-z]/',
            'regex:/[A-Z]/',
            'regex:/[0-9]/',
            'regex:/[@$!%*#?&]/',
        ],
        'ConfirmPassword' => 'same:NewPassword',
    ]);

    $user = User::where('password_reset_token', $request->token)
        ->where('password_reset_expires', '>', now())
        ->first();

    if (!$user) {
        return back()->withErrors(['error' => 'Invalid or expired token']);
    }

    $user->UserPassword = Hash::make($request->NewPassword);
    $user->password_reset_token = null;
    $user->password_reset_expires = null;
    $user->save();

    return redirect()->route('login')
        ->with('success', 'Password reset successfully. You may now log in.');
}

    /**
     * Dynamic redirection by role
     */
    public function home()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        return $this->redirectBasedOnRole($user);
    }

    /**
     * Show profile (shared page)
     */
    public function showProfile()
    {
        return view('profile');
    }

    public function editProfile()
    {
        return view('editprofile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'FullName' => 'required|string|max:255',
            'PhoneNumber' => 'required|string|max:20',
            'Email' => 'required|email',
            'UserName' => 'required|string|max:255',
            'Address' => 'nullable|string|max:255',
        ]);

        $user->FullName = $request->FullName;
        $user->PhoneNumber = $request->PhoneNumber;
        $user->Email = $request->Email;
        $user->UserName = $request->UserName;
        $user->Address = $request->Address;
        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }

    /**
     * Redirect users based on role
     */
    private function redirectBasedOnRole($user)
    {
        if ($user->UserRole === 'Owner') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->UserRole === 'Staff') {
            return redirect()->route('staff.dashboard');
        }

        return redirect()->route('customer.dashboard');
    }
}
