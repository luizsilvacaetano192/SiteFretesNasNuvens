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
        Schema::table('freights', function (Blueprint $table) {
            // Data/hora de coleta e entrega
            $table->dateTime('pickup_date')->nullable();
            $table->dateTime('delivery_date')->nullable();
            $table->string('description')->nullable();
            
            // Seguradoras selecionadas
            $table->json('insurance_carriers')->nullable()->comment('Lista de seguradoras selecionadas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('freights', function (Blueprint $table) {
            $table->dropColumn([
                'pickup_date',
                'delivery_date',
                'insurance_carriers',
                'description'
            ]);
        });
    }
};