<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            // Campos existentes (não precisam ser alterados)
            // 'name', 'cnpj', 'phone', 'email', 'address'
            
            // Informações adicionais da empresa
            $table->string('trading_name')->nullable()->after('name');
            $table->string('state_registration')->nullable()->after('cnpj');
            $table->text('description')->nullable();
            $table->string('website')->nullable();
            
            // Campos de endereço completos
            $table->string('number')->after('address');
            $table->string('complement')->nullable()->after('number');
            $table->string('neighborhood')->after('complement');
            $table->string('city')->after('neighborhood');
            $table->char('state', 2)->after('city');
            $table->string('zip_code', 9)->after('state');
            
            // Contatos adicionais
            $table->string('whatsapp')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'trading_name',
                'state_registration',
                'description',
                'website',
                'number',
                'complement',
                'neighborhood',
                'city',
                'state',
                'zip_code',
                'whatsapp'
            ]);
        });
    }
};