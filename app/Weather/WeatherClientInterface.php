<?php
namespace App\Weather;

use App\Weather\DTO\WeatherDTO;

interface WeatherClientInterface

{
    public function getWeather(float $lat, float $lon);
}
