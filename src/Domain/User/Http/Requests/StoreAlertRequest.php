<?php

namespace Domain\User\Http\Requests;

use App\Http\Requests\AuthRequest;
use Domain\User\Http\Requests\Data\StoreAlertRequestData;
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
        ];
    }

    public function after(): array
    {
        return [
            app(ValidateAlertDatetime::class),
        ];
    }

    public function toData(): StoreAlertRequestData
    {
        return StoreAlertRequestData::from([
            'title' => (string) $this->input('title'),
            'date' => (string) $this->input('date'),
            'time' => $this->has('time') ? (string) $this->input('time') : null,
            'content' => $this->has('content') ? (string) $this->input('content') : null,
        ]);
    }
}
