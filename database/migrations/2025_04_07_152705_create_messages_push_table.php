<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesPushTable extends Migration
{
    public function up()
    {
        Schema::create('messages_push', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id')->nullable(); // ID do motorista
            $table->text('texto');       // Texto da mensagem
            $table->string('token')->nullable();  // Token FCM
            $table->string('link')->nullable();   // Link opcional (para abrir algo no app)
            $table->dateTime('data')->nullable(); // Quando foi enviada
            $table->boolean('send')->default(false); // Se foi enviada com sucesso
            $table->text('erro')->nullable();     // Mensagem de erro, se houver
            $table->string('type')->nullable();   // Tipo da mensagem (ex: alerta, info, etc.)
            $table->timestamps();

            // Chave estrangeira para motoristas
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages_push');
    }
}
