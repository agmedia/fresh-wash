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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('locker_id')->constrained()->cascadeOnDelete();
            
            $table->string('status', 30)->default('active'); // draft|active|ended|cancelled
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            
            $table->decimal('price', 10, 2)->nullable();
            $table->string('currency_code', 3)->default('EUR');
            
            $table->text('note')->nullable();
            
            $table->timestamps();
            
            $table->index(['status']);
            $table->index(['starts_at']);
            $table->index(['ends_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
