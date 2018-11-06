<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\City
 *
 * @mixin \Eloquent
 */
class City extends Model
{
    protected $table = 'cities';
    public $timestamps = false;
}
