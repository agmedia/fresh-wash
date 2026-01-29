<?php

namespace App\Http\Controllers\Front;

use Inertia\Inertia;

class InfoController
{
    public function home()       { return Inertia::render('front/info/Home'); }
    public function how()        { return Inertia::render('front/info/HowItWorks'); }
    public function locations()  { return Inertia::render('front/info/Locations'); }
    public function pricing()    { return Inertia::render('front/info/Pricing'); }
    public function faq()        { return Inertia::render('front/info/Faq'); }
    public function contact()    { return Inertia::render('front/info/Contact'); }
}