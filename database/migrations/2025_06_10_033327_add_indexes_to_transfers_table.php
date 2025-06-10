<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToTransfersTable extends Migration
{
    public function up()
    {
        Schema::table('transfers', function (Blueprint $table) {
            $table->index('user_account_id');
            $table->index('freight_id');
            $table->index('type');
            $table->index('transfer_date');
        });
    }

    public function down()
    {
        Schema::table('transfers', function (Blueprint $table) {
            $table->dropIndex(['user_account_id']);
            $table->dropIndex(['freight_id']);
            $table->dropIndex(['type']);
            $table->dropIndex(['transfer_date']);
        });
    }
}

