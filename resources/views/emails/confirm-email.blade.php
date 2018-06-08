@component('mail::message')
    #One last step

    We just need you to confirm your email adress

@component('mail::button', ['url' => "localhost:8000/register/confirm?token={$user->confirmation_token}"])

    Confirm email:
@endcomponent

Thanks, <br>

{{ config('app.name') }}
@endcomponent
