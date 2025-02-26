<?php

namespace Tests\Unit\Services\Location\UpdateLocation\Utils;

use App\Services\Location\UpdateLocation\Utils\OpenStreetMapService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Tests\Unit\Services\TestCase;

class OpenStreetMapServiceTest extends TestCase
{
    private OpenStreetMapService $service;

    private MockHandler $mockHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockHandler = new MockHandler;
        $handlerStack = HandlerStack::create($this->mockHandler);
        $client = new Client(['handler' => $handlerStack]);

        $this->service = new OpenStreetMapService($client);
    }

    public function test_gets_location_info(): void
    {
        $mockResponse = [
            'address' => [
                'country' => 'Ukraine',
                'city' => 'Odessa',
            ],
            'lat' => 55.7558,
            'lon' => 37.6173,
        ];

        $this->mockHandler->append(
            new Response(200, [], json_encode([$mockResponse]))
        );

        $result = $this->service->getLocationInfo('Ukraine', 'Odessa');

        $this->assertEquals('Ukraine', $result['country']);
        $this->assertEquals('Odessa', $result['city']);
        $this->assertEquals(55.7558, $result['latitude']);
        $this->assertEquals(37.6173, $result['longitude']);
    }

    public function test_throws_exception_on_api_error(): void
    {
        $this->mockHandler->append(
            new Response(500)
        );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Error while getting location info');

        $this->service->getLocationInfo('Invalid', 'Location');
    }
}
