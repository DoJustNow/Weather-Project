<?php

namespace App\Weather\Clients;

use App\Weather\Translators\TranslatorYandexWeather;
use App\Weather\WeatherClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ClientYandexWeather implements WeatherClientInterface
{

    private $client;
    private $key;


    public function __construct()
    {
        $this->client = new Client();
        $this->key    = env('YANDEX_WEATHER_KEY');
    }

    public function getWeather(float $lat, float $lon)
    {
        $options = ['headers' => ['X-Yandex-API-Key' => $this->key]];
        $uri     = 'https://api.weather.yandex.ru/v1/forecast?' .
                   http_build_query([
                       'lat' => $lat,
                       'lon' => $lon,
                   ]);
        try {
            $response      = $this->client->request('GET', $uri, $options);
            $response_data = json_decode((string)$response->getBody(), true);

            return $response_data;
        } catch (GuzzleException $e) {

            return null;
        }
    }
}