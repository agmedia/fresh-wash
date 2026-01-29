<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('category_id')->nullable()->constrained('page_categories')->nullOnDelete();
            
            $table->string('title', 190);
            $table->string('slug', 190)->unique();
            
            $table->longText('body')->nullable();
            
            $table->string('meta_title', 190)->nullable();
            $table->string('meta_description', 255)->nullable();
            
            $table->string('type', 20)->default('page'); // page|post
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            $table->index(['type', 'is_active']);
            $table->index('published_at');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};