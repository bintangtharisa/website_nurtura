<?php

namespace App\Http\Controllers\Father;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        return response()->json([
            'status' => true,
            'totalUser' => 10,
            'totalPengguna' => 5,
            'user' => $request->user()
        ]);
    }
}