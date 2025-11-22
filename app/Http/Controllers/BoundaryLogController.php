<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TricycleOutOfBoundaryLog;
use Illuminate\Support\Facades\DB;

class BoundaryLogController extends Controller
{
    public function show(Request $request)
    {
        if ($request->ajax()) {

            // Get latest log for each tricycle
            $latestLogs = TricycleOutOfBoundaryLog::with(['tricycle', 'driver'])
                ->select('tricycle_id', DB::raw('MAX(detected_at) as latest_detected_at'))
                ->groupBy('tricycle_id');

            $query = TricycleOutOfBoundaryLog::with(['tricycle', 'driver'])
                ->joinSub($latestLogs, 'latest_logs', function ($join) {
                    $join->on('tricycle_out_of_boundary_logs.tricycle_id', '=', 'latest_logs.tricycle_id')
                        ->on('tricycle_out_of_boundary_logs.detected_at', '=', 'latest_logs.latest_detected_at');
                });

            // Optional search by plate number or driver name
            if ($request->filled('search') && !empty($request->input('search')['value'])) {
                $search = $request->input('search')['value'];
                $query->whereHas('tricycle', function ($q) use ($search) {
                    $q->where('plate_number', 'like', '%' . $search . '%');
                })->orWhereHas('driver', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            }

            $totalRecords = $query->count();

            $logs = $query->get();

            // Transform data for DataTable
            $logs->transform(function ($log) {
                return [
                    'plate_number' => $log->tricycle->plate_number ?? 'N/A',
                    'driver_name' => $log->driver->name ?? 'Unknown',
                    'latitude' => $log->latitude,
                    'longitude' => $log->longitude,
                    'last_seen' => $log->detected_at ? $log->detected_at->diffForHumans() : 'N/A',
                ];
            });

            return response()->json([
                "draw" => intval($request->input('draw', 1)),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $totalRecords,
                "data" => $logs
            ]);
        }

        return view('dashboard');
    }
}
