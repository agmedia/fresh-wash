<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Back\Devices\Device;
use App\Models\Back\Locations\Location;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $devices = Device::query()
            ->with('location:id,name')
            ->when($request->search, function ($q, $s) {
                $q->where('code', 'like', "%{$s}%")
                    ->orWhere('hardware_uid', 'like', "%{$s}%")
                    ->orWhere('name', 'like', "%{$s}%");
            })
            ->orderBy('code')
            ->paginate(20)
            ->withQueryString();
        
        return Inertia::render('admin/devices/Index', [
            'devices' => $devices,
            'filters' => ['search' => $request->search],
        ]);
    }
    
    public function create()
    {
        return Inertia::render('admin/devices/Create', [
            'locations' => Location::orderBy('name')->get(['id', 'name']),
        ]);
    }
    
    public function store(Request $request)
    {
        $device = Device::create($this->validated($request));
        
        return redirect()
            ->route('admin.devices.edit', $device)
            ->with('success', 'Uređaj kreiran.');
    }
    
    public function show(Device $device)
    {
        return Inertia::render('admin/devices/Show', [
            'device' => $device->load('location:id,name'),
            'heartbeats' => $device->heartbeats()
                ->latest('reported_at')
                ->limit(25)
                ->get(),
        ]);
    }
    
    public function edit(Device $device)
    {
        return Inertia::render('admin/devices/Edit', [
            'device' => $device->load('location:id,name'),
            'locations' => Location::orderBy('name')->get(['id', 'name']),
        ]);
    }
    
    public function update(Request $request, Device $device)
    {
        $device->update($this->validated($request, $device->id));
        
        return redirect()
            ->route('admin.devices.edit', $device)
            ->with('success', 'Uređaj ažuriran.');
    }
    
    private function validated(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'location_id' => ['nullable', 'exists:locations,id'],
            
            'code' => ['required', 'string', 'max:50', 'unique:devices,code,' . $id],
            'name' => ['nullable', 'string', 'max:150'],
            
            'hardware_uid' => ['required', 'string', 'max:100', 'unique:devices,hardware_uid,' . $id],
            'model' => ['nullable', 'string', 'max:100'],
            'firmware_version' => ['nullable', 'string', 'max:50'],
            
            'ip_address' => ['nullable', 'string', 'max:45'],
            'mac_address' => ['nullable', 'string', 'max:50'],
            
            'status' => ['required', 'in:online,offline,maintenance'],
            'is_active' => ['boolean'],
            'note' => ['nullable', 'string'],
        ]);
    }
}