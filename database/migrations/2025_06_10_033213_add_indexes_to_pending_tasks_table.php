<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToPendingTasksTable extends Migration
{
    public function up()
    {
        Schema::table('pending_tasks', function (Blueprint $table) {
            $table->index('scheduled_at');
            $table->index('seen');
        });
    }

    public function down()
    {
        Schema::table('pending_tasks', function (Blueprint $table) {
            $table->dropIndex(['scheduled_at']);
            $table->dropIndex(['seen']);
        });
    }
}
