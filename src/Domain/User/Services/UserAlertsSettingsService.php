<?php

namespace Domain\User\Services;

use App\Models\Alert;
use App\Models\User;
use App\Repositories\Alert\AlertRepositoryInterface;
use Domain\User\Http\Requests\Data\StoreAlertRequestData;

class UserAlertsSettingsService
{
    public function __construct(
        private readonly AlertRepositoryInterface $alertRepository,
    ) {
    }

    public function store(User $user, StoreAlertRequestData $data): Alert
    {
        return $this->alertRepository->create([
            'user_id' => $user->id,
            'as_mail' => $data->asMail,
            'as_notification' => $data->asNotification,
            'title' => $data->title,
            'content' => $data->content,
            'date_at' => $data->date->format('Y-m-d'),
            'time_at' => $data->time?->setSeconds(0)?->format('H:i'),
        ]);
    }
}
