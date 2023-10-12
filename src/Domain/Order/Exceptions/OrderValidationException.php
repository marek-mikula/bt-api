<?php

namespace Domain\Order\Exceptions;

use Domain\Order\Enums\OrderErrorEnum;
use Exception;
use Illuminate\Validation\ValidationException;

class OrderValidationException extends Exception
{
    public function __construct(
        public readonly OrderErrorEnum $enum,
        public readonly array $data = [],
    ) {
        parent::__construct('Order is invalid.');
    }

    /**
     * @throws ValidationException
     */
    public function toValidationException(): void
    {
        $fields = match ($this->enum) {
            OrderErrorEnum::MIN_QUANTITY_EXCEEDED => [
                'quantity' => __('validation.custom.order.min_quantity_exceeded', [
                    'min' => $this->data['min'],
                ]),
            ],
            OrderErrorEnum::MAX_QUANTITY_EXCEEDED => [
                'quantity' => __('validation.custom.order.max_quantity_exceeded', [
                    'max' => $this->data['max'],
                ]),
            ],
            OrderErrorEnum::STEP_SIZE_INVALID => [
                'quantity' => __('validation.custom.order.step_size_invalid', [
                    'step' => $this->data['step'],
                ]),
            ],
            OrderErrorEnum::MIN_NOTIONAL_EXCEEDED => [
                'price' => __('validation.custom.order.min_notional_exceeded', [
                    'min' => $this->data['min'],
                ]),
            ],
            OrderErrorEnum::MAX_NOTIONAL_EXCEEDED => [
                'price' => __('validation.custom.order.max_notional_exceeded', [
                    'max' => $this->data['max'],
                ]),
            ],
            OrderErrorEnum::NO_FUNDS => [
                'price' => __('validation.custom.order.no_funds', [
                    'funds' => $this->data['funds'],
                ]),
            ],
            OrderErrorEnum::DAILY_TRADES_EXCEEDED => [
                'common' => __('validation.custom.order.daily_trades_exceeded', [
                    'max' => $this->data['max'],
                ]),
            ],
            OrderErrorEnum::WEEKLY_TRADES_EXCEEDED => [
                'common' => __('validation.custom.order.weekly_trades_exceeded', [
                    'max' => $this->data['max'],
                ]),
            ],
            OrderErrorEnum::MONTHLY_TRADES_EXCEEDED => [
                'common' => __('validation.custom.order.monthly_trades_exceeded', [
                    'max' => $this->data['max'],
                ]),
            ],
            OrderErrorEnum::MAX_ASSETS_EXCEEDED => [
                'common' => __('validation.custom.order.max_assets_exceeded', [
                    'max' => $this->data['max'],
                ]),
            ],
            OrderErrorEnum::MIN_ASSETS_EXCEEDED => [
                'common' => __('validation.custom.order.min_assets_exceeded', [
                    'min' => $this->data['min'],
                ]),
            ],
            OrderErrorEnum::MARKET_CAP_EXCEEDED => [
                'common' => __('validation.custom.order.market_cap_exceeded', [
                    'category' => $this->data['category'],
                    'from' => $this->data['from'],
                    'to' => $this->data['to'],
                    'value' => $this->data['value'],
                ]),
            ],
        };

        throw ValidationException::withMessages($fields);
    }
}
