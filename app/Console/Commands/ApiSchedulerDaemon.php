<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ApiSchedulerDaemon extends Command
{
    protected $signature = 'scheduler:api-daemon';
    protected $description = 'Run a daemon that calls an API every 5 minutes with a 1-minute timeout';

    public function handle()
    {
        $lockKey = 'api-scheduler-daemon-lock';
        $pidKey = 'api-scheduler-daemon-pid';

        // Verifica se há um processo anterior e tenta matá-lo
        if (Cache::has($pidKey)) {
            $oldPid = Cache::get($pidKey);

            if ($this->isProcessRunning($oldPid)) {
                $this->warn("Matando instância anterior com PID $oldPid...");
                posix_kill($oldPid, SIGTERM); // ou SIGKILL se necessário
                sleep(2); // espera o processo morrer
            }

            Cache::forget($pidKey);
        }

        // Tenta adquirir o novo lock
        $lock = Cache::lock($lockKey, 600); // 10 minutos

        if (!$lock->get()) {
            $this->error('Não foi possível obter o lock.');
            return Command::FAILURE;
        }

        // Salva o PID atual
        $pid = getmypid();
        Cache::put($pidKey, $pid, 600);
        $this->info("Novo daemon iniciado com PID $pid.");

        try {
            while (true) {
                $this->info('Chamando API em ' . now());

                try {
                    $response = Http::timeout(60)->get('https://r4xl039zf8.execute-api.us-east-1.amazonaws.com/teste/');

                    if ($response->successful()) {
                        $this->info('API OK.');
                    } else {
                        $this->error('Falha na API. Status: ' . $response->status());
                    }
                } catch (\Exception $e) {
                    $this->error('Erro ao chamar API: ' . $e->getMessage());
                }

                $this->info('Dormindo por 5 minutos...');
                sleep(300);
            }
        } finally {
            $lock->release();
            Cache::forget($pidKey);
        }

        return Command::SUCCESS;
    }

    /**
     * Verifica se um processo ainda está em execução.
     */
    private function isProcessRunning($pid): bool
    {
        return posix_kill($pid, 0);
    }
}
