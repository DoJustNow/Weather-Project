@component('mail::message')
# Проверочное письмо
Для подтверждения Email адреса нажмите на кнопку
@component('mail::button', ['url' => $verify_url, 'color'=>'green'])
Подтвердить
@endcomponent
###### Если кнопка не работает используйте ссылку:<br>{{$verify_url}}
@endcomponent