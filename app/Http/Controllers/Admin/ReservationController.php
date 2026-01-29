<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Back\Lockers\Locker;
use App\Models\Back\Reservations\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $q = Reservation::query()
            ->with(['user:id,email,first_name,last_name,name', 'locker:id,code,name,location_id'])
            ->when($request->search, function ($q, $s) {
                $q->whereHas('user', function ($uq) use ($s) {
                    $uq->where('email', 'like', "%{$s}%")
                        ->orWhere('name', 'like', "%{$s}%")
                        ->orWhere('first_name', 'like', "%{$s}%")
                        ->orWhere('last_name', 'like', "%{$s}%");
                })->orWhereHas('locker', function ($lq) use ($s) {
                    $lq->where('code', 'like', "%{$s}%")
                        ->orWhere('name', 'like', "%{$s}%");
                });
            })
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->orderByDesc('starts_at');
        
        return Inertia::render('admin/reservations/Index', [
            'reservations' => $q->paginate(20)->withQueryString(),
            'filters' => [
                'search' => $request->search,
                'status' => $request->status,
            ],
        ]);
    }
    
    public function create()
    {
        return Inertia::render('admin/reservations/Create', [
            'users' => User::query()->orderBy('email')->get(['id', 'email', 'first_name', 'last_name', 'name']),
            'lockers' => Locker::query()->with('location:id,name')->orderBy('code')->get(['id','location_id','code','name']),
        ]);
    }
    
    public function store(Request $request)
    {
        $reservation = Reservation::create($this->validated($request));
        
        return redirect("/admin/reservations/{$reservation->id}/edit")
            ->with('success', 'Rezervacija kreirana.');
    }
    
    public function show(Reservation $reservation)
    {
        return Inertia::render('admin/reservations/Show', [
            'reservation' => $reservation->load(['user', 'locker.location']),
        ]);
    }
    
    public function edit(Reservation $reservation)
    {
        return Inertia::render('admin/reservations/Edit', [
            'reservation' => $reservation->load(['user', 'locker.location']),
            'users' => User::query()->orderBy('email')->get(['id', 'email', 'first_name', 'last_name', 'name']),
            'lockers' => Locker::query()->with('location:id,name')->orderBy('code')->get(['id','location_id','code','name']),
        ]);
    }
    
    public function update(Request $request, Reservation $reservation)
    {
        $reservation->update($this->validated($request, $reservation->id));
        
        return redirect("/admin/reservations/{$reservation->id}/edit")
            ->with('success', 'Rezervacija ažurirana.');
    }
    
    private function validated(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'locker_id' => ['required', 'exists:lockers,id'],
            
            'status' => ['required', 'in:draft,active,ended,cancelled'],
            
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            
            'price' => ['nullable', 'numeric', 'min:0'],
            'currency_code' => ['required', 'string', 'size:3'],
            
            'note' => ['nullable', 'string'],
        ]);
    }
}