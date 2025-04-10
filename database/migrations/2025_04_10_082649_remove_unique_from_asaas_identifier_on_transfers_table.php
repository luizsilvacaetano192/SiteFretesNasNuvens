<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transfers', function (Blueprint $table) {
            $table->dropUnique(['asaas_identifier']); // Remove o índice UNIQUE
        });
    }

    public function down(): void
    {
        Schema::table('transfers', function (Blueprint $table) {
            $table->unique('asaas_identifier'); // Reverte adicionando UNIQUE novamente
        });
    }
};
