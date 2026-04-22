<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Screening;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
        // Data akan di-fetch oleh JS via API, bukan di-pass dari sini
    }
}