<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToFreightsTable extends Migration
{
    public function up(): void
    {
        Schema::table('freights', function (Blueprint $table) {
            $table->index('shipment_id');
            $table->index('company_id');
            $table->index('status_id');
            $table->index('pickup_date');
            $table->index('delivery_date');
            $table->index('truck_type');
            $table->index(['start_lat', 'start_lng']);
            $table->index(['destination_lat', 'destination_lng']);
        });
    }

    public function down(): void
    {
        Schema::table('freights', function (Blueprint $table) {
            $table->dropIndex(['shipment_id']);
            $table->dropIndex(['company_id']);
            $table->dropIndex(['status_id']);
            $table->dropIndex(['pickup_date']);
            $table->dropIndex(['delivery_date']);
            $table->dropIndex(['truck_type']);
            $table->dropIndex(['start_lat', 'start_lng']);
            $table->dropIndex(['destination_lat', 'destination_lng']);
        });
    }
}
