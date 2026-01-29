<?php

namespace App\Models\Back\Notifications;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationLog extends Model
{
    protected $fillable = [
        'user_id',
        'channel',
        'provider',
        'to',
        'subject',
        'template',
        'payload',
        'message',
        'status',
        'error_message',
        'sent_at',
    ];
    
    protected $casts = [
        'payload' => 'array',
        'sent_at' => 'datetime',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}