<?php

namespace App\Http\Controllers\Front;

use App\Models\Back\Orders\Order;
use Inertia\Inertia;

class OrderController
{
    public function index()
    {
        $user = auth()->user();

        $base = Order::query()
                     ->where('user_id', $user->id)
                     ->with([
                         'location:id,name',
                         'locker:id,code,name',   // ili 'machine' ako si preimenovao relaciju
                         'service:id,name',
                     ])
                     ->latest();

        $pending = (clone $base)
            ->whereIn('status', ['pending'])
            ->take(50)
            ->get()
            ->map(fn ($o) => [
                'id' => $o->id,
                'code' => $o->code,
                'status' => $o->status,
                'total_gross' => $o->total_gross,
                'currency' => $o->currency,
                'created_at' => optional($o->created_at)->format('d.m.Y H:i'),
                'location' => $o->location ? ['name' => $o->location->name] : null,
                'locker' => $o->locker ? ['code' => $o->locker->code, 'name' => $o->locker->name] : null,
                'service' => $o->service ? ['name' => $o->service->name] : null,
            ]);

        $paid = (clone $base)
            ->whereIn('status', ['paid', 'completed'])
            ->take(50)
            ->get()
            ->map(fn ($o) => [
                'id' => $o->id,
                'code' => $o->code,
                'status' => $o->status,
                'total_gross' => $o->total_gross,
                'currency' => $o->currency,
                'created_at' => optional($o->created_at)->format('d.m.Y H:i'),
                'location' => $o->location ? ['name' => $o->location->name] : null,
                'locker' => $o->locker ? ['code' => $o->locker->code, 'name' => $o->locker->name] : null,
                'service' => $o->service ? ['name' => $o->service->name] : null,
            ]);

        return Inertia::render('front/app/orders/Index', [
            'pending' => $pending,
            'paid' => $paid,
        ]);
    }
    
    public function create()
    {
        return Inertia::render('front/app/orders/Create');
    }
    
    public function show(int $orderId)
    {
        return Inertia::render('front/app/orders/Show', [
            'orderId' => $orderId,
        ]);
    }
}