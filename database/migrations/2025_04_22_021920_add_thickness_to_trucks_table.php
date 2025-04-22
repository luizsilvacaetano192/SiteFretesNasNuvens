<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThicknessToTrucksTable extends Migration
{
    public function up()
    {
        Schema::table('trucks', function (Blueprint $table) {
            $table->string('thickness')->nullable();
            // Substitua 'some_column' pelo nome de uma coluna existente para posicionar corretamente, ou remova `->after(...)`
        });
    }

    public function down()
    {
        Schema::table('trucks', function (Blueprint $table) {
            $table->dropColumn('thickness');
        });
    }
}
