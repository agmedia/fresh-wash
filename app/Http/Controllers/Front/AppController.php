<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AppController
{
    
    public function home(Request $request)
    {
        if ($request->boolean('openApp') && $this->isMobile($request)) {
            return redirect('/app');
        }
        
        $isMobile = $this->isMobile($request);
        
        // desktop -> blade landing (tailwind)
        if (! $isMobile) {
            return view('landing.desktop', [
                'isAuthenticated' => $request->user() !== null,
            ]);
        }
        
        // mobile -> PWA (Inertia)
        return Inertia::render('front/App', [
            'isAuthenticated' => $request->user() !== null,
        ]);
    }
    
    private function isMobile(Request $request): bool
    {
        // grubo ali radi: iOS/Android + touch uređaji
        $ua = (string) $request->userAgent();
        
        return (bool) preg_match('/Android|iPhone|iPad|iPod|IEMobile|Windows Phone|webOS|BlackBerry/i', $ua);
    }
}