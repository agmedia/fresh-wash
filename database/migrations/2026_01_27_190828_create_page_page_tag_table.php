<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('page_page_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
            $table->foreignId('page_tag_id')->constrained('page_tags')->cascadeOnDelete();
            $table->timestamps();
            
            $table->unique(['page_id', 'page_tag_id']);
            $table->index(['page_id']);
            $table->index(['page_tag_id']);
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('page_page_tag');
    }
};