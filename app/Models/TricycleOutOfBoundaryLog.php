<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TricycleOutOfBoundaryLog extends Model
{
    use HasFactory;

    protected $table = 'tricycle_out_of_boundary_logs';
    protected $primaryKey = 'log_id';

    protected $fillable = [
        'tricycle_id',
        'driver_id',
        'latitude',
        'longitude',
        'status',
        'note',
        'detected_at',
    ];

    protected $casts = [
        'detected_at' => 'datetime',
    ];

    public function tricycle()
    {
        return $this->belongsTo(Tricycle::class, 'tricycle_id', 'tricycle_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'driver_id');
    }
}
