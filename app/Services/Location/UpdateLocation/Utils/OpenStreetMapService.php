<?php

namespace App\Services\Location\UpdateLocation\Utils;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use RuntimeException;

class OpenStreetMapService
{
    protected string $baseUrl = 'https://nominatim.openstreetmap.org/search';

    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Получает данные по стране и городу через Nominatim API
     */
    public function getLocationInfo(string $country, string $city): array
    {
        $query = sprintf('%s, %s', $country, $city);

        try {
            $response = $this->client->get($this->baseUrl, [
                'query' => [
                    'q' => $query,
                    'format' => 'json',
                    'addressdetails' => 1,
                    'limit' => 1,
                ],
                'headers' => [
                    'User-Agent' => 'Browse/1.0 (test@example.com)',
                    'Accept-Language' => 'en',
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
            $data = Arr::first($data, default: []);
        } catch (Exception $e) {
            throw new RuntimeException('Error while getting location info');
        }

        return [
            'country' => $data['address']['country'],
            'city' => $data['address']['city'] ?? $data['name'],
            'latitude' => $data['lat'],
            'longitude' => $data['lon'],
        ];
    }
}
