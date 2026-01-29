<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\Back\Settings\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileSettingsController extends Controller
{
    public function edit(Request $request)
    {
        $userId = $request->user()->id;
        
        return Inertia::render('admin/settings/Profile', [
            'settings' => [
                'theme' => Setting::get('ui', 'theme', $userId, 'system'),      // system|dark|light
                'sidebar' => Setting::get('ui', 'sidebar', $userId, 'expanded') // expanded|collapsed
            ],
        ]);
    }
    
    public function update(Request $request)
    {
        $userId = $request->user()->id;
        
        $data = $request->validate([
            'theme' => ['required', 'in:system,dark,light'],
            'sidebar' => ['required', 'in:expanded,collapsed'],
        ]);
        
        Setting::put('ui', 'theme', $data['theme'], $userId);
        Setting::put('ui', 'sidebar', $data['sidebar'], $userId);
        
        return back()->with('success', 'Postavke spremljene.');
    }
}