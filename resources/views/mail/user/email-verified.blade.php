@php

/**
 * @var \App\Models\User $user
 */

$type = \App\Enums\NotificationTypeEnum::EMAIL_VERIFIED;

@endphp

<x-mail::message>
{{ __('common.notifications.salutation', ['name' => $user->full_name]) }},

{{ __n($type, 'mail', 'body.line1') }}

{{ __('common.notifications.regards') }},<br>
{{ config('app.name') }}
</x-mail::message>
