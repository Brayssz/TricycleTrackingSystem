<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceCoordinate;
use Illuminate\Http\Request;
use App\Models\Tricycle;
use Illuminate\Support\Facades\DB;

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

    public function getTricycleLocation()
    {

        try {
            // Join tricycles, drivers, devices, and latest device coordinates
            $tricycles = DB::table('tricycles')
                ->leftJoin('drivers', 'tricycles.driver_id', '=', 'drivers.driver_id')
                ->leftJoin('devices', 'tricycles.device_id', '=', 'devices.device_id')
                ->leftJoin('device_coordinates', function ($join) {
                    $join->on('devices.device_id', '=', 'device_coordinates.device_id')
                        ->whereRaw('device_coordinates.id IN (
                            SELECT MAX(id) FROM device_coordinates GROUP BY device_id
                         )');
                })
                ->select(
                    'tricycles.tricycle_id as id',
                    'drivers.name as driver_name',
                    'device_coordinates.latitude',
                    'device_coordinates.longitude',
                    'tricycles.status'
                )
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Tricycle locations retrieved successfully',
                'data' => $tricycles
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve tricycle locations',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
