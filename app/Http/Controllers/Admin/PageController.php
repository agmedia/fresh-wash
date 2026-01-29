<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Back\Pages\Page;
use App\Models\Back\Pages\PageCategory;
use App\Models\Back\Pages\PageTag;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $pages = Page::query()
            ->with(['category:id,name', 'tags:id,name'])
            ->when($request->type, fn($q,$t)=>$q->where('type',$t))
            ->when($request->search, function($q,$s){
                $q->where('title','like',"%{$s}%")->orWhere('slug','like',"%{$s}%");
            })
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();
        
        return Inertia::render('admin/pages/pages/Index', [
            'pages' => $pages,
            'filters' => [
                'search' => $request->search,
                'type' => $request->type,
            ],
        ]);
    }
    
    public function create()
    {
        return Inertia::render('admin/pages/pages/Create', [
            'categories' => PageCategory::orderBy('name')->get(['id','name']),
            'tags' => PageTag::orderBy('name')->get(['id','name']),
        ]);
    }
    
    public function store(Request $request)
    {
        $data = $this->validated($request);
        
        $tagIds = $data['tag_ids'] ?? [];
        unset($data['tag_ids']);
        
        $page = Page::create($data);
        $page->tags()->sync($tagIds);
        
        // images[] upload
        if ($request->hasFile('images')) {
            foreach ((array)$request->file('images') as $file) {
                if ($file) $page->addMedia($file)->toMediaCollection('images');
            }
        }
        
        return redirect()->route('admin.pages.edit', $page)->with('success','Stranica kreirana.');
    }
    
    public function show(Page $page)
    {
        $page->load(['category:id,name','tags:id,name']);
        
        return Inertia::render('admin/pages/pages/Show', [
            'page' => $page,
            'images' => $page->getMedia('images')->map(fn($m)=>[
                'id' => $m->id,
                'name' => $m->name,
                'url' => $m->getUrl(),
            ])->values(),
        ]);
    }
    
    public function edit(Page $page)
    {
        $page->load(['category:id,name','tags:id,name']);
        
        return Inertia::render('admin/pages/pages/Edit', [
            'page' => $page,
            'categories' => PageCategory::orderBy('name')->get(['id','name']),
            'tags' => PageTag::orderBy('name')->get(['id','name']),
            'images' => $page->getMedia('images')->map(fn($m)=>[
                'id' => $m->id,
                'name' => $m->name,
                'url' => $m->getUrl(),
            ])->values(),
        ]);
    }
    
    public function update(Request $request, Page $page)
    {
        $data = $this->validated($request, $page->id);
        
        $tagIds = $data['tag_ids'] ?? [];
        unset($data['tag_ids']);
        
        $page->update($data);
        $page->tags()->sync($tagIds);
        
        if ($request->hasFile('images')) {
            foreach ((array)$request->file('images') as $file) {
                if ($file) $page->addMedia($file)->toMediaCollection('images');
            }
        }
        
        return redirect()->route('admin.pages.edit', $page)->with('success','Stranica ažurirana.');
    }
    
    private function validated(Request $request, ?int $id=null): array
    {
        return $request->validate([
            'category_id' => ['nullable','exists:page_categories,id'],
            'title' => ['required','string','max:190'],
            'slug' => ['required','string','max:190','unique:pages,slug,' . $id],
            'body' => ['nullable','string'],
            'meta_title' => ['nullable','string','max:190'],
            'meta_description' => ['nullable','string','max:255'],
            'type' => ['required','in:page,post'],
            'published_at' => ['nullable','date'],
            'is_active' => ['boolean'],
            'tag_ids' => ['array'],
            'tag_ids.*' => ['integer','exists:page_tags,id'],
            'images' => ['nullable','array'],
            'images.*' => ['file','mimes:jpg,jpeg,png,webp','max:5120'],
        ]);
    }
}