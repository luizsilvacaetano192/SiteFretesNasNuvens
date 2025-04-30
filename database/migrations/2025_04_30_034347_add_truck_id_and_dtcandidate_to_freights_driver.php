<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTruckIdAndDtcandidateToFreightsDriver extends Migration
{
    public function up()
    {
        Schema::table('freights_driver', function (Blueprint $table) {
            $table->unsignedBigInteger('truck_id')->nullable()->after('id');
            $table->dateTime('dtstart')->nullable();
            $table->dateTime('dtend')->nullable();
        });
    }

    public function down()
    {
        Schema::table('freights_driver', function (Blueprint $table) {
            $table->dropColumn(['truck_id', 'dtstart', 'dtend']);
        });
    }
}
