<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('charges', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('freight_id');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->nullable();
            $table->string('asaas_charge_id')->nullable();
            $table->string('asaas_payment_id')->nullable();

            $table->enum('status', ['unpaid', 'paid', 'canceled'])->default('unpaid');

            $table->string('charge_url')->nullable();
            $table->string('receipt_url')->nullable();

            $table->timestamps();

            $table->foreign('freight_id')->references('id')->on('freights')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charges');
    }
}
