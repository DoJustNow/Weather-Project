<?php

use App\City;
use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataCities = [
                [
                        'name' => 'Sevastopol',
                        'lat'  => 44.616687,
                        'lon'  => 33.525432,
                ],
                [
                        'name' => 'Volokolamsk',
                        'lat'  => 56.036251,
                        'lon'  => 35.957423,
                ],
                [
                        'name' => 'Moscow',
                        'lat'  => 55.753960,
                        'lon'  => 37.620393,
                ],
                [
                        'name' => 'Taganrog',
                        'lat'  => 47.209580,
                        'lon'  => 38.935194,
                ],
                [
                        'name' => 'Lipetsk',
                        'lat'  => 52.610220,
                        'lon'  => 39.594719,
                ],
                [
                        'name' => 'Omsk',
                        'lat'  => 54.989342,
                        'lon'  => 73.368212,
                ],

        ];
        foreach ($dataCities as $dataCity) {
            $city = new City($dataCity);
            $city->save();
        }

    }
}
