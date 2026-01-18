<h2>Password Reset Request</h2>

<p>Click the button below to reset your password:</p>

<a href="{{ url('/reset-password/'.$token) }}"
   style="padding:10px 15px;background:#0284c7;color:white;text-decoration:none;">
   Reset Password
</a>

<p>This link expires in 30 minutes.</p>

<p>If you did not request this, ignore this email.</p>
