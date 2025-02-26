<?php

namespace App\Console\Commands;

use App\Services\Weather\SyncWeatherService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncWeatherCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and store weather data from multiple providers';

    /**
     * Execute the console command.
     */
    public function handle(SyncWeatherService $service): int
    {
        try {
            $this->info('Starting weather sync...');
            Log::info('SyncWeatherCommand: Starting weather sync');

            $service->dispatchSync();

            $this->info('Weather sync completed successfully');
            Log::info('SyncWeatherCommand: Weather sync completed');

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Weather sync failed: '.$e->getMessage());
            Log::error('SyncWeatherCommand: Weather sync failed', [
                'error' => $e->getMessage(),
            ]);

            return self::FAILURE;
        }
    }
}
