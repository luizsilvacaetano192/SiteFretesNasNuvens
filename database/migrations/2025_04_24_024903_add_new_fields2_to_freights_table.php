<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('freights', function (Blueprint $table) {

            $table->string('loading_instructions', 255)->nullable();
            $table->string('unloading_instructions', 255)->nullable()->after('loading_instructions');
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('freights', function (Blueprint $table) {
            $table->dropColumn([
           
                'loading_instructions',
                'unloading_instructions'
                
            ]);
        });
    }
};