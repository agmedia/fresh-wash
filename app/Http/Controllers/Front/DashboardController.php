<?php

namespace App\Http\Controllers\Front;

use Inertia\Inertia;

class DashboardController
{
    public function index()
    {
        return Inertia::render('front/Dashboard');
    }
}