<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('device_commands', function (Blueprint $table) {
            $table->id();

            $table->foreignId('device_id')->constrained('devices')->cascadeOnDelete();
            $table->foreignId('locker_id')->nullable()->constrained('lockers')->nullOnDelete();
            $table->foreignId('reservation_id')->nullable()->constrained('reservations')->nullOnDelete();
            $table->foreignId('requested_by_user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('type', 50); // open_locker, ...
            $table->json('payload')->nullable();

            $table->string('status', 20)->default('pending'); // pending|sent|acked|failed
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('acked_at')->nullable();
            $table->string('ack_code', 50)->nullable();     // npr. ok, timeout, jammed
            $table->text('ack_message')->nullable();

            $table->timestamps();

            $table->index(['device_id', 'status']);
            $table->index(['reservation_id']);
            $table->index(['locker_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_commands');
    }
};