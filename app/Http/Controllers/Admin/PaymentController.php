<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Back\Orders\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::query()
            ->with([
                'order:id,code,user_id',
                'order.user:id,email,first_name,last_name,name',
            ])
            ->when($request->search, function ($q, $s) {
                $q->where('provider', 'like', "%{$s}%")
                    ->orWhere('status', 'like', "%{$s}%")
                    ->orWhere('provider_ref', 'like', "%{$s}%")
                    ->orWhereHas('order', fn ($oq) => $oq->where('code', 'like', "%{$s}%"))
                    ->orWhereHas('order.user', fn ($uq) => $uq->where('email', 'like', "%{$s}%"));
            })
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();
        
        return Inertia::render('admin/payments/Index', [
            'payments' => $payments,
            'filters' => ['search' => $request->search],
        ]);
    }
    
    public function show(Payment $payment)
    {
        $payment->load([
            'order:id,code,user_id',
            'order.user:id,email,first_name,last_name,name',
        ]);
        
        return Inertia::render('admin/payments/Show', [
            'payment' => $payment,
        ]);
    }
    
    public function edit(Payment $payment)
    {
        $payment->load([
            'order:id,code,user_id',
            'order.user:id,email,first_name,last_name,name',
        ]);
        
        return Inertia::render('admin/payments/Edit', [
            'payment' => $payment,
            'statuses' => ['pending', 'succeeded', 'failed', 'refunded'],
            'providers' => ['offline', 'stripe'],
        ]);
    }
    
    public function update(Request $request, Payment $payment)
    {
        $data = $request->validate([
            'provider' => ['required', 'string', 'max:50'],
            'status' => ['required', 'in:pending,succeeded,failed,refunded'],
            
            'amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            
            'provider_ref' => ['nullable', 'string', 'max:120'],
            'paid_at' => ['nullable', 'date'],
            
            // ako šalješ iz Vue textarea kao string, ostavi nullable string,
            // a kasnije pretvori u JSON. Za sada: array ili null.
            'payload' => ['nullable', 'array'],
        ]);
        
        $payment->update($data);
        
        return redirect("/admin/payments/{$payment->id}/edit")
            ->with('success', 'Plaćanje ažurirano.');
    }
}