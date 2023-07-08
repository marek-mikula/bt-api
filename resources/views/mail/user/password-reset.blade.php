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

We received a request to reset the password associated with your account. To proceed with the password reset process, please click on the link below:

<x-mail::button :url="$url">
Reset password
</x-mail::button>

When you click on the link, you'll be taken to a page where you can enter the following secret code: **{{ $code }}**

The link is valid until **{{ $validity }}**.

Once you've entered the secret code, you'll be able to reset your password and access your account.

If you did not request a password reset, please ignore this email. Someone may have entered your email address by mistake. Please do not click on the link or share the secret code with anyone.

Best regards,<br>
{{ config('app.name') }}

<hr>

<small class="break-all">
    If the button link does not work. Use this link instead <a href="{{ $url }}">{{ $url }}</a>
</small>
</x-mail::message>
