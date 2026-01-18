<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forgot Password - AutoShineX</title>

<style>
*{margin:0;padding:0;box-sizing:border-box}

body{
    font-family:'Poppins',sans-serif;
    height:100vh;
    background:linear-gradient(135deg,#0ea5e9,#0284c7);
    display:flex;
}

.left{
    flex:1;
    color:#fff;
    padding:80px;
    display:flex;
    flex-direction:column;
    justify-content:center;
}

.left h1{font-size:48px;font-weight:900;margin-bottom:20px}
.left p{font-size:18px;max-width:480px;line-height:1.6}

.right{
    flex:1;
    background:#f8fafc;
    display:flex;
    align-items:center;
    justify-content:center;
}

.card{
    background:#fff;
    width:420px;
    padding:45px;
    border-radius:18px;
    box-shadow:0 30px 60px rgba(0,0,0,.25);
}

.card h2{
    font-size:24px;
    margin-bottom:10px;
    text-align:center;
}

.card p{
    font-size:14px;
    color:#475569;
    margin-bottom:25px;
    text-align:center;
}

label{font-size:14px;font-weight:600}
input{
    width:100%;
    padding:14px;
    margin:8px 0 18px;
    border-radius:10px;
    border:1px solid #cbd5e1;
    background:#f8fafc;
}

button{
    width:100%;
    padding:15px;
    border:none;
    border-radius:12px;
    background:linear-gradient(135deg,#0ea5e9,#0284c7);
    color:#fff;
    font-size:15px;
    font-weight:700;
    cursor:pointer;
}

button:hover{box-shadow:0 15px 30px rgba(14,165,233,.5)}

.success{color:#16a34a;font-size:14px;margin-bottom:12px;text-align:center}
.error{color:#dc2626;font-size:14px;margin-bottom:12px;text-align:center}

.footer{
    margin-top:20px;
    text-align:center;
    font-size:14px;
}
.footer a{color:#0284c7;font-weight:600;text-decoration:none}

@media(max-width:900px){
    .left{display:none}
    .right{flex:1}
}
</style>
</head>

<body>

<div class="left">
    <h1>Forgot your password?</h1>
    <p>
        No worries. Enter your registered email and we’ll send you a secure
        password reset link.
    </p>
</div>

<div class="right">
    <div class="card">

        <h2>Password Recovery</h2>
        <p>We will email you a secure reset link</p>

        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('forgot.submit') }}" method="POST">
            @csrf

            <label>Email Address</label>
            <input type="email" name="Email" placeholder="your@email.com" required>

            <button type="submit">Send Reset Link</button>
        </form>

        <div class="footer">
            <a href="{{ route('login') }}">← Back to Login</a>
        </div>

    </div>
</div>

</body>
</html>
