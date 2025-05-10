<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class cron_push_notification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cron_push_notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
         $response = Http::get('https://r4xl039zf8.execute-api.us-east-1.amazonaws.com/teste/');

        if ($response->successful()) {
            \Log::info($response);
        } else {
            \Log::error('Erro ao enviar push notification.', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }
    }
}
