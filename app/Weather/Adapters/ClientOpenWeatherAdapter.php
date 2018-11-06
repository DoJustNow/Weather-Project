<?php
namespace App\Weather\Adapters;

use App\Weather\Clients\ClientOpenWeather;
use App\Weather\DTO\WeatherDTO;
use App\Weather\Translators\TranslatorOpenWeather;

class ClientOpenWeatherAdapter implements WeatherClientAdapterInterface
{
    private $client;

    public function __construct(ClientOpenWeather $client)
    {
        $this->client = $client;
    }

    public function getWeather($lat, $lon)
    {
        //Получение данных о погоде от API
        $dataWeather = $this->client->getWeather($lat, $lon);
        if(is_null($dataWeather)) return null;
        //Трансляция данных из массива в WeatherDTO
        return TranslatorOpenWeather::translate($dataWeather);
    }
}