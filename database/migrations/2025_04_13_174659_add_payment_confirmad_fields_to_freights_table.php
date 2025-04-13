<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('freights', function (Blueprint $table) {
       
            $table->boolean('is_payment_confirmed')->default(false)->after('asaas_payment_id');
        });
    }

    public function down(): void
    {
        Schema::table('freights', function (Blueprint $table) {
            $table->boolean('is_payment_confirmed')->default(false)->after('asaas_payment_id');
        });
    }
};
