<?php

namespace App\Http\Controllers;

use App\City;
use App\Weather;
use App\Weather\Adapters\ClientOpenWeatherAdapter;
use App\Weather\Adapters\ClientYandexWeatherAdapter;
use App\Weather\DTO\WeatherDTO;
use Cache;
use Illuminate\Http\Request;

class WeatherController extends Controller
{

    public function showWeather($city = null)
    {
        $paginateRows = 5;
        $filter       = false;
        $cities       = $this->getCitiesFromDB();
        $weathers     = Weather::orderBy('id', 'desc');
        if ($city) {
            $filter   = $city;
            $weathers = $weathers->where('city', $city);
        }
        $weathers = $weathers->paginate($paginateRows);

        return view('weather.show', compact(['weathers', 'cities', 'filter']));
    }

    public function targetCity(Request $request)
    {
        $validateRules    = ['city' => 'required|exists:cities,name',];
        $validateMessages = [
            'required' => 'Необходимо выбрать город.',
            'exists'   => 'Такого города у нас нет!',
        ];
        $this->validate($request, $validateRules, $validateMessages);

        return redirect()->route('showWeather', ['city' => $request->city]);
    }

    public function addWeatherForm()
    {
        $cities = $this->getCitiesFromDB();

        return view('weather.form', compact(['cities']));
    }

    public function addWeather(
        Request $request,
        ClientOpenWeatherAdapter $clientOpenWeatherAdapter,
        ClientYandexWeatherAdapter $clientYandexAdapter
    ) {
        //TODO Исправить
        //$apiServices = ['Yandex', 'OpenWeather'];
        //Получаем провайдеров
        $apiServices = config('weather_services');
        $this->validate($request,
            ['city_id' => 'required|exists:cities,id'],
            [
                'required' => 'Необходимо выбрать город.',
                'exists'   => 'Что-то пошло не так! :attribute ?',
            ]
        );
        $cities           = $this->getCitiesFromDB();
        $selectedCity     = $cities->find($request->city_id);
        $lat              = $selectedCity->lat;
        $lon              = $selectedCity->lon;
        $city             = $selectedCity->name;
        $addWeatherResult = [];
        foreach ($apiServices as $apiName => $apiClientAdapterClass) {
            $key = sha1($lat . $lon . date('d') . $apiName);
            //Кеширование на 60 минут
            $weather = Cache::remember($key, 60,
                function () use ($lat, $lon, $apiClientAdapterClass) {
                    return app()->make($apiClientAdapterClass)->getWeather($lat,
                        $lon);
                });
            if ($weather !== null) {
                $this->addWeatherInDB($apiName, $city, $weather);
                $addWeatherResult['successful'][]
                    = "Данные из $apiName API удачно добавлены";
            } else {
                $addWeatherResult['failure'][]
                    = "Не удалось подключиться к $apiName API";
            }

        }

        session()->put('addWeatherResult', $addWeatherResult);

        return view('weather.form', compact(['cities']));
    }

    private function getCitiesFromDB()
    {
        $cities = Cache::remember('sities', 600, function () {
            return City::all();
        });

        return $cities;
    }

    private function addWeatherInDB(
        string $api,
        string $city,
        WeatherDTO $weather
    ) {
        $table              = new Weather();
        $table->api         = $api;
        $table->city        = $city;
        $table->condition   = $weather->getCondition();
        $table->temperature = $weather->getTemperature();
        $table->wind_speed  = $weather->getWindSpeed();
        $table->save();
    }
}