<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToMessagesPushTable extends Migration
{
    public function up()
    {
        Schema::table('messages_push', function (Blueprint $table) {
            $table->index('driver_id');
            $table->index('token');
            $table->index('send');
            $table->index('data');
            $table->index('type');
            $table->index('screen');
            $table->index('erro');
        });
    }

    public function down()
    {
        Schema::table('messages_push', function (Blueprint $table) {
            $table->dropIndex(['driver_id']);
            $table->dropIndex(['token']);
            $table->dropIndex(['send']);
            $table->dropIndex(['data']);
            $table->dropIndex(['type']);
            $table->dropIndex(['screen']);
            $table->dropIndex(['erro']);
        });
    }
}

