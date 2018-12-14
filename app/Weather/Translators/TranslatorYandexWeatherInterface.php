<?php
namespace App\Weather\Translators;

use App\Weather\DTO\WeatherDTO;

interface TranslatorYandexWeatherInterface
{
    public static function translate(array $data): WeatherDTO;
}