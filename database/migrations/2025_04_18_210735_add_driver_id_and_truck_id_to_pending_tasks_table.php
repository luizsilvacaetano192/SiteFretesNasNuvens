<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDriverIdAndTruckIdToPendingTasksTable extends Migration
{
    public function up()
    {
        Schema::table('pending_tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('driver_id')->nullable()->after('id');
            $table->unsignedBigInteger('truck_id')->nullable()->after('driver_id');

            // Adiciona as foreign keys se desejar (opcional)
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('set null');
            $table->foreign('truck_id')->references('id')->on('trucks')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('pending_tasks', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->dropForeign(['truck_id']);
            $table->dropColumn(['driver_id', 'truck_id']);
        });
    }
}

