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
        'email_verification_token',
        'email_verified_at',
    ];

    protected $hidden = ['UserPassword'];

    public function getAuthPassword()
    {
        return $this->UserPassword;
    }

    public function isEmailVerified()
    {
        return $this->email_verified_at !== null;
    }
}

