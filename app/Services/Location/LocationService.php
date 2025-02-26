<?php

namespace App\Services\Location;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class LocationService
{
    public function getWeatherConditions($user, int $limit): Collection
    {
        return Cache::remember(
            'user-locations-' . $user->id . '-' . $limit,
            60,
            static function () use ($user, $limit) {
                return $user->locations()
                    ->with([
                        'weatherConditions' => function ($query) use ($limit) {
                            return $query
                                ->select([
                                    'uv_index',
                                    'precipitation',
                                    'location_id',
                                    'created_at',
                                ])->latest()
                                ->limit($limit);
                        },
                    ])
                    ->select(['id', 'country', 'city'])
                    ->get();
            });
    }
}
