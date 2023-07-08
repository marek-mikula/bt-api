@php

/**
 * @var \App\Models\User $user
 * @var string $url
 * @var string $code
 * @var string $validity
 */

@endphp

<x-mail::message>
Dear {{ $user->full_name }},

Thank you for registering with our web application! To complete the registration process, we need to confirm your email address.

Please follow the link bellow and use the following code to confirm your email address: **{{ $code }}**

<x-mail::button :url="$url">
Confirm email address
</x-mail::button>

The link is valid until **{{ $validity }}**.

If you did not initiate this registration or do not recognize this email, please disregard this message.

Thank you,<br>
{{ config('app.name') }}

<hr>

<small class="break-all">
    If the button link does not work. Use this link instead <a href="{{ $url }}">{{ $url }}</a>
</small>
</x-mail::message>
