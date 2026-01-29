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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            
            $table->string('provider', 50)->default('offline'); // stripe|offline...
            $table->string('status', 30)->default('pending');   // pending|succeeded|failed|refunded
            
            $table->decimal('amount', 10, 2);
            $table->char('currency', 3)->default('EUR');
            
            $table->string('provider_ref', 120)->nullable(); // txn id
            $table->timestamp('paid_at')->nullable();
            $table->json('payload')->nullable();
            
            $table->timestamps();
            
            $table->index(['provider', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
