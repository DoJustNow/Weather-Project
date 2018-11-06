<?php

namespace App\Weather\Adapters;

use App\Weather\DTO\WeatherDTO;
use App\Weather\Translators\TranslatorOpenWeather;
use App\Weather\Translators\TranslatorYandexWeather;
use App\Weather\WeatherClientInterface;

class WeatherClientAdapter
{
    private $client;
    private $lat;
    private $lon;

    public function setClientAdapter(WeatherClientInterface $client)
    {
        $this->client = $client;
    }



    public function getWeather($lat, $lon)
    {
        return $this->client->getWeather($lat, $lon);
    }

}