<?php

namespace App\Http\Controllers\Front;

use Inertia\Inertia;

class BlogController
{
    public function index()
    {
        return Inertia::render('front/info/Blog/Index');
    }
    
    public function show(string $slug)
    {
        return Inertia::render('front/info/Blog/Show', compact('slug'));
    }
    
    public function category(string $slug)
    {
        return Inertia::render('front/info/Blog/Category', compact('slug'));
    }
    
    public function tag(string $slug)
    {
        return Inertia::render('front/info/Blog/Tag', compact('slug'));
    }
}