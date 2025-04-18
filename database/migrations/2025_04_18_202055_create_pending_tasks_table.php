<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      
        Schema::create('pending_tasks', function (Blueprint $table) {
            $table->id();
            $table->text('message'); // campo mensagem
            $table->dateTime('scheduled_at'); // campo data com hora
            $table->boolean('seen')->default(false); // campo visto padrão false
            $table->timestamps(); // created_at e updated_at
        });
     
        
    }

    public function down()
    {
        Schema::dropIfExists('pending_tasks');
    }
    
};
