<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->string('token')->nullable();
            $table->string('token_push')->nullable()->after('token');
            $table->string('token_sass')->nullable()->after('token_push');
        });
    }

    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn(['token', 'token_push', 'token_sass']);
        });
    }
};
