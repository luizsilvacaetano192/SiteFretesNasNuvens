<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->date('date'); // Data do evento
            $table->time('time'); // Hora do evento
            $table->string('address'); // Endereço
            $table->decimal('latitude', 10, 7); // Latitude
            $table->decimal('longitude', 10, 7); // Longitude
            $table->foreignId('freight_id')->constrained('freights')->onDelete('cascade'); // Chave estrangeira para fretes
            $table->enum('status', ['pending', 'in_progress', 'completed', 'canceled']); // Status do evento
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
