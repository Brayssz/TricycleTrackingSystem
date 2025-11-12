<?php

namespace App\Livewire\Tracking;

use Livewire\Component;
use App\Models\Tricycle;

class LocationTracker extends Component
{
    public function getTricycleLocations($driver_id = null, $tricycle_id = null)
    {
        $tricycles = Tricycle::query()
            ->where('status', '!=', 'inactive')
            ->with(['device', 'driver']);

        if ($driver_id) {
            $tricycles->where('driver_id', $driver_id);
        }

        if ($tricycle_id) {
            $tricycles->where('tricycle_id', $tricycle_id); // fixed column
        }

        return $tricycles->get()->map(function ($tricycle) {
            $latestCoordinate = \App\Models\DeviceCoordinate::where('device_id', $tricycle->device_id)
                ->orderByDesc('recorded_at')
                ->first();

            $ago = null;
            if ($latestCoordinate && $latestCoordinate->recorded_at) {
                $diff = now()->diff($latestCoordinate->recorded_at);

                if ($diff->d > 0) {
                    $ago = $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
                } elseif ($diff->h > 0) {
                    $ago = $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
                } elseif ($diff->i > 0) {
                    $ago = $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
                } else {
                    $ago = 'just now';
                }
            }

            return [
                'plate_number' => $tricycle->plate_number,
                'driver_name' => $tricycle->driver->name ?? null,
                'lat' => $latestCoordinate->latitude ?? null,
                'lng' => $latestCoordinate->longitude ?? null,
                'status' => $tricycle->status,
                'last_update' => $ago,
            ];
        });
    }

    public function render()
    {
        return view('livewire.tracking.location-tracker');
    }
}
