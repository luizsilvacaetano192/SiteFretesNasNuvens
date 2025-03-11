<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToFreightsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('freights', function (Blueprint $table) {
            // Adicionar novos campos
            $table->string('start_address')->nullable()->after('shipment_id');
            $table->string('destination_address')->nullable()->after('start_address');
            $table->decimal('current_lat', 10, 8)->nullable()->after('current_position');
            $table->decimal('current_lng', 11, 8)->nullable()->after('current_lat');
            $table->decimal('start_lat', 10, 8)->nullable()->after('current_lng');
            $table->decimal('start_lng', 11, 8)->nullable()->after('start_lat');
            $table->decimal('destination_lat', 10, 8)->nullable()->after('start_lng');
            $table->decimal('destination_lng', 11, 8)->nullable()->after('destination_lat');
            $table->unsignedBigInteger('company_id')->nullable()->after('destination_lng');
            $table->string('truck_type')->nullable()->after('company_id');

            // Adicionar chave estrangeira para company_id
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('freights', function (Blueprint $table) {
            // Remover os campos adicionados
            $table->dropColumn([
                'start_address',
                'destination_address',
                'current_lat',
                'current_lng',
                'start_lat',
                'start_lng',
                'destination_lat',
                'destination_lng',
                'company_id',
                'truck_type',
            ]);

            // Remover a chave estrangeira
            $table->dropForeign(['company_id']);
        });
    }
}