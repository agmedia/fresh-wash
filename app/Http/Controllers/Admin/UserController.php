<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->search, function ($q, $s) {
                $q->where(function ($q) use ($s) {
                    $q->where('email', 'like', "%{$s}%")
                        ->orWhere('name', 'like', "%{$s}%")
                        ->orWhere('first_name', 'like', "%{$s}%")
                        ->orWhere('last_name', 'like', "%{$s}%");
                });
            })
            ->with(['roles:name'])
            ->orderBy('id', 'desc')
            ->paginate(20)
            ->withQueryString();
        
        // Flatten roles relation to a simple array of role names for Vue
        $users->getCollection()->transform(function ($user) {
            $user->roles = $user->roles?->pluck('name')->values()->all() ?? [];
            return $user;
        });
        
        return Inertia::render('admin/users/Index', [
            'users' => $users,
            'filters' => ['search' => $request->search],
        ]);
    }
    
    public function create()
    {
        return Inertia::render('admin/users/Create');
    }
    
    public function store(Request $request)
    {
        $data = $this->validated($request, creating: true);
        
        $user = User::create([
            'name' => trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? '')) ?: ($data['email']),
            'first_name' => $data['first_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'address_line1' => $data['address_line1'] ?? null,
            'address_line2' => $data['address_line2'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
            'city' => $data['city'] ?? null,
            'country_code' => $data['country_code'] ?? 'HR',
            'phone' => $data['phone'] ?? null,
            'whatsapp_opt_in' => (bool)($data['whatsapp_opt_in'] ?? false),
            'email_verified_at' => now(),
        ]);
        
        return redirect("/admin/users/{$user->id}/edit")->with('success', 'Korisnik kreiran.');
    }
    
    public function show(User $user)
    {
        return Inertia::render('admin/users/Show', [
            'user' => $user,
        ]);
    }
    
    public function edit(User $user)
    {
        return Inertia::render('admin/users/Edit', [
            'user' => $user,
        ]);
    }
    
    public function update(Request $request, User $user)
    {
        $data = $this->validated($request, creating: false, userId: $user->id);
        
        $user->fill([
            'first_name' => $data['first_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'email' => $data['email'],
            'address_line1' => $data['address_line1'] ?? null,
            'address_line2' => $data['address_line2'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
            'city' => $data['city'] ?? null,
            'country_code' => $data['country_code'] ?? 'HR',
            'phone' => $data['phone'] ?? null,
            'whatsapp_opt_in' => (bool)($data['whatsapp_opt_in'] ?? false),
        ]);
        
        // sync name
        $user->name = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: $user->email;
        
        // optional password change
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        
        $user->save();
        
        return redirect("/admin/users/{$user->id}/edit")->with('success', 'Korisnik ažuriran.');
    }
    
    private function validated(Request $request, bool $creating, ?int $userId = null): array
    {
        return $request->validate([
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . ($userId ?? 'NULL')],
            'password' => $creating ? ['required', 'string', 'min:8'] : ['nullable', 'string', 'min:8'],
            
            'address_line1' => ['nullable', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'city' => ['nullable', 'string', 'max:100'],
            'country_code' => ['nullable', 'string', 'size:2'],
            
            'phone' => ['nullable', 'string', 'max:50'],
            'whatsapp_opt_in' => ['boolean'],
        ]);
    }
}