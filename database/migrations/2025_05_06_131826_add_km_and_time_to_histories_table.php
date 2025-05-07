<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('histories', function (Blueprint $table) {
            $table->float('km')->nullable();
            $table->time('time_end')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('histories', function (Blueprint $table) {
            $table->dropColumn(['km', 'time_endf']);
        });
    }
};
