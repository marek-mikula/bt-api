@php

/**
 * @var \App\Models\User $user
 * @var \App\Models\Alert $alert
 */

$type = \App\Enums\NotificationTypeEnum::ALERT;

@endphp

<x-mail::message>
{{ __('common.notifications.salutation', ['name' => $user->full_name]) }},

{{ __n($type, 'mail', 'body.line1', ['title' => $alert->title]) }}

@if(! empty($alert->content))
<x-mail::panel>
{{ $alert->content }}
</x-mail::panel>
@endif

{{ __('common.notifications.regards') }},<br>
{{ config('app.name') }}
</x-mail::message>
