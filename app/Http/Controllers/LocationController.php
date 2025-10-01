<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceCoordinate;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'device_identifier' => 'required|string|exists:devices,device_identifier',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $device = Device::where('device_identifier', $request->query('device_identifier'))->first();

        if (!$device) {
            return response()->json(['error' => 'Device not found.'], 404);
        }

        $coordinate = new DeviceCoordinate();
        $coordinate->device_id = $device->device_id;
        $coordinate->latitude = $request->query('latitude');
        $coordinate->longitude = $request->query('longitude');
        $coordinate->save();

        return response()->json(['message' => 'Location saved successfully.'], 201);
    }
}
