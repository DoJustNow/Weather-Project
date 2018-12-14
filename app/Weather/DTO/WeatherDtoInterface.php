<?php
namespace App\Weather\DTO;

interface WeatherDtoInterface
{

    public function getCondition(): string;

    public function getTemperature(): int;

    public function getWindSpeed(): float;
}