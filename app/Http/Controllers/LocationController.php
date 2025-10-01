<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceCoordinate;
use Illuminate\Http\Request;
use App\Models\Tricycle;

class LocationController extends Controller
{
    public function store(Request $request)
    {
        $device = Device::where('device_identifier', $request->device_identifier)->first();

        if (!$device) {
            return response()->json(['error' => 'Device not found.'], 404);
        }

        $coordinate = new DeviceCoordinate();
        $coordinate->device_id = $device->device_id;
        $coordinate->latitude = $request->latitude;
        $coordinate->longitude = $request->longitude;
        $coordinate->save();

        return response()->json(['message' => 'Location saved successfully.'], 201);
    }

    public function tricyclesWithLatestUpdate()
    {
        $tricycles = Tricycle::with(['device'])
            ->whereNotNull('device_id')
            ->get()
            ->map(function ($tricycle) {
                $latestCoordinate = DeviceCoordinate::where('device_id', $tricycle->device_id)
                    ->latest('recorded_at')
                    ->first();

                $timeAgo = null;
                if ($latestCoordinate) {
                    $timeAgo = $latestCoordinate->recorded_at->diffForHumans();
                }

                return [
                    'tricycle_id' => $tricycle->tricycle_id,
                    'plate_number' => $tricycle->plate_number,
                    'device' => [
                        'device_id' => $tricycle->device->device_id,
                        'device_name' => $tricycle->device->device_name,
                        'device_identifier' => $tricycle->device->device_identifier,
                    ],
                    'latest_update' => $latestCoordinate ? $latestCoordinate->recorded_at : null,
                    'time_ago' => $timeAgo,
                ];
            });

        return response()->json($tricycles);
    }
}
