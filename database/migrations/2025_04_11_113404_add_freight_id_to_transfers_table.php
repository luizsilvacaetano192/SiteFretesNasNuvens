<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFreightIdToTransfersTable extends Migration
{
    public function up()
    {
        Schema::table('transfers', function (Blueprint $table) {
            $table->unsignedBigInteger('freight_id')->nullable()->after('id');
            $table->foreign('freight_id')->references('id')->on('freights')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('transfers', function (Blueprint $table) {
            $table->dropForeign(['freight_id']);
            $table->dropColumn('freight_id');
        });
    }
}

