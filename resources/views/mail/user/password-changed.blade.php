@php

/**
 * @var \App\Models\User $user
 */

@endphp

<x-mail::message>
Dear {{ $user->full_name }},

We are writing to inform you that your password has been successfully changed.

If you changed your password recently, then you can safely ignore this email.

However, if you did not make this change, or you believe that someone else may have accessed your account, please contact us immediately.

Best regards,<br>
{{ config('app.name') }}
</x-mail::message>
