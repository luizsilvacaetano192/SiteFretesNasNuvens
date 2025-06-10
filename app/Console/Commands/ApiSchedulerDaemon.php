<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ApiSchedulerDaemon extends Command
{
    protected $signature = 'scheduler:api-daemon';
    protected $description = 'Run a daemon that calls an API every 5 minutes with a 1-minute timeout';

    public function handle()
    {
        $this->info('API Scheduler daemon started. Press Ctrl+C to stop.');

        while (true) {
            $this->info('Calling API at ' . now());

            try {
                // Altere esta URL para a sua API real
                $response = Http::timeout(60)->get('https://r4xl039zf8.execute-api.us-east-1.amazonaws.com/teste/');

                if ($response->successful()) {
                    $this->info('API call successful.');
                } else {
                    $this->error('API call failed. Status: ' . $response->status());
                }
            } catch (\Exception $e) {
                $this->error('API call error: ' . $e->getMessage());
            }

            $this->info('Sleeping for 5 minutes...');
            sleep(300); // 5 minutos
        }
    }
}
