<?php

namespace App\Models;

use App\Notifications\BaseNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;

/**
 * @property string $id uuid string ID
 * @property class-string<BaseNotification> $type
 * @property class-string<Model> $notifiable
 * @property array $data
 * @property Carbon|null $read_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Notification extends DatabaseNotification
{
    use HasFactory;
}
