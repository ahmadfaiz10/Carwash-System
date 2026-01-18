<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password - AutoShineX</title>

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

.left h1{font-size:46px;font-weight:900;margin-bottom:20px}
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

.card h2{text-align:center;margin-bottom:10px}
.card p{text-align:center;color:#475569;font-size:14px;margin-bottom:25px}

label{font-size:14px;font-weight:600}
input{
    width:100%;
    padding:14px;
    margin:8px 0 18px;
    border-radius:10px;
    border:1px solid #cbd5e1;
    background:#f8fafc;
}

small{color:#64748b;font-size:12px}

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

.error{color:#dc2626;font-size:14px;margin-bottom:12px;text-align:center}

@media(max-width:900px){
    .left{display:none}
}
</style>
</head>

<body>

<div class="left">
    <h1>Set a new password</h1>
    <p>
        Choose a strong password to keep your AutoShineX account safe.
    </p>
</div>

<div class="right">
    <div class="card">

        <h2>Reset Password</h2>
        <p>Create a new secure password</p>

        @if($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <label>New Password</label>
            <input type="password" name="NewPassword" required>
            <small>At least 8 chars, uppercase, lowercase, number & symbol</small>

            <label style="margin-top:15px">Confirm Password</label>
            <input type="password" name="ConfirmPassword" required>

            <button type="submit" style="margin-top:20px">Reset Password</button>
        </form>

    </div>
</div>

</body>
</html>
