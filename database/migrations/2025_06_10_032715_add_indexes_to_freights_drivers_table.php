<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToFreightsDriversTable extends Migration
{
    public function up(): void
    {
        Schema::table('freights_drivers', function (Blueprint $table) {
            $table->index('freight_id');
            $table->index('driver_id');
            $table->index('truck_id');
            $table->index('status_id');
        });
    }

    public function down(): void
    {
        Schema::table('freights_drivers', function (Blueprint $table) {
            $table->dropIndex(['freight_id']);
            $table->dropIndex(['driver_id']);
            $table->dropIndex(['truck_id']);
            $table->dropIndex(['status_id']);
        });
    }
}

