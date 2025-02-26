<?php

namespace App\Http\Resources\Chart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeriesChartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'data' => $this->data,
            'type' => $this->type ?? 'line',
            'color' => $this->color ?? null,
        ];
    }
}
