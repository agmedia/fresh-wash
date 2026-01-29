<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();
        
        return Inertia::render('front/Profile', [
            'user' => $user->only([
                'id',
                'first_name',
                'last_name',
                'name',
                'email',
                'address_line1',
                'address_line2',
                'postal_code',
                'city',
                'country_code',
                'phone',
                'whatsapp_opt_in',
            ]),
        ]);
    }
    
    public function update(Request $request)
    {
        $user = $request->user();
        
        $data = $request->validate([
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'address_line1' => ['nullable', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'city' => ['nullable', 'string', 'max:100'],
            'country_code' => ['nullable', 'string', 'size:2'],
            'phone' => ['nullable', 'string', 'max:50'],
            'whatsapp_opt_in' => ['boolean'],
        ]);
        
        // ako checkbox ne dođe, neka bude false
        $data['whatsapp_opt_in'] = (bool) ($data['whatsapp_opt_in'] ?? false);
        
        // opcionalno: sync name iz first/last
        $fullName = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));
        if ($fullName !== '') {
            $data['name'] = $fullName;
        }
        
        $user->update($data);
        
        return back()->with('success', 'Profil je spremljen.');
    }
    
    public function destroy(Request $request)
    {
        $user = $request->user();
        
        Auth::logout();
        
        // GDPR: pobriši usera (kasnije možemo dodati cleanup povezanih tablica)
        $user->delete();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Račun je obrisan.');
    }
}