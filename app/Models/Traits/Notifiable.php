<?php

namespace App\Models\Traits;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\Notifiable as BaseNotifiable;

/**
 * @property-read Collection<Notification> $notifications
 */
trait Notifiable
{
    use BaseNotifiable;

    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'notifiable')->latest();
    }
}
