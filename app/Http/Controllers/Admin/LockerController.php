<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Back\Lockers\Locker;
use App\Models\Back\Locations\Location;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LockerController extends Controller
{
    public function index(Request $request)
    {
        $lockers = Locker::with('location')
            ->when($request->search, function ($q, $s) {
                $q->where('code', 'like', "%{$s}%")
                    ->orWhere('name', 'like', "%{$s}%");
            })
            ->orderBy('code')
            ->paginate(20)
            ->withQueryString();
        
        return Inertia::render('admin/lockers/Index', [
            'lockers' => $lockers,
            'filters' => ['search' => $request->search],
        ]);
    }
    
    public function create()
    {
        return Inertia::render('admin/lockers/Create', [
            'locations' => Location::orderBy('name')->get(['id', 'name']),
        ]);
    }
    
    public function store(Request $request)
    {
        $locker = Locker::create($this->validated($request));
        
        return redirect()
            ->route('admin.lockers.edit', $locker)
            ->with('success', 'Ormarić kreiran.');
    }
    
    public function show(Locker $locker)
    {
        return Inertia::render('admin/lockers/Show', [
            'locker' => $locker->load('location'),
        ]);
    }
    
    public function edit(Locker $locker)
    {
        return Inertia::render('admin/lockers/Edit', [
            'locker' => $locker,
            'locations' => Location::orderBy('name')->get(['id', 'name']),
        ]);
    }
    
    public function update(Request $request, Locker $locker)
    {
        $locker->update($this->validated($request, $locker->id));
        
        return redirect()
            ->route('admin.lockers.edit', $locker)
            ->with('success', 'Ormarić ažuriran.');
    }
    
    private function validated(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'location_id' => ['required', 'exists:locations,id'],
            'code' => ['required', 'string', 'max:50'],
            'name' => ['nullable', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'unique:lockers,slug,' . $id],
            'size' => ['required', 'in:S,M,L,XL'],
            'hardware_uid' => ['nullable', 'string', 'max:100'],
            'is_active' => ['boolean'],
            'is_available' => ['boolean'],
            'note' => ['nullable', 'string'],
        ]);
    }
}