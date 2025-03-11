<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMigrateAndDurationToFreights extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('freights', function (Blueprint $table) {
            // Adiciona o campo migrate (BOOLEAN) e define o valor padrão
            $table->string('distance')->nullable();

            // Adiciona o campo duration (INTEGER) e permite valores nulos
            $table->string('duration')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('freights', function (Blueprint $table) {
            // Remove os campos migrate e duration
            $table->dropColumn('distance');
            $table->dropColumn('duration');
        });
    }
}
