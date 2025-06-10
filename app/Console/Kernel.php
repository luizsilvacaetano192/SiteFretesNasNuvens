<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Registra os comandos personalizados.
     */
    protected $commands = [
        \App\Console\Commands\ApiSchedulerDaemon::class, // 👈 Adicionado aqui
    ];

    /**
     * Define agendamentos (cron jobs).
     */
    protected function schedule(Schedule $schedule): void
    {
        // Nenhum cron aqui — o processo é contínuo via comando
    }

    /**
     * Carrega os comandos adicionais da aplicação.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
