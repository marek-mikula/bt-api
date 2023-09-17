<?php

namespace Domain\User\Http\Requests;

use App\Http\Requests\AuthRequest;
use Domain\User\Enums\LimitsNotificationPeriodEnum;
use Domain\User\Http\Requests\Data\UpdateLimitsRequestData;
use Domain\User\Validation\ValidateLimits;
use Domain\User\Validation\ValidateLimitsMarketCap;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateLimitsRequest extends AuthRequest
{
    public function rules(): array
    {
        return [
            // TRADE
            'trade.enabled' => [
                'required',
                'boolean',
            ],
            'trade.daily' => [
                'nullable',
                'integer',
                Rule::when($this->filled('trade.daily') && $this->filled('trade.weekly'), 'lte:trade.weekly'),
                Rule::when($this->filled('trade.daily') && $this->filled('trade.monthly'), 'lte:trade.monthly'),
                'min:0',
            ],
            'trade.weekly' => [
                'nullable',
                'integer',
                Rule::when($this->filled('trade.weekly') && $this->filled('trade.monthly'), 'lte:trade.monthly'),
                'min:0',
            ],
            'trade.monthly' => [
                'nullable',
                'integer',
                'min:0',
            ],

            // CRYPTOCURRENCY
            'cryptocurrency.enabled' => [
                'required',
                'boolean',
            ],
            'cryptocurrency.period' => [
                Rule::when($this->boolean('cryptocurrency.enabled'), 'required', 'nullable'),
                'string',
                new Enum(LimitsNotificationPeriodEnum::class),
            ],
            'cryptocurrency.min' => [
                'nullable',
                'integer',
                Rule::when($this->filled('cryptocurrency.min') && $this->filled('cryptocurrency.max'), 'lte:cryptocurrency.max'),
                'min:0',
            ],
            'cryptocurrency.max' => [
                'nullable',
                'integer',
                'min:0',
            ],

            // MARKET CAP
            'marketCap.enabled' => [
                'required',
                'boolean',
            ],
            'marketCap.period' => [
                Rule::when($this->boolean('marketCap.enabled'), 'required', 'nullable'),
                'string',
                new Enum(LimitsNotificationPeriodEnum::class),
            ],
            'marketCap.margin' => [
                Rule::when($this->boolean('marketCap.enabled'), 'required', 'nullable'),
                'integer',
                'between:3,15',
            ],

            // MARKET CAP OBJECT - micro
            'marketCap.microEnabled' => [
                'required',
                'boolean',
            ],
            'marketCap.micro' => [
                Rule::when($this->boolean('marketCap.enabled') && $this->boolean('marketCap.microEnabled'), 'required', 'nullable'),
                'integer',
                'between:0,100',
            ],

            // MARKET CAP OBJECT - small
            'marketCap.smallEnabled' => [
                'required',
                'boolean',
            ],
            'marketCap.small' => [
                Rule::when($this->boolean('marketCap.enabled') && $this->boolean('marketCap.smallEnabled'), 'required', 'nullable'),
                'integer',
                'between:0,100',
            ],

            // MARKET CAP OBJECT - mid
            'marketCap.midEnabled' => [
                'required',
                'boolean',
            ],
            'marketCap.mid' => [
                Rule::when($this->boolean('marketCap.enabled') && $this->boolean('marketCap.midEnabled'), 'required', 'nullable'),
                'integer',
                'between:0,100',
            ],

            // MARKET CAP OBJECT - large
            'marketCap.largeEnabled' => [
                'required',
                'boolean',
            ],
            'marketCap.large' => [
                Rule::when($this->boolean('marketCap.enabled') && $this->boolean('marketCap.largeEnabled'), 'required', 'nullable'),
                'integer',
                'between:0,100',
            ],

            // MARKET CAP OBJECT - mega
            'marketCap.megaEnabled' => [
                'required',
                'boolean',
            ],
            'marketCap.mega' => [
                Rule::when($this->boolean('marketCap.enabled') && $this->boolean('marketCap.megaEnabled'), 'required', 'nullable'),
                'integer',
                'between:0,100',
            ],
        ];
    }

    public function after(): array
    {
        return [
            app(ValidateLimits::class),
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
            'cryptocurrencyPeriod' => $this->filled('cryptocurrency.period') ? (string) $this->input('cryptocurrency.period') : null,
            'cryptocurrencyMin' => $this->filled('cryptocurrency.min') ? (int) $this->input('cryptocurrency.min') : null,
            'cryptocurrencyMax' => $this->filled('cryptocurrency.max') ? (int) $this->input('cryptocurrency.max') : null,
            'marketCapEnabled' => $this->boolean('marketCap.enabled'),
            'marketCapPeriod' => $this->filled('marketCap.period') ? (string) $this->input('marketCap.period') : null,
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
