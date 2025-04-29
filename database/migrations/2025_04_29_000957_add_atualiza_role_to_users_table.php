<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // adicionar se precisar

return new class extends Migration
{
    public function up(): void
    {
        // Primeiro, remover o campo role, se existir
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }

        // Depois, adicionar novamente o campo role corretamente
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'company', 'driver'])->default('company')->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
