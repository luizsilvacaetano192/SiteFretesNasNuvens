<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('cloud_percentage', 5, 2)->default(10.00)->comment('Percentual da plataforma sobre o valor do frete');
            $table->decimal('advance_percentage', 5, 2)->default(30.00)->comment('Percentual de adiantamento para o motorista');
            $table->decimal('small_truck_price', 8, 2)->default(2.50)->comment('Valor por km para caminhão pequeno');
            $table->decimal('medium_truck_price', 8, 2)->default(3.20)->comment('Valor por km para caminhão médio');
            $table->decimal('large_truck_price', 8, 2)->default(4.50)->comment('Valor por km para caminhão grande');
            $table->timestamps();
        });

        // Insere um registro padrão
        DB::table('settings')->insert([
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};