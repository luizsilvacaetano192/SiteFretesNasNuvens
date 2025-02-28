<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFreightsTableAddDriverId extends Migration
{
    public function up()
    {
        Schema::table('freights', function (Blueprint $table) {
            $table->unsignedBigInteger('driver_id')->nullable()->after('status_id')->change(); // Adiciona o campo 'driver_id' após o campo 'status_id'
        });
    }

    public function down()
    {
        Schema::table('freights', function (Blueprint $table) {
            $table->dropColumn('driver_id'); // Remove a coluna 'driver_id' caso a migração seja revertida
        });
    }
}
