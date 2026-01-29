<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Back\Pages\PageCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = PageCategory::query()
            ->when($request->search, fn($q,$s)=>$q->where('name','like',"%{$s}%")->orWhere('slug','like',"%{$s}%"))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();
        
        return Inertia::render('admin/pages/categories/Index', [
            'categories' => $categories,
            'filters' => ['search' => $request->search],
        ]);
    }
    
    public function create()
    {
        return Inertia::render('admin/pages/categories/Create');
    }
    
    public function store(Request $request)
    {
        $data = $this->validated($request);
        $cat = PageCategory::create($data);
        
        return redirect()->route('admin.page-categories.edit', $cat)->with('success','Kategorija kreirana.');
    }
    
    public function edit(PageCategory $pageCategory)
    {
        return Inertia::render('admin/pages/categories/Edit', ['category' => $pageCategory]);
    }
    
    public function update(Request $request, PageCategory $pageCategory)
    {
        $pageCategory->update($this->validated($request, $pageCategory->id));
        
        return redirect()->route('admin.page-categories.edit', $pageCategory)->with('success','Kategorija ažurirana.');
    }
    
    private function validated(Request $request, ?int $id=null): array
    {
        return $request->validate([
            'name' => ['required','string','max:150'],
            'slug' => ['required','string','max:190','unique:page_categories,slug,' . $id],
            'description' => ['nullable','string'],
            'is_active' => ['boolean'],
        ]);
    }
}