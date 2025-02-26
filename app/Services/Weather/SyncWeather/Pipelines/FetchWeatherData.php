<?php

namespace App\Services\Weather\SyncWeather\Pipelines;

use App\Services\Weather\SyncWeather\DTO\WeatherParamDTO;
use App\Services\Weather\SyncWeather\Enumes\WeatherUtils;
use App\Services\Weather\SyncWeather\Utils\WeatherProviderInterface;
use Closure;
use RuntimeException;

class FetchWeatherData
{
    public function handle(WeatherParamDTO $DTO, Closure $next)
    {
        foreach (WeatherUtils::cases() as $util) {
            $provider = $this->getProvider($util);
            $data = $provider->getWeather($DTO);

            $DTO->setProviderData($util->value, $data);
        }

        return $next($DTO);
    }

    private function getProvider(WeatherUtils $util): WeatherProviderInterface
    {
        $provider = $util->className();
        if (is_subclass_of($provider, WeatherProviderInterface::class) === false) {
            throw new RuntimeException('Invalid util');
        }

        return app($util->className());
    }
}
