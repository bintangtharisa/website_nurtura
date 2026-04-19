<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private function bsonDate($time = null)
    {
        return new UTCDateTime(($time ?? now())->getTimestamp() * 1000);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:4', 'max:50'],
            'email' => ['required', 'string', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required', 'string', 'in:ayah,ibu,admin']
        ]);

        $role = match ($request->role) {
            'ayah' => 'father',
            'ibu'  => 'mother',
            default => 'admin'
        };

        $data = [
            'username'      => $request->name,
            'email'         => $request->email,
            'password_hash' => Hash::make($request->password),
            'role'          => $role,
            'created_at'    => $this->bsonDate(),
            'updated_at'    => $this->bsonDate(),
        ];

        if ($role === 'father') {
            $data['connection_code'] = Str::random(6);
            $data['code_used'] = false;
            $data['code_expires_at'] = $this->bsonDate(now()->addDay());
        }

        if ($role === 'mother') {
            $data['anonymous_id'] = Str::random(10);
        }

        User::create($data);

        return redirect('/login')->with('status', 'Register berhasil!');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'password' => ['required', 'string']
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password_hash)) {
            return back()->withErrors([
                'email' => 'Email atau password salah'
            ]);
        }

        $user->update([
            'last_login' => $this->bsonDate(),
            'updated_at' => $this->bsonDate()
        ]);

        Auth::login($user);


        session(['role' => $user->role]);

        return redirect('/dashboard');
    }

    public function dashboard()
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

    public function logout()
    {
        Auth::logout();
        session()->flush();
        return redirect('/login');
    }
}