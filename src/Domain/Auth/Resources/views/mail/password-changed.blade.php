@php

/**
 * @var \App\Models\User $user
 */

$type = \App\Enums\NotificationTypeEnum::PASSWORD_CHANGED;

@endphp

<x-mail::message>
{{ __('common.notifications.salutation', ['name' => $user->full_name]) }},

{{ __n($type, 'mail', 'body.line1') }}

{{ __n($type, 'mail', 'body.line2') }}

{{ __n($type, 'mail', 'body.line3') }}

{{ __('common.notifications.regards') }},<br>
{{ config('app.name') }}
</x-mail::message>
