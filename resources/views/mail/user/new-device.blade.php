@php

/**
 * @var \App\Models\User $user
 * @var string $time
 * @var string $ipAddress
 * @var string $browser
 */

@endphp

<x-mail::message>
Dear {{ $user->full_name }},

We noticed a recent login to your account from a new device. Here are the details:

Time: **{{ $time }}**<br>
IP Address: **{{ $ipAddress }}**<br>
Browser: **{{ $browser }}**

If you recognize this activity, no further action is required. However, if you didn't initiate this login or suspect unauthorized access, please take immediate action and change your account password.

Best regards,<br>
{{ config('app.name') }}
</x-mail::message>
