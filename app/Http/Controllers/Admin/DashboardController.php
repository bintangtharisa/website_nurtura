<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use MongoDB\BSON\UTCDateTime;
use App\Models\Screening;

class DashboardController extends Controller
{

    public function dashboardAdmin()
    {
        $totalUser = User::whereIn('role', ['father', 'mother'])->count();

        $screenings = Screening::latest()->take(10)->get();

        return view('admin.dashboard', [
            'totalUser' => $totalUser,
            'screenings' => $screenings
        ]);
    }


    public function screenings()
    {
        $screenings = Screening::latest()->get();

        return response()->json([
            'status' => true,
            'data' => $screenings
        ]);
    }}