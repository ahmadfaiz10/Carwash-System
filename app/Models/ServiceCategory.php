<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    use HasFactory;

    protected $table = 'service_categories'; // ✅ make sure this matches your actual table name

    protected $fillable = ['name'];

    // ✅ Relationship to Services
    public function services()
    {
        // 'ServiceCategory' is the column name in `services` table
        return $this->hasMany(Service::class, 'ServiceCategory');
    }
}
