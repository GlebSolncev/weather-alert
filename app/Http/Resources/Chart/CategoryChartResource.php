<?php

namespace App\Http\Resources\Chart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class CategoryChartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'label' => $this->created_at instanceof Carbon
                ? $this->created_at->format('Y-m-d H:i:s')
                : null,
            'value' => $this->value,
        ];
    }
}
