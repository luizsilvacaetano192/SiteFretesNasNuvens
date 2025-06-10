<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToUsersTable extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index('email');   // Busca rápida por e-mail (ex: login)
            $table->index('cnpj');    // Busca por CNPJ (empresas)
            $table->index('role');    // Filtros por tipo de usuário
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->dropIndex(['cnpj']);
            $table->dropIndex(['role']);
        });
    }
}
