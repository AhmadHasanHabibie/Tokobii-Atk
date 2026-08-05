<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Display the owner dashboard.
     */
    public function index(): View
    {
        return view('owner.dashboard.index');
    }
}