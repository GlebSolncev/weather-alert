<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property float $uv_index
 * @property float $precipitation
 * @property int $location_id
 * @property Location $location
 */
class WeatherCondition extends Model
{
    use HasFactory;

    protected $fillable = [
        'uv_index',
        'precipitation',
    ];

    protected $casts = [
        'uv_index' => 'float',
        'precipitation' => 'float',
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
