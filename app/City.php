<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\City
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property float $lat
 * @property float $lon
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City whereLon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City whereName($value)
 */
class City extends Model
{
    protected $table = 'cities';
    public $timestamps = false;
}
