<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Back\Orders\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::query()
            ->with([
                'user:id,email,first_name,last_name,name',
                'location:id,name',
            ])
            ->when($request->search, function ($q, $s) {
                $q->where('code', 'like', "%{$s}%")
                    ->orWhere('status', 'like', "%{$s}%")
                    ->orWhereHas('user', fn ($uq) => $uq
                        ->where('email', 'like', "%{$s}%")
                        ->orWhere('first_name', 'like', "%{$s}%")
                        ->orWhere('last_name', 'like', "%{$s}%")
                        ->orWhere('name', 'like', "%{$s}%")
                    )
                    ->orWhereHas('location', fn ($lq) => $lq->where('name', 'like', "%{$s}%"));
            })
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();
        
        return Inertia::render('admin/orders/Index', [
            'orders' => $orders,
            'filters' => ['search' => $request->search],
        ]);
    }
    
    public function show(Order $order)
    {
        $order->load([
            'user:id,email,first_name,last_name,name',
            'location:id,name',
            'payments',
        ]);
        
        return Inertia::render('admin/orders/Show', [
            'order' => $order,
        ]);
    }
    
    public function edit(Order $order)
    {
        $order->load([
            'user:id,email,first_name,last_name,name',
            'location:id,name',
        ]);
        
        return Inertia::render('admin/orders/Edit', [
            'order' => $order,
            'statuses' => ['pending', 'paid', 'cancelled', 'refunded'],
        ]);
    }
    
    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,paid,cancelled,refunded'],
            'note' => ['nullable', 'string'],
            
            'currency' => ['required', 'string', 'size:3'],
            'total_net' => ['required', 'numeric', 'min:0'],
            'vat_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'total_gross' => ['required', 'numeric', 'min:0'],
        ]);
        
        $order->update($data);
        
        return redirect("/admin/orders/{$order->id}/edit")
            ->with('success', 'Narudžba ažurirana.');
    }
}