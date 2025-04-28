<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CompanyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
            User::truncate();

            User::create([
                'name' => 'Fretes em Nuvens',
                'email' => 'sebastiao.carlos.pugas@gmail.com',
                'password' => Hash::make('Cadmus@192'), // Defina a senha padrão
                'role' => 'Admin',
                'company_id' => 2 // Se tiver relação
            ]);
        
    }
}
