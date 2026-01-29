<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Back\Catalog\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::query()
            ->when($request->search, fn ($q, $s) => $q
                ->where('code', 'like', "%{$s}%")
                ->orWhere('name', 'like', "%{$s}%")
            )
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();
        
        return Inertia::render('admin/catalog/services/Index', [
            'services' => $services,
            'filters' => ['search' => $request->search],
        ]);
    }
    
    public function create()
    {
        return Inertia::render('admin/catalog/services/Create');
    }
    
    public function store(Request $request)
    {
        $service = Service::create($this->validated($request));
        
        return redirect("/admin/catalog/services/{$service->id}/edit")
            ->with('success', 'Usluga kreirana.');
    }
    
    public function show(Service $service)
    {
        return Inertia::render('admin/catalog/services/Show', [
            'service' => $service,
        ]);
    }
    
    public function edit(Service $service)
    {
        return Inertia::render('admin/catalog/services/Edit', [
            'service' => $service,
        ]);
    }
    
    public function update(Request $request, Service $service)
    {
        $service->update($this->validated($request, $service->id));
        
        return redirect("/admin/catalog/services/{$service->id}/edit")
            ->with('success', 'Usluga ažurirana.');
    }
    
    private function validated(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:services,code,' . ($id ?? 'NULL')],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);
    }
}