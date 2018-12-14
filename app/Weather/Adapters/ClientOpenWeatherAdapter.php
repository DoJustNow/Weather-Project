<?php
namespace App\Weather\Adapters;

use App\Weather\Clients\ClientOpenWeather;
use App\Weather\Translators\TranslatorOpenWeather;

class ClientOpenWeatherAdapter implements WeatherClientAdapterInterface
{

    private $client;

    public function __construct(ClientOpenWeather $client)
    {
        $this->client = $client;
    }

    public function getWeather(float $lat,float $lon)
    {
        //Получение данных о погоде от API
        $dataWeather = $this->client->getWeather($lat, $lon);
        if ($dataWeather === null) {
            return null;
        }

        //Трансляция данных из массива в WeatherDTO
        return TranslatorOpenWeather::translate($dataWeather);
    }
}