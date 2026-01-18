<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Account - AutoShineX</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    min-height: 100vh;
    background: linear-gradient(135deg, #eef6ff, #ffffff);
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 50px 20px;
    color: #0f172a;
}

/* ===== PAGE WRAPPER ===== */
.page-wrapper {
    width: 100%;
    max-width: 820px;
}

/* ===== PAGE HEADER ===== */
.page-header {
    text-align: center;
    margin-bottom: 35px;
}

.page-header img {
    width: 70px;
    margin-bottom: 10px;
}

.page-header h1 {
    font-size: 32px;
    font-weight: 800;
    margin-bottom: 6px;
}

.page-header p {
    font-size: 15px;
    color: #475569;
}

/* ===== REGISTER CARD ===== */
.register-card {
    background: #ffffff;
    border-radius: 18px;
    padding: 40px 45px;
    box-shadow: 0 30px 60px rgba(0,0,0,0.12);
}

/* ===== SECTION ===== */
.section {
    margin-bottom: 30px;
}

.section-title {
    font-size: 13px;
    font-weight: 700;
    color: #0284c7;
    letter-spacing: 1px;
    margin-bottom: 15px;
}

/* ===== GRID ===== */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}

/* ===== INPUT ===== */
.input-group {
    position: relative;
}

.input-group i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #64748b;
    font-size: 17px;
}

.input-group input {
    width: 100%;
    padding: 14px 14px 14px 44px;
    border-radius: 12px;
    border: 1px solid #cbd5e1;
    background: #f8fafc;
    font-size: 14px;
}

.input-group input:focus {
    outline: none;
    border-color: #0ea5e9;
    box-shadow: 0 0 0 3px rgba(14,165,233,.25);
}

/* ===== FULL WIDTH INPUT ===== */
.full {
    grid-column: span 2;
}

/* ===== BUTTON ===== */
.btn-register {
    width: 100%;
    padding: 16px;
    border-radius: 14px;
    border: none;
    background: linear-gradient(135deg, #0ea5e9, #0284c7);
    color: #ffffff;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    margin-top: 10px;
}

.btn-register:hover {
    box-shadow: 0 18px 35px rgba(14,165,233,.45);
}

/* ===== MESSAGES ===== */
.error-message {
    color: #dc2626;
    margin-bottom: 18px;
    font-size: 14px;
}
.success-message {
    color: #16a34a;
    margin-bottom: 18px;
    font-size: 14px;
}

/* ===== FOOTER LINKS ===== */
.form-footer {
    margin-top: 25px;
    text-align: center;
    font-size: 14px;
    color: #475569;
}

.form-footer a {
    color: #0284c7;
    font-weight: 600;
    text-decoration: none;
}
.form-footer a:hover {
    text-decoration: underline;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 720px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    .full {
        grid-column: span 1;
    }
}
</style>
</head>

<body>

<div class="page-wrapper">

    <!-- HEADER -->
    <div class="page-header">
        <img src="{{ asset('images/logo.png') }}" alt="AutoShineX">
        <h1>Create Your Account</h1>
        <p>Register once to manage bookings and carwash services easily</p>
    </div>

    <!-- CARD -->
    <div class="register-card">

        @if ($errors->any())
            <div class="error-message">{{ $errors->first() }}</div>
        @endif

        @if (session('success'))
            <div class="success-message">{{ session('success') }}</div>
        @endif

        <form action="{{ route('register.submit') }}" method="POST">
            @csrf

            <!-- PERSONAL -->
            <div class="section">
                <div class="section-title">PERSONAL INFORMATION</div>
                <div class="form-grid">
                    <div class="input-group">
                        <i class="bi bi-person-fill"></i>
                        <input type="text" name="FullName" placeholder="Full name" required>
                    </div>

                    <div class="input-group">
                        <i class="bi bi-telephone-fill"></i>
                        <input type="text" name="PhoneNumber" placeholder="Phone number" required>
                    </div>
                </div>
            </div>

            <!-- ACCOUNT -->
            <div class="section">
                <div class="section-title">ACCOUNT DETAILS</div>
                <div class="form-grid">
                    <div class="input-group full">
                        <i class="bi bi-envelope-fill"></i>
                        <input type="email" name="Email" placeholder="Email address" required>
                    </div>

                    <div class="input-group">
                        <i class="bi bi-person-badge-fill"></i>
                        <input type="text" name="UserName" placeholder="Username" required>
                    </div><br>

                    <div class="input-group">
                        <i class="bi bi-shield-lock-fill"></i>
                        <input type="password" name="UserPassword" placeholder="Password" required>
                    </div><br>
<small style="color:#64748b;">
Password must contain at least 8 characters, uppercase, lowercase, number, and special symbol.
</small>

                    <div class="input-group full">
                        <i class="bi bi-shield-lock"></i>
                        <input type="password" name="ConfirmPassword" placeholder="Confirm password" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-register">
                Create Account
            </button>
        </form>

        <div class="form-footer">
            Already have an account?
            <a href="{{ route('login') }}">Login here</a>
        </div>
    </div>

</div>

</body>
</html>
