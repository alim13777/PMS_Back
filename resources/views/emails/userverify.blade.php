@component('mail::message')
    # Welcome {{ $notifiable->name }}

    Before you can use this tutorial system you must verify your email address.

    @component('mail::button', ['url' => $url])
        Laravel
    @endcomponent

    If you did not create an account, no further action is required.
    Thanks,

    {{ config('app.name') }} Team

    @component('mail::subcopy')
        If you’re having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser: {{ $url }}
    @endcomponent

@endcomponent

