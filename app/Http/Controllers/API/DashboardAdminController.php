<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Screening;

class DashboardAdminController extends Controller
{
    public function dashboard()
{
    $twoMonthsAgo = now()->subMonths(2);

    $totalUser = User::whereIn('role', ['father', 'mother'])
        ->where('last_login', '>=', new \MongoDB\BSON\UTCDateTime($twoMonthsAgo->timestamp * 1000))
        ->count();

    $totalPengguna = User::whereIn('role', ['father', 'mother'])->count();

    return response()->json([
        'status'        => true,
        'totalUser'     => $totalUser,
        'totalPengguna' => $totalPengguna
    ]);
}

    public function screenings()
    {
        $screenings = Screening::latest()->get();

        return response()->json([
            'status' => true,
            'data' => $screenings
        ]);
    }
}