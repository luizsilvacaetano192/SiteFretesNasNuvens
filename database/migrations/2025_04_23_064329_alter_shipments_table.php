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
        Schema::table('shipments', function (Blueprint $table) {
            
            // Cargo information
   
            $table->text('description')->nullable();
            
            // Flags
            $table->boolean('is_fragile')->default(false);
            $table->boolean('is_hazardous')->default(false);
            $table->boolean('requires_temperature_control')->default(false);
            
            // Temperature control fields
            $table->decimal('min_temperature', 5, 2)->nullable();
            $table->decimal('max_temperature', 5, 2)->nullable();
            $table->decimal('temperature_tolerance', 5, 2)->nullable();
            $table->string('temperature_control_type')->nullable();
            $table->string('temperature_unit')->default('celsius');
            $table->text('temperature_notes')->nullable();
 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
          
            
            $table->dropColumn([
                'description',
                'is_fragile',
                'is_hazardous',
                'requires_temperature_control',
                'min_temperature',
                'max_temperature',
                'temperature_tolerance',
                'temperature_control_type',
                'temperature_unit',
                'temperature_notes'
            ]);
        });
    }
};