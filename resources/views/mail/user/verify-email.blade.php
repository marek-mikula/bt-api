@php

/**
 * @var \App\Models\User $user
 * @var string $url
 * @var string $code
 */

@endphp

<x-mail::message>
Dear {{ $user->full_name }},

We require you to verify your email address in order to complete the login process. This is a security measure that ensures only you have access to your account.

Please use the following code to complete the verification process:

Verification Code: **{{ $code }}**

To verify your email address, please use the link below.

<x-mail::button :url="$url">
Verify email address
</x-mail::button>

If you did not initiate this login or believe that your account may be compromised, please contact us immediately.

Thank you for your cooperation in keeping your account secure.

Best regards,<br>
{{ config('app.name') }}

<hr>

<small class="break-all">
    If the button link does not work. Use this link instead <a href="{{ $url }}">{{ $url }}</a>
</small>
</x-mail::message>
