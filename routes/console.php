<?php

use App\Console\Commands\SyncWeatherCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(SyncWeatherCommand::class)
    ->everyFifteenMinutes();
