<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name', 100)->nullable()->after('id');
            $table->string('last_name', 100)->nullable()->after('first_name');
            
            $table->string('address_line1', 255)->nullable()->after('email');
            $table->string('address_line2', 255)->nullable()->after('address_line1');
            $table->string('postal_code', 20)->nullable()->after('address_line2');
            $table->string('city', 100)->nullable()->after('postal_code');
            $table->string('country_code', 2)->default('HR')->after('city');
            
            $table->string('phone', 50)->nullable()->after('country_code');
            $table->boolean('whatsapp_opt_in')->default(false)->after('phone');
        });
    }
    
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
                'address_line1',
                'address_line2',
                'postal_code',
                'city',
                'country_code',
                'phone',
                'whatsapp_opt_in',
            ]);
        });
    }
};