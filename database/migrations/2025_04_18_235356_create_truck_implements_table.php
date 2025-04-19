<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('truck_implements', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('license_plate')->nullable();
            $table->string('manufacture_year')->nullable();
            $table->string('capacity')->nullable();
            $table->string('photo')->nullable(); // base64 pode ser longo, por isso longText
            $table->unsignedBigInteger('truck_id')->nullable();

            $table->foreign('truck_id')->references('id')->on('trucks')->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('truck_implements');
    }
};

