<?php

namespace App\Livewire\Tracking;

use Livewire\Component;

class LocationTracker extends Component
{
    public function getTricycleLocations($driver_id = null, $tricycle_id = null)
    {
        $tricycles = \App\Models\Tricycle::query()
            ->where('status', '!=', 'inactive')
            ->with(['device', 'driver']);

        if ($driver_id) {
            $tricycles->where('driver_id', $driver_id);
        }

        if ($tricycle_id) {
            $tricycles->where('id', $tricycle_id);
        }

        return $tricycles->get()->map(function ($tricycle) {
            $latestCoordinate = \App\Models\DeviceCoordinate::where('device_id', $tricycle->device_id)
                ->orderByDesc('recorded_at')
                ->first();

            return [
                'plate_number' => $tricycle->plate_number,
                'driver_name' => $tricycle->driver->name ?? null,
                'lat' => $latestCoordinate->latitude ?? null,
                'lng' => $latestCoordinate->longitude ?? null,
                'status' => $tricycle->status,
            ];
        });
    }
    public function render()
    {
        return view('livewire.tracking.location-tracker');
    }
}
