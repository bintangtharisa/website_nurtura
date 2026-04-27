<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Screening;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use MongoDB\BSON\UTCDateTime;

class DashboardAdminController extends Controller
{
    public function dashboard()
    {
        try {

            $twoMonthsAgo = new UTCDateTime(
                now()->subMonths(2)->timestamp * 1000
            );

            $totalUser = User::whereIn('role', ['father', 'mother'])
                ->whereNotNull('last_login')
                ->where('last_login', '>=', $twoMonthsAgo)
                ->count();

            $totalPengguna = User::whereIn('role', ['father', 'mother'])->count();

            return response()->json([
                'status' => true,
                'totalUser' => $totalUser,
                'totalPengguna' => $totalPengguna
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Dashboard error',
                'error' => $e->getMessage()
            ], 500);
        }
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