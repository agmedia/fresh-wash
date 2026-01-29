<?php

namespace App\Http\Controllers\Front;

use Inertia\Inertia;

class LegalController
{
    public function terms()
    {
        return Inertia::render('front/info/Legal/Terms');
    }
    
    public function privacy()
    {
        return Inertia::render('front/info/Legal/Privacy');
    }
}