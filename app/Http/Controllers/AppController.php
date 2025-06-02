<?php

namespace App\Http\Controllers;

use App\Models\Tricycle;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function show(Request $request)
    {
        $total_users = User::where('status', 'active')->count();
        $total_drivers = Driver::where('status', 'active')->count();
        $total_tricycles = Tricycle::where('status', 'active')->count(); 

        return view('contents.dashboard', compact('total_users', 'total_drivers', 'total_tricycles'));
    }
}
