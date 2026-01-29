<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Back\Catalog\Service;
use App\Models\Back\Catalog\Tariff;
use App\Models\Back\Locations\Location;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TariffController extends Controller
{
    public function index(Request $request)
    {
        $tariffs = Tariff::query()
            ->with(['service:id,name,code', 'location:id,name'])
            ->when($request->search, function ($q, $s) {
                $q->whereHas('service', fn($sq) => $sq->where('name', 'like', "%{$s}%")->orWhere('code', 'like', "%{$s}%"))
                    ->orWhereHas('location', fn($lq) => $lq->where('name', 'like', "%{$s}%"));
            })
            ->orderBy('location_id')
            ->orderBy('service_id')
            ->paginate(20)
            ->withQueryString();
        
        return Inertia::render('admin/catalog/tariffs/Index', [
            'tariffs' => $tariffs,
            'filters' => ['search' => $request->search],
        ]);
    }
    
    public function create()
    {
        return Inertia::render('admin/catalog/tariffs/Create', [
            'services' => Service::orderBy('name')->get(['id','name','code']),
            'locations' => Location::orderBy('name')->get(['id','name']),
            'defaults' => [
                'vat_rate' => 25.00,
                'currency' => 'EUR',
            ],
        ]);
    }
    
    public function store(Request $request)
    {
        $data = $this->validated($request);
        
        // simple calc
        $data['price_gross'] = round((float)$data['price_net'] * (1 + ((float)$data['vat_rate'] / 100)), 2);
        
        $tariff = Tariff::create($data);
        
        return redirect("/admin/catalog/tariffs/{$tariff->id}/edit")
            ->with('success', 'Tarifa kreirana.');
    }
    
    public function show(Tariff $tariff)
    {
        $tariff->load(['service:id,name,code', 'location:id,name']);
        
        return Inertia::render('admin/catalog/tariffs/Show', [
            'tariff' => $tariff,
        ]);
    }
    
    public function edit(Tariff $tariff)
    {
        $tariff->load(['service:id,name,code', 'location:id,name']);
        
        return Inertia::render('admin/catalog/tariffs/Edit', [
            'tariff' => $tariff,
            'services' => Service::orderBy('name')->get(['id','name','code']),
            'locations' => Location::orderBy('name')->get(['id','name']),
            'defaults' => [
                'vat_rate' => 25.00,
                'currency' => 'EUR',
            ],
        ]);
    }
    
    public function update(Request $request, Tariff $tariff)
    {
        $data = $this->validated($request, $tariff->id);
        $data['price_gross'] = round((float)$data['price_net'] * (1 + ((float)$data['vat_rate'] / 100)), 2);
        
        $tariff->update($data);
        
        return redirect("/admin/catalog/tariffs/{$tariff->id}/edit")
            ->with('success', 'Tarifa ažurirana.');
    }
    
    private function validated(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'service_id' => [
                'required',
                'exists:services,id',
                Rule::unique('tariffs')
                    ->ignore($id)
                    ->where(fn ($q) => $q->where('location_id', $request->input('location_id'))),
            ],
            'location_id' => ['required', 'exists:locations,id'],
            
            'price_net' => ['required', 'numeric', 'min:0'],
            'vat_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'currency' => ['required', 'string', 'size:3'],
            'is_active' => ['boolean'],
            'valid_from' => ['nullable', 'date'],
            'valid_to' => ['nullable', 'date', 'after_or_equal:valid_from'],
        ], [
            'service_id.unique' => 'Tarifa za odabranu lokaciju i uslugu već postoji.',
        ]);
    }
}