<?php
namespace App\Weather\Translators;

use App\Weather\DTO\WeatherDTO;

class TranslatorOpenWeather
{
    public static function translate(array $data): WeatherDTO
    {

        $condition   = $data['weather'][0]['description'];
        $temperature = $data['main']['temp'];
        $windSpeed   = $data['wind']['speed'];

        return new WeatherDTO($condition, $temperature, $windSpeed);
    }
}