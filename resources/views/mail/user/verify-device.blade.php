@php

/**
 * @var \App\Models\User $user
 * @var string $url
 * @var string $code
 * @var string $device
 * @var string $validity
 */

@endphp

<x-mail::message>
Dear {{ $user->full_name }},

We have noticed that a new device is being used to login to your account. To ensure the security of your account, we require you to verify this device.

Device: **{{ $device }}**

Please use the following code to complete the verification process:

Verification Code: **{{ $code }}**

To verify the new device, please use the link below.

<x-mail::button :url="$url">
Verify new device
</x-mail::button>

The link is valid until **{{ $validity }}**.

If you did not initiate this login or believe that your account may be compromised, please contact us immediately.

Thank you for your cooperation in keeping your account secure.

Best regards,<br>
{{ config('app.name') }}

<hr>

<small class="break-all">
    If the button link does not work. Use this link instead <a href="{{ $url }}">{{ $url }}</a>
</small>
</x-mail::message>
