<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->string('channel', 20);              // email|whatsapp|sms|push
            $table->string('provider', 50)->nullable(); // npr. smtp|twilio|meta|...
            
            $table->string('to', 190)->nullable();      // email/phone/identifier
            $table->string('subject', 190)->nullable(); // za email
            
            $table->string('template', 100)->nullable(); // npr. reservation.created
            $table->json('payload')->nullable();         // data koja je poslana/koristena
            $table->text('message')->nullable();         // final body / tekst
            
            $table->string('status', 20)->default('queued'); // queued|sent|failed
            $table->text('error_message')->nullable();
            
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            
            $table->index(['channel', 'status']);
            $table->index('sent_at');
            $table->index('user_id');
            $table->index('template');
            $table->index('to');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};