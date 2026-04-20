<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboardAdmin()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $totalUser = User::where('role', 'father')->orWhere('role', 'mother')->count();
        $enamBulanLalu = new \MongoDB\BSON\UTCDateTime(now()->subMonths(6)->getTimestamp() * 1000);
        $userAktif6Bulan = User::where('last_login', '>=', $enamBulanLalu)->count();
        $screenings = [];

        return view('admin.dashboard', [
            'totalUser' => $totalUser,
            'screenings' => $screenings,
            'userAktif6Bulan' => $userAktif6Bulan,
            ]);
    }
}
