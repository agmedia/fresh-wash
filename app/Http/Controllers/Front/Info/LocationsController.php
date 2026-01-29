<?php

namespace App\Http\Controllers\Front\Info;

use App\Http\Controllers\Controller;
use App\Models\Back\Locations\Location;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LocationsController extends Controller
{
    public function index(Request $request)
    {
        $locations = Location::query()
                             ->where('is_active', true)
                             ->orderBy('name')
                             ->get(['id','name','slug','address_line1','address_line2','postal_code','city','country_code','lat','lng']);

        $ua = (string) $request->header('User-Agent');
        $isMobile = (bool) preg_match('/Mobile|Android|iPhone|iPad|iPod/i', $ua);

        if (! $isMobile) {
            return view('landing.locations', ['locations' => $locations]);
        }

        return Inertia::render('front/info/Locations', [
            'locations' => $locations,
        ]);
    }
}