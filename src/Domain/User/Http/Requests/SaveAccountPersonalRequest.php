<?php

namespace Domain\User\Http\Requests;

use App\Http\Requests\AuthRequest;
use App\Rules\AgeRule;
use Domain\User\Http\Requests\Data\SaveAccountPersonalRequestData;

class SaveAccountPersonalRequest extends AuthRequest
{
    public function rules(): array
    {
        return [
            'firstname' => [
                'required',
                'string',
                'max:255',
            ],
            'lastname' => [
                'required',
                'string',
                'max:255',
            ],
            'birthDate' => [
                'required',
                'string',
                'date_format:Y-m-d',
                new AgeRule(18),
            ],
        ];
    }

    public function toData(): SaveAccountPersonalRequestData
    {
        return SaveAccountPersonalRequestData::from([
            'firstname' => (string) $this->input('firstname'),
            'lastname' => (string) $this->input('lastname'),
            'birthDate' => (string) $this->input('birthDate'),
        ]);
    }
}
