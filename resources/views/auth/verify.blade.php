@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{'Подтвердите свой Email адрес' }}</div>

                    <div class="card-body">
                        @if (session('resent'))
                            <div class="alert alert-primary" role="alert">
                                <!-- Вывод содержимое поля resent из сесии и  удаление его-->
                                {{ session()->pull('resent') }}
                            </div>
                        @endif

                        {{ 'Прежде чем продолжить проверьте свой почтовый ящик для подтверждения Email адреса.'}}
                        <br>
                        {{'Если вы у казали неверный адрес измените его в настройках профиля.'}}
                        <hr>
                        {{ 'Если письмо не пришло, нажмите '}}<a
                                href="{{ route('verify_resend') }}">{{'отправить письмо заново.' }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
