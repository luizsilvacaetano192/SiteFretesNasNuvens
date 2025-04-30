<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FreightStatusSeeder extends Seeder
{
    public function run(): void
    {
        // Apaga todos os registros da tabela
        DB::table('freight_statuses')->truncate();

        // Insere os dados com id e nome
        DB::table('freight_statuses')->insert([
            ['id' => 1, 'nome' => 'Carga cadastrada'],
            ['id' => 2, 'nome' => 'Frete Solicitado'],
            ['id' => 3, 'nome' => 'Aguardando pagamento'],
            ['id' => 4, 'nome' => 'Aguardando motorista'],
            ['id' => 5, 'nome' => 'Aguardando retirada'],
            ['id' => 6, 'nome' => 'Indo retirar carga'],
            ['id' => 7, 'nome' => 'Em processo de entrega'],
            ['id' => 8, 'nome' => 'Carga entregue'],
            ['id' => 9, 'nome' => 'Aguardando Aprovação empresa'],
            ['id' => 10, 'nome' => 'Frete recusado'],
        ]);
    }
}
