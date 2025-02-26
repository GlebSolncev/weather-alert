<?php

namespace App\Services\Weather\SyncWeather\Pipelines;

use App\Jobs\SmartAlertJob;
use App\Services\Weather\SyncWeather\DTO\WeatherParamDTO;
use Closure;
use Illuminate\Support\Facades\DB;

class DispatchSmartAlert
{
    public function handle(WeatherParamDTO $DTO, Closure $next)
    {
        $result = $DTO->getResult();

        $query = DB::table('location_user')
            ->select('user_id')
            ->where([
                ['location_id', '=', $DTO->getLocationId()],
            ])
            ->where(function ($query) use ($result) {
                $query->where([
                    ['max_precipitation', '<', $result['uv_index']],
                ])->orWHere([
                    ['max_uv', '<', $result['precipitation']],
                ]);
            });

        $query->cursor()->chunk(100)->each(function ($data) use (
            $DTO,
            $result
        ) {
            SmartAlertJob::dispatch([
                'userIds' => $data->pluck('user_id')->toArray(),
                'locationId' => $DTO->getLocationId(),
                'weatherData' => $result,
            ]);
        });

        return $next($DTO);
    }
}
