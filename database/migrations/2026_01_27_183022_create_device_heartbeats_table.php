<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('device_heartbeats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('devices')->cascadeOnDelete();
            
            $table->timestamp('reported_at')->index();
            $table->string('firmware_version', 50)->nullable();
            
            $table->unsignedSmallInteger('rssi')->nullable();
            $table->unsignedSmallInteger('battery')->nullable(); // % ako ikad bude
            $table->unsignedInteger('uptime_sec')->nullable();
            
            $table->json('payload')->nullable();
            $table->timestamps();
            
            $table->index(['device_id', 'reported_at']);
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('device_heartbeats');
    }
};