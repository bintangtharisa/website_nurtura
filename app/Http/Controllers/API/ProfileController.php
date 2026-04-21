<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class ProfileController extends Controller
{
public function me()
{
    return response()->json([
        'message' => 'Berhasil mengambil profil',
        'data' => [
            'id' => (string) auth()->user()->_id,
            'username' => auth()->user()->username,
            'email' => auth()->user()->email,
            'role' => auth()->user()->role
        ]
    ]);
}}