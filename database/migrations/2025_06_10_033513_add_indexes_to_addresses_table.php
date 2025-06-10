<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToAddressesTable extends Migration
{
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->index('address');
            // Para latitude e longitude, índices simples (não tão eficientes para buscas geográficas)
            $table->index('latitude');
            $table->index('longitude');
        });
    }

    public function down()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropIndex(['address']);
            $table->dropIndex(['latitude']);
            $table->dropIndex(['longitude']);
        });
    }
}
