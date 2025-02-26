<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ChartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $weathers = $this->weatherConditions;
        $location = sprintf('%s, %s', $this->country, $this->city);

        return [
            'title' => $location,
            'series' => $this->getSeriesData($weathers),
            'categories' => $this->getCategories($weathers),
        ];
    }

    private function getSeriesData($weathers): array
    {
        return [
            [
                'name' => 'UV Index',
                'data' => $weathers->pluck('uv_index')->toArray(),
            ],
            [
                'name' => 'Precipitation',
                'data' => $weathers->pluck('precipitation')->toArray(),
            ],
        ];
    }

    private function getCategories($weathers): array
    {
        return $weathers->pluck('created_at')
            ->map(fn (Carbon $date) => $date->format('H:i'))
            ->toArray();
    }
}
