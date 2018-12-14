<?php
namespace App\Weather\Translators;

use App\Weather\DTO\WeatherDTO;

interface TranslatorOpenWeatherInterface
{
    public static function translate(array $data): WeatherDTO;
}