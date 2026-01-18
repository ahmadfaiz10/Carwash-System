<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer';
    protected $primaryKey = 'CustomerID';
    public $timestamps = false;

    protected $fillable = [
        'CustomerName',
        'CustomerEmail',
        'CustomerPhone',
        'CustomerAddress',
        'UserID',
    ];

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->CustomerName;
    }

    public function getEmailAttribute()
    {
        return $this->CustomerEmail;
    }

    public function getPhoneNumberAttribute()
    {
        return $this->CustomerPhone;
    }

    public function getAddressAttribute()
    {
        return $this->CustomerAddress;
    }
}

