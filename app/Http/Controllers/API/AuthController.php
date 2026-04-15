<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:4',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:ayah,ibu,admin'
        ]);

        $role = match ($request->role) {
            'ayah' => 'father',
            'ibu'  => 'mother',
            default => 'admin'
        };

        User::create([
            'username'        => $request->name,
            'email'           => $request->email,
            'password_hash'   => Hash::make($request->password),
            'role'            => $role,
            'connection_code' => Str::random(6),
            'code_used'       => false,
            'created_at'      => Carbon::now(),
            'updated_at'      => Carbon::now(),
        ]);

        return redirect('/login')->with('status', 'Register berhasil!');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password_hash)) {
            return back()->withErrors([
                'email' => 'Email atau password salah'
            ]);
        }

        session([
            'user_id' => $user->_id,
            'role' => $user->role
        ]);

    }

    public function dashboard()
{
    if (!session('user_id')) {
        return redirect('/login');
    }

    return view('admin.dashboard');
}
}