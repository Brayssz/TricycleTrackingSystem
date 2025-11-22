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

            // Get the latest log for each tricycle
            $latestLogs = TricycleOutOfBoundaryLog::with(['tricycle', 'driver'])
                ->select('tricycle_out_of_boundary_logs.*')
                ->whereIn('log_id', function ($query) {
                    $query->select(DB::raw('MAX(log_id)'))
                        ->from('tricycle_out_of_boundary_logs')
                        ->groupBy('tricycle_id');
                });

            $query = $latestLogs;

            // Optional search
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
