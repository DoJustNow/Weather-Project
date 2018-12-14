<?php
namespace App\Weather\Clients;

interface WeatherClientInterface

{

    public function getWeather(float $lat, float $lon);
}
