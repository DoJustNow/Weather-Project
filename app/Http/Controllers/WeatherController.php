<?php

namespace App\Http\Controllers;

use App\City;
use App\Weather;
use App\Weather\Adapters\ClientOpenWeatherAdapter;
use App\Weather\Adapters\ClientYandexWeatherAdapter;
use App\Weather\Clients\ClientOpenWeather;
use App\Weather\Clients\ClientYandexWeather;
use App\Weather\DTO\WeatherDTO;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Response;

class WeatherController extends Controller
{

    private $request;

    private $clientYandexWeather;

    private $clientOpenWeather;

    public function __construct(
        Request $request,
        ClientYandexWeather $clientYandexWeather,
        ClientOpenWeather $clientOpenWeather
    ) {
        $this->request             = $request;
        $this->clientYandexWeather = $clientYandexWeather;
        $this->clientOpenWeather   = $clientOpenWeather;

    }

    public function showWeather($city = null)
    {
        $filter = false;
        try {
            $cities   = $this->getCitiesFromDB();
            $weathers = Weather::orderBy('id', 'desc');
            if ($city) {
                $filter   = $city;
                $weathers = $weathers->where('city', $city);
            }
            $weathers = $weathers->paginate(5);

        } catch (Exception $e) {
            session()->put('infoMessage', 'Ошибка подключения к БД.');

            return view('infoBoard');
        }

        return view('weather.show', compact(['weathers', 'cities', 'filter']));
    }

    /*Request уже есть т.к Dependency Injection*/
    public function targetCity()
    {
        $this->validate($this->request,
            ['city' => 'required|exists:cities,name'],
            [
                'required' => 'Необходимо выбрать город.',
                'exists'   => 'Такого города у нас нет!',
            ]);

        return redirect()->route('showWeather',
            ['city' => $this->request->input('city', 'Moscow')]);
    }

    public function addWeatherForm($info = false)
    {
        $cities = $this->getCitiesFromDB();

        return view('weather.form', compact(['cities', 'info']));
    }

    /*Request уже есть т.к Dependency Injection*/
    public function addWeather(
        ClientOpenWeatherAdapter $clientOpenWeatherAdapter,
        ClientYandexWeatherAdapter $clientYandexAdapter
    ) {
        //TODO Исправить
        $apiServices = ['Yandex', 'OpenWeather'];
        $this->validate($this->request,
            ['city_id' => 'required|exists:cities,id'],
            [
                'required' => 'Необходимо выбрать город.',
                'exists'   => 'Что-то пошло не так! :attribute ?',
            ]
        );
        $cities       = $this->getCitiesFromDB();
        $selectedCity = $cities->find($this->request->input('city_id', 1));
        $lat          = $selectedCity->lat;
        $lon          = $selectedCity->lon;
        $city         = $selectedCity->name;

        foreach ($apiServices as $apiService) {
            $key = sha1($lat.$lon.date('d').$apiService);
            //Кеширование на 60 минут
            $weather = Cache::remember($key, 60,
                function () use (
                    $lat,
                    $lon,
                    $apiService,
                    $clientOpenWeatherAdapter,
                    $clientYandexAdapter
                ) {
                    return ${"client{$apiService}Adapter"}->getWeather($lat,
                        $lon);
                });

            if ( ! empty($weather)) {
                if ($result = $this->addWeatherInDB($apiService, $city,
                    $weather)
                ) {
                    $info ['successful'][] = $result;
                } else {
                    return view('infoBoard');
                }
            } else {
                $info ['failure'][]
                    = "Не удалось подключиться к $apiService API";
            }

        }

        return view('weather.form', compact(['cities', 'info']));
    }

    private function getCitiesFromDB(): Collection
    {
        try {
            $cities = City::all();
        } catch (Exception $e) {
            session()->put('infoMessage', 'Ошибка подключения к БД.');

            return view('infoBoard');
        }

        return $cities;
    }

    private function addWeatherInDB(
        string $api,
        string $city,
        WeatherDTO $weather
    ): string {
        $table              = new Weather();
        $table->api         = $api;
        $table->city        = $city;
        $table->condition   = $weather->getCondition();
        $table->temperature = $weather->getTemperature();
        $table->wind_speed  = $weather->getWindSpeed();
        try {
            $table->save();

            return "Данные из $api API удачно добавлены";
        } catch (Exception $e) {
            //записываем в сессию результат
            session()->put('infoMessage', 'Ошибка подключения к БД.');

            return false;
        }
    }
}