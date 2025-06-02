<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;

class DriverController extends Controller
{
    public function show(Request $request)
    {
        if ($request->ajax()) {
            $query = Driver::query();

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('search') && !empty($request->input('search')['value'])) {
                $search = $request->input('search')['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('license_number', 'like', '%' . $search . '%')
                      ->orWhere('phone', 'like', '%' . $search . '%')
                      ->orWhere('address', 'like', '%' . $search . '%');
                });
            }

            $totalRecords = $query->count();

            $orderColumnIndex = $request->input('order')[0]['column'] ?? 0;
            $orderColumn = $request->input('columns')[$orderColumnIndex]['data'] ?? 'driver_id';
            $orderDirection = $request->input('order')[0]['dir'] ?? 'asc';
            $query->orderBy($orderColumn, $orderDirection);

            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $drivers = $query->skip($start)->take($length)->get();

            $drivers->transform(function ($driver) {
                return $driver;
            });

            return response()->json([
                "draw" => intval($request->input('draw', 1)),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $totalRecords,
                "data" => $drivers
            ]);
        }

        return view('contents.drivers');
    }
}
