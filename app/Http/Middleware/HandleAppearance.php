<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class HandleAppearance
{
    public function handle(Request $request, Closure $next): Response
    {
        $appearance = $request->cookie('appearance');
        $sidebarState = $request->cookie('sidebar_state');
        
        Log::debug('appearance: ' . $appearance);
        Log::debug('sidebar_state: ' . $sidebarState);
        
        $userId = Auth::id();
        
        if ($userId && class_exists(\App\Models\Back\Settings\Setting::class)) {
            $dbAppearance = \App\Models\Back\Settings\Setting::get('ui', 'theme', $userId, null);
            $dbSidebar = \App\Models\Back\Settings\Setting::get('ui', 'sidebar', $userId, null);
            
            if (!empty($dbAppearance)) $appearance = $dbAppearance;
            if (!empty($dbSidebar)) $sidebarState = $dbSidebar;
        }
        
        $appearance = $appearance ?: 'system';
        $sidebarState = $sidebarState ?: 'expanded';

        // Normalize sidebar values for frontend cookie consumption
        // DB stores: expanded/collapsed; frontend expects: open/closed
        if ($sidebarState === 'expanded') {
            $sidebarState = 'open';
        } elseif ($sidebarState === 'collapsed') {
            $sidebarState = 'closed';
        }
        
        View::share('appearance', $appearance);
        View::share('sidebar_state', $sidebarState);

        Log::debug('appearance: ' . $appearance);
        Log::debug('sidebar_state: ' . $sidebarState);
        
        // sync cookies (important!) - must be JS-readable (httpOnly=false)
        if ($request->cookie('appearance') !== $appearance) {
            Cookie::queue('appearance', $appearance, 60 * 24 * 365, '/', null, null, false, false, 'Lax');
        }
        if ($request->cookie('sidebar_state') !== $sidebarState) {
            Cookie::queue('sidebar_state', $sidebarState, 60*24*365, '/', null, null, false, false, 'Lax');
            Cookie::queue('sidebar_state', $sidebarState, 60*24*365, '/admin', null, null, false, false, 'Lax');
        }
        
        return $next($request);
    }
}