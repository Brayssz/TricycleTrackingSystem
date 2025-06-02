<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverCoordinate extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'latitude',
        'longitude',
        'recorded_at',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'driver_id');
    }
}
