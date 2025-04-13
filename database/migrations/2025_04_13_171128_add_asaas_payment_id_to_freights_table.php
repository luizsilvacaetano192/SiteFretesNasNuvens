<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('freights', function (Blueprint $table) {
            $table->string('asaas_payment_id')->nullable()->after('freight_value');
        });
    }

    public function down(): void
    {
        Schema::table('freights', function (Blueprint $table) {
            $table->dropColumn('asaas_payment_id');
        });
    }
};

