@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Личный кабинет</div>
                    <div class="card-body">
                        <p class="text-center font-weight-light text-uppercase">Изменить E-mail адрес</p>

                        <form method="POST" action="{{ route('profileSettingsChange') }}">
                            @csrf
                            <fieldset disabled>
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">{{'Подтвержденный E-Mail'}}</label>

                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="{{$userEmail}}">
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset disabled>
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label text-md-right">{{'Неподтвержденный E-Mail'}}</label>

                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="{{$userUnconfirmedEmail}}">
                                    </div>
                                </div>
                            </fieldset>
                            <div class="form-group row">
                                <label for="email"
                                       class="col-md-4 col-form-label text-md-right">{{'Новый E-Mail'}}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                           class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                           name="email" value="{{ old('email') }}" required>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{'Сохранить'}}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Если в сессии храниться статус отправки выведем его и сразу удалим из сессии (pull)-->
    @if (session('send_email_message'))
        <div id="info-alert" class="wow flipInX alert alert-{{session()->pull('send_email_status')?'success':'danger'}}
                show fade col-md-8 mt-1 ml-auto mr-auto">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ session()->pull('send_email_message') }}
        </div>
    @endif

    {{-- Блок вывода ошибок --}}
    @include('layouts.errors');
@endsection