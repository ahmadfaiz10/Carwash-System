<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    protected $primaryKey = 'PaymentID';
    public $timestamps = false;

    protected $fillable = [
        'CustomerID',
        'BookingID',
        'PurchaseID',
        'Amount',
        'PaymentMethod',
        'ReferenceNumber',
        'BankType',
        'OwnerName',
        'PaymentStatus',
        'ReceiptPath'
    ];

    // Relationship: Payment belongs to Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerID', 'CustomerID');
    }

    // Relationship: Payment belongs to Booking (optional)
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'BookingID', 'BookingID');
    }

    // Relationship: Payment belongs to Purchase (optional)
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'PurchaseID', 'PurchaseID');
    }
}
