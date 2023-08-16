@php

/**
 * @var \App\Models\User $user
 * @var string $time
 * @var string $ipAddress
 * @var string $browser
 */

$type = \App\Enums\NotificationTypeEnum::NEW_DEVICE;

@endphp

<x-mail::message>
{{ __('common.notifications.salutation', ['name' => $user->full_name]) }},

{{ __n($type, 'mail', 'body.line1') }}

{{ __n($type, 'mail', 'body.list1', ['time' => $time]) }}<br>
{{ __n($type, 'mail', 'body.list2', ['ipAddress' => $ipAddress]) }}<br>
{{ __n($type, 'mail', 'body.list3', ['browser' => $browser]) }}

{{ __n($type, 'mail', 'body.line2') }}

{{ __('common.notifications.regards') }},<br>
{{ config('app.name') }}
</x-mail::message>
