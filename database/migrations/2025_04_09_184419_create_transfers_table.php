<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_account_id');
            $table->string('type');
            $table->decimal('amount', 10, 2);
            $table->string('description')->nullable();
            $table->date('transfer_date');
            $table->string('asaas_identifier')->unique();
            $table->timestamps();

            $table->foreign('user_account_id')
                  ->references('id')
                  ->on('user_accounts')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
}
