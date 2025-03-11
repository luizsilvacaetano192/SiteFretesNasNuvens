<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->decimal('latitude', 10, 6)->nullable(); // Removendo a referência à coluna 'existing_column'
            $table->decimal('longitude', 10, 6)->nullable();
            $table->decimal('time', 8, 2)->nullable();
            $table->decimal('distance', 8, 2)->nullable();
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
            $table->dropColumn(['latitude', 'longitude', 'time', 'distance']);
        });
    }
}
