@php

/**
 * @var \App\Models\User $user
 * @var string $url
 * @var string $code
 */

@endphp

<x-mail::message>
Dear {{ $user->full_name }},

We have noticed that a new device is being used to login to your account. To ensure the security of your account, we require you to verify this device.

Please use the following code to complete the verification process:

Verification Code: **{{ $code }}**

To verify the new device, please use the link below.

<x-mail::button :url="$url">
Verify new device
</x-mail::button>

If you did not initiate this login or believe that your account may be compromised, please contact us immediately.

Thank you for your cooperation in keeping your account secure.

Best regards,<br>
{{ config('app.name') }}
</x-mail::message>
