@component('mail::message')
# Introduction
## Hallo {{$user->name}}

Please confirm your E-mail

@component('mail::button', ['url' => url('/register/confirm/'.$user->confirmation_token)])
Confirm your Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
