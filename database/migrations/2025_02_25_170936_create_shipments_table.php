<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->engine = 'InnoDB'; // Definir o engine como InnoDBs
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('driver_id');
          
            $table->decimal('weight', 10, 2);
            $table->string('cargo_type');
            $table->string('dimensions')->nullable();
            $table->decimal('volume', 10, 2)->nullable();
            $table->string('truck_type');
            $table->string('start_address');
            $table->string('destination_address');
            $table->dateTime('expected_start_date');
            $table->dateTime('expected_delivery_date');
            $table->integer('deadline')->comment('In hours or days');
            $table->time('start_time');
            $table->timestamps();

           
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};

