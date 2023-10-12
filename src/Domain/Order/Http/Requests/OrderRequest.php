<?php

namespace Domain\Order\Http\Requests;

use App\Http\Requests\AuthRequest;
use App\Models\CurrencyPair;
use Domain\Order\Http\Requests\Data\OrderRequestData;
use Illuminate\Validation\Rules\Exists;

class OrderRequest extends AuthRequest
{
    public function rules(): array
    {
        return [
            'symbol' => [
                'required',
                'string',
                new Exists(CurrencyPair::class, 'symbol'),
            ],
            'quantity' => [
                'required',
                'numeric',
            ],
        ];
    }

    public function toData(): OrderRequestData
    {
        /** @var CurrencyPair $pair */
        $pair = CurrencyPair::query()
            ->with([
                'baseCurrency',
                'quoteCurrency',
            ])
            ->ofSymbol($this->string('symbol')->toString())
            ->first();

        return OrderRequestData::from([
            'pair' => $pair,
            'quantity' => $this->float('quantity'),
        ]);
    }
}
