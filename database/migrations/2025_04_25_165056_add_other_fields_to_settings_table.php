<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            
            // Veículos especiais
            $table->decimal('small_refrigerated_rate', 8, 2)->default(3.00);
            $table->decimal('medium_refrigerated_rate', 8, 2)->default(3.70);
            $table->decimal('large_refrigerated_rate', 8, 2)->default(5.20);
            $table->decimal('small_tanker_rate', 8, 2)->default(3.20);
            $table->decimal('medium_tanker_rate', 8, 2)->default(4.00);
            $table->decimal('large_tanker_rate', 8, 2)->default(5.50);
            
            // Outros valores
            $table->decimal('minimum_freight_value', 8, 2)->default(150.00);
            $table->decimal('weight_surcharge_3000', 5, 2)->default(10.00);
            $table->decimal('weight_surcharge_5000', 5, 2)->default(15.00);
            $table->decimal('fragile_surcharge', 5, 2)->default(20.00);
            $table->decimal('hazardous_surcharge', 5, 2)->default(30.00);
            
          
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};