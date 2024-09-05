<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        $data = User::latest()->paginate();

        return view('dashboard/dashboard', compact('data'));
        // return view('dashboard');
    }
}
