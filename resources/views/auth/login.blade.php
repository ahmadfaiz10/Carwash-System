<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - AutoShineX Carwash System</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    height: 100vh;
    overflow: hidden;
    background: #f1f9ff;
}

/* ===== SYSTEM STATUS ===== */
.system-status {
    position: absolute;
    top: 25px;
    right: 35px;
    background: rgba(255,255,255,0.9);
    padding: 10px 18px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    color: #0f172a;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    z-index: 10;
}
.system-status span {
    color: #16a34a;
}

/* ===== FULL SCREEN GRID ===== */
.screen {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    height: 100%;
}

/* ===== LEFT BRAND AREA (IMAGE BACKGROUND) ===== */
.brand-area {
    padding: 80px;
    background:
        linear-gradient(
            rgba(10, 70, 120, 0.75),
            rgba(5, 40, 80, 0.85)
        ),
        url("{{ asset('images/login.png') }}");
    background-size: cover;
    background-position: center;
    color: #ffffff;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.brand-area h1 {
    font-size: 54px;
    font-weight: 900;
    margin-bottom: 20px;
}

.brand-area p {
    font-size: 18px;
    max-width: 520px;
    line-height: 1.7;
    margin-bottom: 40px;
}

/* ===== BRAND STATS ===== */
.brand-stats {
    display: flex;
    gap: 35px;
}
.stat span {
    display: block;
    font-size: 28px;
    font-weight: 800;
}

/* ===== RIGHT LOGIN AREA ===== */
.login-area {
    background: #f8fafc;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* ===== LOGIN PANEL ===== */
.login-panel {
    width: 420px;
    background: #ffffff;
    padding: 45px 40px;
    border-radius: 18px;
    box-shadow: 0 30px 60px rgba(0,0,0,0.18);
}

/* ===== LOGO ===== */
.logo {
    text-align: center;
    margin-bottom: 12px;
}
.logo img {
    width: 80px;
}

.login-title {
    text-align: center;
    font-size: 24px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 25px;
}

/* ===== INPUTS ===== */
.label {
    font-size: 14px;
    font-weight: 600;
    color: #334155;
    margin-bottom: 6px;
    display: block;
}

.input-group {
    margin-bottom: 18px;
}

.input-field {
    width: 100%;
    padding: 14px 15px;
    border-radius: 10px;
    border: 1px solid #cbd5e1;
    background: #f8fafc;
    font-size: 14px;
}

.input-field:focus {
    outline: none;
    border-color: #0ea5e9;
    box-shadow: 0 0 0 3px rgba(14,165,233,0.25);
}

/* ===== BUTTON ===== */
.login-btn {
    width: 100%;
    padding: 15px;
    margin-top: 10px;
    border-radius: 10px;
    border: none;
    background: linear-gradient(135deg, #0ea5e9, #0284c7);
    color: #ffffff;
    font-weight: 700;
    font-size: 15px;
    cursor: pointer;
}
.login-btn:hover {
    box-shadow: 0 15px 30px rgba(14,165,233,0.45);
}

/* ===== MESSAGES ===== */
.error-message {
    color: #dc2626;
    margin-bottom: 10px;
    font-size: 14px;
}
.success-message {
    color: #16a34a;
    margin-bottom: 10px;
    font-size: 14px;
}

/* ===== SECURITY NOTE ===== */
.security-note {
    margin-top: 18px;
    font-size: 13px;
    color: #64748b;
    text-align: center;
}
.security-note span {
    color: #0ea5e9;
    font-weight: 600;
}

/* ===== LINKS ===== */
.login-panel p {
    text-align: center;
    margin-top: 14px;
    font-size: 14px;
}
.login-panel a {
    color: #0284c7;
    font-weight: 600;
    text-decoration: none;
}
.login-panel a:hover {
    text-decoration: underline;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 900px) {
    .screen {
        grid-template-columns: 1fr;
    }
    .brand-area {
        display: none;
    }
}
</style>
</head>

<body>



<div class="screen">

    <!-- LEFT BRAND (IMAGE BACKGROUND) -->
    <div class="brand-area">
        <h1>AutoShine</h1>
        <p>
            Enterprise carwash management system built for
            high efficiency, secure operations, and professional service control.
        </p>

        <div class="brand-stats">
            <div class="stat"><span>24/7</span>Access</div>
            <div class="stat"><span>100%</span>Secure</div>
            <div class="stat"><span>Fast</span>Service</div>
        </div>
    </div>

    <!-- RIGHT LOGIN -->
    <div class="login-area">
        <div class="login-panel">

            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
            </div>

            <h2 class="login-title">System Login</h2>

            {{-- ERROR --}}
            @if ($errors->any())
                <div class="error-message">{{ $errors->first() }}</div>
            @endif

            {{-- SUCCESS --}}
            @if (session('success'))
                <div class="success-message">{{ session('success') }}</div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST">
                @csrf

                <label class="label">Username</label>
                <div class="input-group">
                    <input type="text" name="UserName" class="input-field" required>
                </div>

                <label class="label">Password</label>
                <div class="input-group">
                    <input type="password" name="UserPassword" class="input-field" required>
                </div>

                <button class="login-btn" type="submit">Login</button>

                <p><a href="{{ route('forgot') }}">Forgot Password?</a></p>
                <p>New user? <a href="{{ route('register') }}">Create account</a></p>
            </form>

            <div class="security-note">
                🔒 Protected by <span>AutoShine Secure Authentication</span>
            </div>

        </div>
    </div>

</div>

</body>
</html>
