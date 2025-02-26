<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $country
 * @property string $city
 * @property float $latitude
 * @property float $longitude
 * @property Collection|User[] $users
 * @property Collection|WeatherCondition[] $weatherConditions
 */
class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'country',
        'city',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['max_uv', 'max_precipitation']);
    }

    public function weatherConditions(): HasMany
    {
        return $this->hasMany(WeatherCondition::class);
    }

    public function getFullAddressAttribute(): string
    {
        return sprintf('%s, %s', $this->city, $this->country);
    }
}
