<?php

namespace App\Weather\Adapters;

use App\Weather\DTO\WeatherDTO;

interface WeatherClientAdapterInterface
{
    public function getWeather($lat, $lon);
}