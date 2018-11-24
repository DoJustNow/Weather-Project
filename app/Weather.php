<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Weather
 *
 * @property int $id
 * @property string $api
 * @property string $city
 * @property string $condition
 * @property int $temperature
 * @property float $wind_speed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $posted
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Weather whereApi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Weather whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Weather whereCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Weather whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Weather whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Weather wherePosted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Weather whereTemperature($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Weather whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Weather whereWindSpeed($value)
 * @mixin \Eloquent
 */
class Weather extends Model
{
    protected $table = 'weathers';

}
