<?php

namespace Tests\Unit\Services\Weather\SyncWeather\Utils;

use App\Services\Weather\SyncWeather\DTO\WeatherParamDTO;
use App\Services\Weather\SyncWeather\Utils\OpenMeteoProvider;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Tests\Unit\Services\TestCase;

class OpenMeteoProviderTest extends TestCase
{
    private OpenMeteoProvider $provider;

    private MockHandler $mockHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockHandler = new MockHandler;
        $client = new Client(['handler' => HandlerStack::create($this->mockHandler)]);
        $this->provider = new OpenMeteoProvider($client);
    }

    public function test_get_weather_returns_formatted_data(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'current' => [
                'uv_index' => 5.7,
                'precipitation' => 0.5,
            ],
        ], JSON_THROW_ON_ERROR)));

        $dto = new WeatherParamDTO([
            'latitude' => 55.7558,
            'longitude' => 37.6173,
        ]);

        $result = $this->provider->getWeather($dto);

        $this->assertEquals([
            'uv_index' => 0,
            'precipitation' => 0,
        ], $result);
    }

    public function test_get_weather_handles_failed_response(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'current' => [],
        ], JSON_THROW_ON_ERROR)));

        $dto = new WeatherParamDTO([
            'latitude' => 200,
            'longitude' => 0,
        ]);

        $result = $this->provider->getWeather($dto);

        $this->assertEquals([], $result);
    }
}
