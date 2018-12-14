<?php
namespace App\Weather\Translators;

use App\Weather\DTO\WeatherDTO;

class TranslatorYandexWeather implements TranslatorYandexWeatherInterface
{

    public static function translate(array $data): WeatherDTO
    {
        $condition   = $data['fact']['condition'];
        $temperature = $data['fact']['temp'];
        $windSpeed   = $data['fact']['wind_speed'];

        return new WeatherDTO($condition, $temperature, $windSpeed);
    }
}