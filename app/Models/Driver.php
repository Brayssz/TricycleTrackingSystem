<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $primaryKey = 'driver_id';

    protected $fillable = [
        'name',
        'license_number',
        'phone',
        'address',
        'status',
        'username',
    ];

    public function tricycles()
    {
        return $this->hasMany(Tricycle::class, 'driver_id', 'driver_id');
    }
}