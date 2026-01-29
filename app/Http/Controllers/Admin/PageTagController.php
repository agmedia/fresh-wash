<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Back\Pages\PageTag;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageTagController extends Controller
{
    public function index(Request $request)
    {
        $tags = PageTag::query()
            ->when($request->search, fn($q,$s)=>$q->where('name','like',"%{$s}%")->orWhere('slug','like',"%{$s}%"))
            ->orderBy('name')
            ->paginate(30)
            ->withQueryString();
        
        return Inertia::render('admin/pages/tags/Index', [
            'tags' => $tags,
            'filters' => ['search' => $request->search],
        ]);
    }
    
    public function create()
    {
        return Inertia::render('admin/pages/tags/Create');
    }
    
    public function store(Request $request)
    {
        $tag = PageTag::create($this->validated($request));
        return redirect()->route('admin.page-tags.edit', $tag)->with('success','Tag kreiran.');
    }
    
    public function edit(PageTag $pageTag)
    {
        return Inertia::render('admin/pages/tags/Edit', ['tag' => $pageTag]);
    }
    
    public function update(Request $request, PageTag $pageTag)
    {
        $pageTag->update($this->validated($request, $pageTag->id));
        return redirect()->route('admin.page-tags.edit', $pageTag)->with('success','Tag ažuriran.');
    }
    
    private function validated(Request $request, ?int $id=null): array
    {
        return $request->validate([
            'name' => ['required','string','max:100'],
            'slug' => ['required','string','max:190','unique:page_tags,slug,' . $id],
        ]);
    }
}