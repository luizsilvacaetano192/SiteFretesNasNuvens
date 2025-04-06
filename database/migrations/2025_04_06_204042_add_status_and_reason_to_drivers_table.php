<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusAndReasonToDriversTable extends Migration
{
    public function up()
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->enum('status', ['create', 'active', 'block', 'transfer_block'])->default('create')->after('id');
            $table->string('reason')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn(['status', 'reason']);
        });
    }
}
