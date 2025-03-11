<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUnnecessaryColumnsFromShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipments', function (Blueprint $table) {
            // Removendo os campos desnecessários
            $table->dropColumn('truck_type');
            $table->dropColumn('start_address');
            $table->dropColumn('destination_address');
            $table->dropColumn('expected_start_date');
            $table->dropColumn('expected_delivery_date');
            $table->dropColumn('deadline');
            $table->dropColumn('start_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipments', function (Blueprint $table) {
            // Revertendo as remoções, caso necessário
            $table->string('truck_type')->nullable();
            $table->string('start_address')->nullable();
            $table->string('destination_address')->nullable();
            $table->dateTime('expected_start_date')->nullable();
            $table->dateTime('expected_delivery_date')->nullable();
            $table->integer('deadline')->nullable()->comment('In hours or days');
            $table->time('start_time')->nullable();
             // Adicionando os campos de latitude e longitude de início e destino
             $table->decimal('start_latitude', 10, 6)->nullable()->after('start_address');
             $table->decimal('start_longitude', 10, 6)->nullable()->after('start_latitude');
             $table->decimal('destination_latitude', 10, 6)->nullable()->after('destination_address');
             $table->decimal('destination_longitude', 10, 6)->nullable()->after('destination_latitude');
             
        });
    }
}
