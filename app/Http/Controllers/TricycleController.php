<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tricycle;
use Illuminate\Support\Facades\DB;

class TricycleController extends Controller
{
    public function show(Request $request)
    {
        if ($request->ajax()) {
            $query = Tricycle::query()->with(['driver', 'device']);

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('search') && !empty($request->input('search')['value'])) {
                $search = $request->input('search')['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('plate_number', 'like', '%' . $search . '%')
                        ->orWhere('motorcycle_model', 'like', '%' . $search . '%')
                        ->orWhere('color', 'like', '%' . $search . '%');
                });
            }

            $totalRecords = $query->count();

            $orderColumnIndex = $request->input('order')[0]['column'] ?? 0;
            $orderColumn = $request->input('columns')[$orderColumnIndex]['data'] ?? 'tricycle_id';
            $orderDirection = $request->input('order')[0]['dir'] ?? 'asc';
            $query->orderBy($orderColumn, $orderDirection);

            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $tricycles = $query->skip($start)->take($length)->get();

            $tricycles->transform(function ($tricycle) {
                return $tricycle;
            });

            return response()->json([
                "draw" => intval($request->input('draw', 1)),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $totalRecords,
                "data" => $tricycles
            ]);
        }

        return view('contents.tricycles');
    }

    
}
