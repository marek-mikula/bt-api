<?php

namespace Domain\User\Http\Requests;

use App\Http\Requests\AuthRequest;
use Domain\User\Http\Requests\Data\StoreAlertRequestData;
use Domain\User\Validation\ValidateAlertChannels;
use Domain\User\Validation\ValidateAlertDatetime;

class StoreAlertRequest extends AuthRequest
{
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'date' => [
                'required',
                'string',
                'date_format:Y-m-d',
            ],
            'time' => [
                'nullable',
                'string',
                'date_format:H:i',
            ],
            'content' => [
                'nullable',
                'string',
                'max:500',
            ],
            'asMail' => [
                'required',
                'boolean',
            ],
            'asNotification' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function after(): array
    {
        return [
            app(ValidateAlertDatetime::class),
            app(ValidateAlertChannels::class),
        ];
    }

    public function toData(): StoreAlertRequestData
    {
        return StoreAlertRequestData::from([
            'title' => (string) $this->input('title'),
            'date' => (string) $this->input('date'),
            'time' => $this->filled('time') ? (string) $this->input('time') : null,
            'content' => $this->filled('content') ? (string) $this->input('content') : null,
            'asMail' => $this->boolean('asMail'),
            'asNotification' => $this->boolean('asNotification'),
        ]);
    }
}
