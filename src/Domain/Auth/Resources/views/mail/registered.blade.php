@php

/**
 * @var \App\Models\User $user
 * @var string $url
 * @var string $code
 * @var string $validity
 */

$type = \App\Enums\NotificationTypeEnum::REGISTERED;

@endphp

<x-mail::message>
{{ __('common.notifications.salutation', ['name' => $user->full_name]) }},

{{ __n($type, 'mail', 'body.line1') }}

{{ __n($type, 'mail', 'body.line2', ['code' => $code]) }}

<x-mail::button :url="$url">
    {{ __n($type, 'mail', 'body.action1') }}
</x-mail::button>

{{ __n($type, 'mail', 'body.line3', ['validity' => $validity]) }}

{{ __n($type, 'mail', 'body.line4') }}

{{ __('common.notifications.regards') }},<br>
{{ config('app.name') }}

<hr>

<small class="break-all">
    {{ __('common.notifications.link', ['link' => $url]) }}
</small>
</x-mail::message>