<?php
namespace App\Weather\Adapters;

interface WeatherClientAdapterInterface
{

    public function getWeather(float $lat, float $lon);
}