<?php

namespace Domain\User\Http\Requests;

use App\Http\Requests\AuthRequest;
use Domain\User\Http\Requests\Data\UpdateLimitsRequestData;
use Domain\User\Validation\ValidateLimitsMarketCap;
use Illuminate\Validation\Rule;

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
                Rule::when(
                    $this->filled('cryptocurrency.min') && $this->filled('cryptocurrency.max'),
                    'lte:cryptocurrency.max'
                ),
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
                'nullable',
                'integer',
                'between:0,100',
            ],
            'marketCap.smallEnabled' => [
                'required',
                'boolean',
            ],
            'marketCap.small' => [
                'required_if:marketCap.smallEnabled,1',
                'nullable',
                'integer',
                'between:0,100',
            ],
            'marketCap.midEnabled' => [
                'required',
                'boolean',
            ],
            'marketCap.mid' => [
                'required_if:marketCap.midEnabled,1',
                'nullable',
                'integer',
                'between:0,100',
            ],
            'marketCap.largeEnabled' => [
                'required',
                'boolean',
            ],
            'marketCap.large' => [
                'required_if:marketCap.largeEnabled,1',
                'nullable',
                'integer',
                'between:0,100',
            ],
            'marketCap.megaEnabled' => [
                'required',
                'boolean',
            ],
            'marketCap.mega' => [
                'required_if:marketCap.megaEnabled,1',
                'nullable',
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
            'tradeEnabled' => $this->boolean('trade.enabled'),
            'tradeDaily' => $this->filled('trade.daily') ? (int) $this->input('trade.daily') : null,
            'tradeWeekly' => $this->filled('trade.weekly') ? (int) $this->input('trade.weekly') : null,
            'tradeMonthly' => $this->filled('trade.monthly') ? (int) $this->input('trade.monthly') : null,
            'cryptocurrencyEnabled' => $this->boolean('cryptocurrency.enabled'),
            'cryptocurrencyMin' => $this->filled('cryptocurrency.min') ? (int) $this->input('cryptocurrency.min') : null,
            'cryptocurrencyMax' => $this->filled('cryptocurrency.max') ? (int) $this->input('cryptocurrency.max') : null,
            'marketCapEnabled' => $this->boolean('marketCap.enabled'),
            'marketCapMargin' => $this->filled('marketCap.margin') ? (int) $this->input('marketCap.margin') : null,
            'marketCapMicroEnabled' => $this->boolean('marketCap.microEnabled'),
            'marketCapMicro' => $this->filled('marketCap.micro') ? (int) $this->input('marketCap.micro') : null,
            'marketCapSmallEnabled' => $this->boolean('marketCap.smallEnabled'),
            'marketCapSmall' => $this->filled('marketCap.small') ? (int) $this->input('marketCap.small') : null,
            'marketCapMidEnabled' => $this->boolean('marketCap.midEnabled'),
            'marketCapMid' => $this->filled('marketCap.mid') ? (int) $this->input('marketCap.mid') : null,
            'marketCapLargeEnabled' => $this->boolean('marketCap.largeEnabled'),
            'marketCapLarge' => $this->filled('marketCap.large') ? (int) $this->input('marketCap.large') : null,
            'marketCapMegaEnabled' => $this->boolean('marketCap.megaEnabled'),
            'marketCapMega' => $this->filled('marketCap.mega') ? (int) $this->input('marketCap.mega') : null,
        ]);
    }
}
