<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'booking';
    protected $primaryKey = 'BookingID';
    public $timestamps = false;

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerID', 'CustomerID');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'id', 'id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'BookingID', 'BookingID');
    }
}
