<?php
namespace App\Weather\DTO;

class WeatherDTO implements WeatherDtoInterface
{

    private $condition;
    private $temperature;
    private $windSpeed;

    public function __construct(
        string $condition,
        int $temperature,
        float $windSpeed
    ) {
        $this->condition   = $condition;
        $this->temperature = $temperature;
        $this->windSpeed   = $windSpeed;
    }

    public function getCondition(): string
    {
        return $this->condition;
    }

    public function getTemperature(): int
    {
        return $this->temperature;
    }

    public function getWindSpeed(): float
    {
        return $this->windSpeed;
    }
}