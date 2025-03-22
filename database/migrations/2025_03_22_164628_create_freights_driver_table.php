<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFreightsDriverTable extends Migration
{
    /**
     * Executa as migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('freights_driver', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id');
            $table->unsignedBigInteger('freight_id');
            $table->unsignedBigInteger('status_id');
            $table->timestamps();

            // Chaves estrangeiras
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
            $table->foreign('freight_id')->references('id')->on('freights')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('freight_statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverte as migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('freights_driver');
    }
}