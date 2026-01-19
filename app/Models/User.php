<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';
    protected $primaryKey = 'UserID';
    public $timestamps = false;

    protected $fillable = [
        'FullName',
        'PhoneNumber',
        'Email',
        'UserName',
        'UserPassword',
        'UserRole',
        'Status',
        'email_verification_token',
        'email_verified_at',
        'password_reset_token',
        'password_reset_expires',
    ];

    protected $hidden = ['UserPassword'];

    // 🔐 Tell Laravel which column is the password
    public function getAuthPassword()
    {
        return $this->UserPassword;
    }
}
