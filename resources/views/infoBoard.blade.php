@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Страница информации</div>

                    <div class="card-body">
                        @if (session()->has('infoMessage'))
                            <div class="alert alert-primary" role="alert">
                                <!-- Вывод содержимое поля из сесии и  удаление его-->
                                {{ session()->pull('infoMessage') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
