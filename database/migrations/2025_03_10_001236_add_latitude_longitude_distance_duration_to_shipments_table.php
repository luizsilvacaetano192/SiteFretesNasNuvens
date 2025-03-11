<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLatitudeLongitudeDistanceDurationToShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipments', function (Blueprint $table) {
            // Adicionando novos campos
            $table->decimal('start_latitude', 10, 6)->nullable()->after('start_address');
            $table->decimal('start_longitude', 10, 6)->nullable()->after('start_latitude');
            $table->decimal('destination_latitude', 10, 6)->nullable()->after('destination_address');
            $table->decimal('destination_longitude', 10, 6)->nullable()->after('destination_latitude');
            $table->decimal('duration', 8, 2)->nullable()->after('distance');
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
            // Removendo os campos caso a migration seja revertida
            $table->dropColumn(['start_latitude', 'start_longitude', 'destination_latitude', 'destination_longitude', 'distance', 'duration']);
        });
    }
}
