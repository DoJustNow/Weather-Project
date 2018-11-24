<?php
namespace App\Weather\Clients;


use App\Weather\Translators\TranslatorOpenWeather;
use App\Weather\WeatherClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ClientOpenWeather implements WeatherClientInterface
{

    private $client;
    private $key;

    public function __construct()
    {
        $this->client = new Client();
        $this->key    = env('OPENWEATHER_WEATHER_KEY');
    }

    public function getWeather(float $lat, float $lon)
    {
        $uri = 'https://api.openweathermap.org/data/2.5/weather?' .
               http_build_query([
                   'lat'   => $lat,
                   'lon'   => $lon,
                   'units' => 'metric',
                   'appid' => $this->key,
               ]);
        try {
            $response      = $this->client->request('GET', $uri);
            $response_data = json_decode((string)$response->getBody(), true);

            return $response_data;
        } catch (GuzzleException $e) {
            return null;
        }
    }
}