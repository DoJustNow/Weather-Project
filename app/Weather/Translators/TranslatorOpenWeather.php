<?php
namespace App\Weather\Translators;

use App\Weather\DTO\WeatherDTO;

class TranslatorOpenWeather implements TranslatorOpenWeatherInterface
{

    public static function translate($data): WeatherDTO
    {
        $condition   = $data['weather'][0]['description'];
        $temperature = $data['main']['temp'];
        $windSpeed   = $data['wind']['speed'];

        return new WeatherDTO($condition, $temperature, $windSpeed);
    }
}