<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Back\Notifications\NotificationLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NotificationLogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->toString();
        $channel = $request->string('channel')->toString();
        $status = $request->string('status')->toString();
        
        $logs = NotificationLog::query()
            ->with('user:id,email,name,first_name,last_name')
            ->when($channel, fn ($q) => $q->where('channel', $channel))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('to', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%")
                        ->orWhere('template', 'like', "%{$search}%")
                        ->orWhere('message', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();
        
        return Inertia::render('admin/notifications/Index', [
            'logs' => $logs,
            'filters' => [
                'search' => $search ?: null,
                'channel' => $channel ?: null,
                'status' => $status ?: null,
            ],
            'channels' => ['email', 'whatsapp', 'sms', 'push'],
            'statuses' => ['queued', 'sent', 'failed'],
        ]);
    }
    
    public function show(NotificationLog $notification)
    {
        return Inertia::render('admin/notifications/Show', [
            'log' => $notification->load('user:id,email,name,first_name,last_name'),
        ]);
    }
}