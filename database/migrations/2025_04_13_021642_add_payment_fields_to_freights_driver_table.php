<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class AddPaymentFieldsToFreightsDriverTable extends Migration
{
    public function up()
    {
        Schema::table('freights_driver', function (Blueprint $table) {
            $table->decimal('freight_value', 10, 2)->nullable();
            $table->decimal('driver_freight_value', 10, 2)->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_link')->nullable();
            $table->boolean('first_payment')->default(false)->nullable();
            $table->boolean('second_payment')->default(false)->nullable();
            $table->decimal('first_payment_value', 10, 2)->nullable();
            $table->decimal('second_payment_value', 10, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('freights_driver', function (Blueprint $table) {
            $table->dropColumn([
                'freight_value',
                'driver_freight_value',
                'payment_method',
                'payment_link',
                'first_payment',
                'second_payment',
                'first_payment_value',
                'second_payment_value',
            ]);
        });
    }
}

