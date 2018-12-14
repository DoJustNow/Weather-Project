@extends('layouts.app')
@section('title')
    Форма добавления
@endsection
@section('content')
    <form action="{{route('formAddWeather')}}" method="post">
        @csrf
        <div class="input-group justify-content-center mb-1 mt-1">
            <select size="1" name="city_id" class="custom-select col-8">
                <option value="" selected disabled hidden>Выберите город</option>
                @foreach($cities as $city)
                    <option value="{{$city->id}}">{{$city->name}}</option>
                @endforeach
            </select>
            <div class="input-group-append">
                <button type="submit" class="btn btn-outline-secondary">Добавить</button>
            </div>
        </div>
    </form>


    {{-- Вывод блока(-ов) сообщений --}}
    @if(session('addWeatherResult'))
        @foreach(session()->pull('addWeatherResult') as $typeMessage=>$messages)
            <div id="info-alert" class="wow flipInX alert
        {{$typeMessage=='successful'?'alert-success':'alert-danger'}}
                show fade col-8 mt-1 ml-auto mr-auto">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="alert-heading">{{$typeMessage=='successful'?'Успех!':'Неудача :('}}</h4>
                @foreach($messages as $message)
                    <hr>
                    {{$message}}
                @endforeach
            </div>
        @endforeach
    @endif

    {{-- Блок вывода ошибок --}}
    @include('layouts.errors')
@endsection