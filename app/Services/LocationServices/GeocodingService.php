<?php

namespace App\Services\LocationServices;

use App\Contracts\LocationContract;
use App\Exceptions\CustomException;
use GuzzleHttp\Client;

class GeocodingService implements LocationContract
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.location.geocoding_api_key');
    }

    public function getLatLon(string $address): array
    {
        $client = new Client(['base_uri' => 'https://maps.googleapis.com', 'timeout'=> 10]);
        $response = $client->get('/maps/api/geocode/json', [
            'query' => [
                'address' => $address,
                'language' => 'zh_TW',
                'key' => $this->apiKey,
            ],
        ]);
        $response = json_decode($response->getBody()->getContents(), true);
        throw_if(
            $response['status'] != 'OK',
            new CustomException('Geocoding fail.', CustomException::ERROR_CODE_LOCATION_SERVICE_GOCODING_FAIL)
        );

        return [
            'lat' => $response['results'][0]['geometry']['location']['lat'],
            'lon' => $response['results'][0]['geometry']['location']['lng'],
        ];
    }
}
