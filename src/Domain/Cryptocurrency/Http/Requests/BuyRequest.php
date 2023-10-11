<?php

namespace Domain\Cryptocurrency\Http\Requests;

use App\Http\Requests\AuthRequest;

class BuyRequest extends AuthRequest
{
    public function rules(): array
    {
        return [
            'quantity' => [
                'required',
                'numeric',
            ],
        ];
    }

    public function getQuantity(): float
    {
        return $this->float('quantity');
    }
}
