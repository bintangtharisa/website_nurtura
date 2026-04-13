<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // VALIDASI (SESUAI MONGODB)
        $request->validate([
            'name' => 'required|string|min:4',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:ayah,ibu,admin'
        ]);

        // MAP ROLE → MONGODB ENUM
        $role = match ($request->role) {
            'ayah' => 'father',
            'ibu'  => 'mother',
            default => 'admin'
        };

        // SIMPAN (SESUAI SCHEMA)
        User::create([
            'username'        => $request->name,
            'email'           => $request->email,
            'password_hash'   => Hash::make($request->password),
            'role'            => $role,

            // OPTIONAL SESUAI VALIDATOR
            'connection_code' => Str::random(6),
            'code_used'       => false,

            // BSON DATE (PENTING)
            'created_at'      => Carbon::now(),
            'updated_at'      => Carbon::now(),
        ]);

        // REDIRECT
        return redirect('/login')->with('status', 'Register berhasil!');
    }
}