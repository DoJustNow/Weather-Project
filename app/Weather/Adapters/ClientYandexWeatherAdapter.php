<?php
namespace App\Weather\Adapters;

use App\Weather\Clients\ClientYandexWeather;
use App\Weather\DTO\WeatherDTO;
use App\Weather\Translators\TranslatorYandexWeather;

class ClientYandexWeatherAdapter implements WeatherClientAdapterInterface
{
    private $client;

    public function __construct(ClientYandexWeather $client)
    {
        $this->client = $client;
    }

    public function getWeather($lat, $lon)
    {
        //Получение данных о погоде от API
        $dataWeather = $this->client->getWeather($lat, $lon);
        if(is_null($dataWeather)) return null;
        //Трансляция данных из массива в WeatherDTO
        return TranslatorYandexWeather::translate($dataWeather);
    }
}