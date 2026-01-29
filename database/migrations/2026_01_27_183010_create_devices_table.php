<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();
            
            $table->string('code', 50)->unique();              // npr. DEV-001
            $table->string('name', 150)->nullable();
            
            $table->string('hardware_uid', 100)->unique();     // jedinstveni UID MCU-a
            $table->string('model', 100)->nullable();          // npr. ESP32
            $table->string('firmware_version', 50)->nullable();
            
            $table->string('ip_address', 45)->nullable();
            $table->string('mac_address', 50)->nullable();
            
            $table->timestamp('last_seen_at')->nullable();     // heartbeat
            $table->string('status', 20)->default('offline');  // online/offline/maintenance
            
            $table->boolean('is_active')->default(true);
            $table->text('note')->nullable();
            
            $table->timestamps();
            
            $table->index(['location_id', 'status']);
            $table->index('last_seen_at');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};