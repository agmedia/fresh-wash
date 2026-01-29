<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Back\Locations\Location;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $locations = Location::query()
            ->when($request->get('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhere('postal_code', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();
        
        return Inertia::render('admin/locations/Index', [
            'locations' => $locations,
            'filters' => [
                'search' => $request->get('search'),
            ],
        ]);
    }
    
    public function create()
    {
        return Inertia::render('admin/locations/Create');
    }
    
    public function store(Request $request)
    {
        $data = $this->validated($request);
        
        $location = Location::create($data);
        
        return redirect()
            ->route('admin.locations.edit', $location)
            ->with('success', 'Lokacija je kreirana.');
    }
    
    public function show(Location $location)
    {
        return Inertia::render('admin/locations/Show', [
            'location' => $location,
        ]);
    }
    
    public function edit(Location $location)
    {
        return Inertia::render('admin/locations/Edit', [
            'location' => $location,
        ]);
    }
    
    public function update(Request $request, Location $location)
    {
        $data = $this->validated($request, $location->id);
        
        $location->update($data);
        
        return redirect()
            ->route('admin.locations.edit', $location)
            ->with('success', 'Lokacija je ažurirana.');
    }
    
    private function validated(Request $request, ?int $locationId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:180', 'unique:locations,slug,' . ($locationId ?? 'NULL')],
            
            'address_line1' => ['nullable', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'city' => ['nullable', 'string', 'max:100'],
            'country_code' => ['nullable', 'string', 'size:2'],
            
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
            
            'is_active' => ['required', 'boolean'],
            'note' => ['nullable', 'string'],
        ]);
    }
}