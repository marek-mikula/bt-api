<?php

namespace Domain\User\Http\Requests;

use App\Http\Requests\AuthRequest;
use Domain\User\Http\Requests\Data\UpdateLimitsRequestData;
use Domain\User\Validation\ValidateLimitsMarketCap;

class UpdateLimitsRequest extends AuthRequest
{
    public function rules(): array
    {
        return [
            'trade.enabled' => [
                'required',
                'boolean',
            ],
            'trade.daily' => [
                'nullable',
                'integer',
                'lte:trade.weekly',
                'lte:trade.monthly',
            ],
            'trade.weekly' => [
                'nullable',
                'integer',
                'lte:trade.monthly',
            ],
            'trade.monthly' => [
                'nullable',
                'integer',
            ],
            'cryptocurrency.enabled' => [
                'required',
                'boolean',
            ],
            'cryptocurrency.min' => [
                'nullable',
                'integer',
                'let:cryptocurrency.max',
            ],
            'cryptocurrency.max' => [
                'nullable',
                'integer',
            ],
            'marketCap.enabled' => [
                'required',
                'boolean',
            ],
            'marketCap.margin' => [
                'nullable',
                'integer',
                'between:3,15',
            ],
            'marketCap.microEnabled' => [
                'required',
                'boolean',
            ],
            'marketCap.micro' => [
                'required_if:marketCap.microEnabled,1',
                'integer',
                'between:0,100',
            ],
            'marketCap.smallEnabled' => [
                'required',
                'boolean',
            ],
            'marketCap.small' => [
                'required_if:marketCap.smallEnabled,1',
                'integer',
                'between:0,100',
            ],
            'marketCap.midEnabled' => [
                'required',
                'boolean',
            ],
            'marketCap.mid' => [
                'required_if:marketCap.midEnabled,1',
                'integer',
                'between:0,100',
            ],
            'marketCap.largeEnabled' => [
                'required',
                'boolean',
            ],
            'marketCap.large' => [
                'required_if:marketCap.largeEnabled,1',
                'integer',
                'between:0,100',
            ],
            'marketCap.megaEnabled' => [
                'required',
                'boolean',
            ],
            'marketCap.mega' => [
                'required_if:marketCap.megaEnabled,1',
                'integer',
                'between:0,100',
            ],
        ];
    }

    public function after(): array
    {
        return [
            app(ValidateLimitsMarketCap::class),
        ];
    }

    public function toData(): UpdateLimitsRequestData
    {
        return UpdateLimitsRequestData::from([
            'tradeEnabled' => $this->boolean('tradeEnabled'),
            'tradeDaily' => $this->integer('tradeDaily', null),
            'tradeWeekly' => $this->integer('tradeWeekly', null),
            'tradeMonthly' => $this->integer('tradeMonthly', null),
            'cryptocurrencyEnabled' => $this->boolean('cryptocurrencyEnabled'),
            'cryptocurrencyMin' => $this->integer('cryptocurrencyMin', null),
            'cryptocurrencyMax' => $this->integer('cryptocurrencyMax', null),
            'marketCapEnabled' => $this->boolean('marketCapEnabled'),
            'marketCapMargin' => $this->integer('marketCapMargin', null),
            'marketCapMicroEnabled' => $this->boolean('marketCapMicroEnabled'),
            'marketCapMicro' => $this->integer('marketCapMicro', null),
            'marketCapSmallEnabled' => $this->boolean('marketCapSmallEnabled'),
            'marketCapSmall' => $this->integer('marketCapSmall', null),
            'marketCapMidEnabled' => $this->boolean('marketCapMidEnabled'),
            'marketCapMid' => $this->integer('marketCapMid', null),
            'marketCapLargeEnabled' => $this->boolean('marketCapLargeEnabled'),
            'marketCapLarge' => $this->integer('marketCapLarge', null),
            'marketCapMegaEnabled' => $this->boolean('marketCapMegaEnabled'),
            'marketCapMega' => $this->integer('marketCapMega', null),
        ]);
    }
}
