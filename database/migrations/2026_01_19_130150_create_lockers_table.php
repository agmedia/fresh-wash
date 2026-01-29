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
        Schema::create('lockers', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('location_id')
                ->constrained()
                ->cascadeOnDelete();
            
            $table->string('code', 50);          // npr. L-001, A12
            $table->string('name', 150)->nullable();
            $table->string('slug', 180)->unique();
            
            $table->enum('size', ['S', 'M', 'L', 'XL'])->default('M');
            
            $table->boolean('is_active')->default(true);
            $table->boolean('is_available')->default(true);
            
            $table->string('hardware_uid', 100)->nullable(); // ID uređaja / locka
            $table->text('note')->nullable();
            
            $table->timestamps();
            
            $table->unique(['location_id', 'code']);
            $table->index(['is_active', 'is_available']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lockers');
    }
};
