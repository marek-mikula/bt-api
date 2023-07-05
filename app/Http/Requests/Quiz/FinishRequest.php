<?php

namespace App\Http\Requests\Quiz;

use App\Http\Requests\AuthRequest;

class FinishRequest extends AuthRequest
{
    public function rules(): array
    {
        return [
            'answers' => [
                'required',
                'array',
            ],
            'answers.*.id' => [
                'required',
                'integer',
            ],
            'answers.*.answer' => [
                'required',
                'integer',
            ]
        ];
    }

    public function toData(): FinishRequestData
    {
        return FinishRequestData::from([
            'answers' => $this->collect('answers')
                ->map(fn (array $answer): FinishRequestAnswerData => FinishRequestAnswerData::from([
                    'id' => (int) $answer['id'],
                    'answer' => (int) $answer['answer'],
                ])),
        ]);
    }
}
