<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToDriversTable extends Migration
{
    public function up(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->index('cpf');
            $table->index('identity_card');
            $table->index('status');
            $table->index('phone');
            $table->index('driver_license_number');
        });
    }

    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropIndex(['cpf']);
            $table->dropIndex(['identity_card']);
            $table->dropIndex(['status']);
            $table->dropIndex(['phone']);
            $table->dropIndex(['driver_license_number']);
        });
    }
}
