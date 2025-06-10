<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToHistoriesTable extends Migration
{
    public function up(): void
    {
        Schema::table('histories', function (Blueprint $table) {
            $table->index('freight_id');
            $table->index('date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('histories', function (Blueprint $table) {
            $table->dropIndex(['freight_id']);
            $table->dropIndex(['date']);
            $table->dropIndex(['status']);
        });
    }
}

