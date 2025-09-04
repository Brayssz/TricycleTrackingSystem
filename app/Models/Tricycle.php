<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tricycle extends Model
{
    use HasFactory;

    protected $primaryKey = 'tricycle_id';

    protected $fillable = [
        'plate_number',
        'motorcycle_model',
        'color',
        'driver_id',
        'device_id',
        'status',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'driver_id');
    }

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id', 'device_id');
    }
}