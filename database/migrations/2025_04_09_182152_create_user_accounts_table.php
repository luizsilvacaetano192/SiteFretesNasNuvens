<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAccountsTable extends Migration
{
    public function up()
    {
        Schema::create('user_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id');
            $table->string('asaas_identifier')->nullable();
            $table->decimal('total_balance', 10, 2)->default(0);
            $table->decimal('blocked_balance', 10, 2)->default(0);
            $table->decimal('available_balance', 10, 2)->default(0);
            $table->dateTime('last_updated_at')->nullable();
            $table->timestamps();

            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_accounts');
    }
}
