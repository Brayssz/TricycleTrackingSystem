<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceCoordinate;
use Illuminate\Http\Request;

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
}
