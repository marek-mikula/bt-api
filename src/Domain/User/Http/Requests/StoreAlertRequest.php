<?php

namespace Domain\User\Http\Requests;

use App\Http\Requests\AuthRequest;
use Domain\User\Http\Requests\Data\StoreAlertRequestData;

class StoreAlertRequest extends AuthRequest
{
    public function rules(): array
    {
        return [
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
                'required',
                'string',
                'max:500',
            ]
        ];
    }

    public function toData(): StoreAlertRequestData
    {
        return StoreAlertRequestData::from([
            'date' => (string) $this->input('date'),
            'time' => $this->has('time') ? (string) $this->input('time') : null,
            'content' => (string) $this->input('content'),
        ]);
    }
}
