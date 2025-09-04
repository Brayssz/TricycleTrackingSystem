<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $primaryKey = 'device_id';

    protected $fillable = [
        'device_name',
        'device_identifier',
        'status',
    ];

    public function coordinates()
    {
        return $this->hasMany(DeviceCoordinate::class, 'device_id', 'device_id');
    }
}