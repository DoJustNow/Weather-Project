@extends('layouts.app')
@section('title')
    Погода
@endsection
@section('content')
    <div class="container">
        <form action="{{route('cityWeather')}}" method="post">
            <div class="form-row mt-1 mb-1 ml-0">
                @csrf
                <div class="input-group">
                    <select size="1" name="city" class="custom-select col-auto">
                        <option value="" selected disabled hidden>Выберите город</option>
                        {{-- Формирование списка городов для фильтрации --}}
                        @foreach($cities as $city)
                            <option value="{{$city->name}}" @if($filter == $city->name){{'disabled'}}@endif>{{$city->name}}</option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary">Отфильтровать</button>
                        {{-- Вывод кнопки "Сбросить фильтр", если включена фильтрация --}}
                        @if($filter)
                            <a href="{{route('showWeather')}}" class="btn btn-outline-secondary">
                                Сбросить фильтр
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
        Публикуется также на <a href="https://vk.com/id516997158" target="_blank" class="alert-link">https://vk.com/id516997158</a>
        <div class="table-responsive table-sm">
            <table class="table table-striped table-bordered table-dark table-hover text-center">
                <thead>
                <tr class="">
                    <th>API</th>
                    <th>Город</th>
                    <th>Погода</th>
                    <th>Температура, °C</th>
                    <th>Скорость ветра, м/c</th>
                    <th>Дата добавления</th>
                </tr>
                </thead>
                <tbody>
                {{-- Формирование строк (ячеек) таблицы--}}
                @foreach ($weathers as $weather)
                    <tr>
                        <td>{{$weather->api}}</td>
                        <td>{{$weather->city}}</td>
                        <td>{{$weather->condition}}</td>
                        <td>{{$weather->temperature}}</td>
                        <td>{{$weather->wind_speed}}</td>
                        <td>{{$weather->created_at}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{-- Вывод пагинатора --}}
        {{$weathers->links('vendor.pagination.bootstrap-4')}}
        {{-- Блок вывода ошибок --}}
        @include('layouts.errors')
    </div>
@endsection