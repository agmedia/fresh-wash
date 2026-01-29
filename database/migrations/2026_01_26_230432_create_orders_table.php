<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            $table->string('code', 40)->unique(); // ORD-2026-000001
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('reservation_id')->nullable()->constrained('reservations')->nullOnDelete();
            
            $table->string('status', 30)->default('pending'); // pending|paid|cancelled|refunded
            $table->char('currency', 3)->default('EUR');
            
            $table->decimal('total_net', 10, 2)->default(0);
            $table->decimal('vat_rate', 5, 2)->default(25.00);
            $table->decimal('total_gross', 10, 2)->default(0);
            
            $table->text('note')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
