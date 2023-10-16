<?php

namespace Domain\Order\Http\Requests;

use App\Http\Requests\AuthRequest;
use App\Models\CurrencyPair;
use Domain\Order\Enums\OrderSideEnum;
use Domain\Order\Http\Requests\Data\OrderRequestData;
use Illuminate\Validation\Rules\Enum;
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
            'side' => [
                'required',
                'string',
                new Enum(OrderSideEnum::class),
            ],
            'ignoreLimitsValidation' => [
                'nullable',
                'boolean',
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
            'side' => $this->enum('side', OrderSideEnum::class),
            'quantity' => $this->float('quantity'),
            'ignoreLimitsValidation' => $this->boolean('ignoreLimitsValidation'),
        ]);
    }
}
