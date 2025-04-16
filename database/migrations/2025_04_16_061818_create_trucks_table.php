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
        Schema::create('trucks', function (Blueprint $table) {
            $table->id();
            
            // Relacionamento com motorista
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->foreign('driver_id')
                  ->references('id')
                  ->on('drivers')
                  ->onDelete('set null');
            
            // Dados básicos
            $table->string('license_plate', 7)->unique(); // Placa (ABC1D23)
            $table->string('brand'); // Marca
            $table->string('model'); // Modelo
            $table->year('manufacture_year'); // Ano de fabricação
            
            // Documentos
            $table->string('renavam', 11)->unique(); // RENAVAM
            $table->string('chassis_number', 17)->unique(); // Chassi
            $table->string('crv_number'); // Número do CRV
            $table->string('crlv_number'); // Número do CRLV
            
            // Especificações técnicas
            $table->string('vehicle_type'); // Tipo de veículo
            $table->decimal('load_capacity', 10, 2); // Capacidade de carga (kg)
            $table->integer('axles_number'); // Quantidade de eixos
            $table->decimal('tare', 10, 2); // Tara (kg)
            $table->decimal('gross_weight', 10, 2); // Peso Bruto Total - PBT (kg)
            
            // Carroceria
            $table->string('body_type'); // Tipo de carroceria
            $table->string('body_material'); // Material da carroceria
            $table->string('dimensions'); // Dimensões (L x A x C em metros)
            
            // Fotos do caminhão (links para o bucket AWS)
            $table->string('front_photo_url')->nullable(); // Foto dianteira
            $table->string('rear_photo_url')->nullable(); // Foto traseira
            $table->string('left_side_photo_url')->nullable(); // Foto lateral esquerda
            $table->string('right_side_photo_url')->nullable(); // Foto lateral direita
            
            // Documentos fotográficos (links para o bucket AWS)
            $table->string('crv_photo_url')->nullable(); // Foto do CRV
            $table->string('crlv_photo_url')->nullable(); // Foto do CRLV
            
            // Status e controle
            $table->boolean('active')->default(false); // Ativo/inativo
            $table->timestamps();
            $table->softDeletes(); // Para exclusão lógica
            
            // Índices
            $table->index('license_plate');
            $table->index('renavam');
            $table->index('chassis_number');
            $table->index('active');
            $table->index('driver_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trucks');
    }
};