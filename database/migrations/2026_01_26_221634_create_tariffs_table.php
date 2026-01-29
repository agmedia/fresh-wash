<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tariffs', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->foreignId('location_id')->constrained('locations')->cascadeOnDelete();
            
            $table->decimal('price_net', 10, 2);
            $table->decimal('vat_rate', 5, 2)->default(25.00);
            $table->decimal('price_gross', 10, 2);
            
            $table->char('currency', 3)->default('EUR');
            $table->boolean('is_active')->default(true);
            
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            
            $table->timestamps();
            
            // za sada 1 tarifa po service+location
            $table->unique(['service_id', 'location_id']);
            $table->index(['location_id', 'service_id']);
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('tariffs');
    }
};