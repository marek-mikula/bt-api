<?php

namespace Domain\Limits\Enums;

enum MarketCapCategoryEnum: string
{
    case MICRO = 'micro';
    case SMALL = 'small';
    case MID = 'mid';
    case LARGE = 'large';
    case MEGA = 'mega';

    public function getTranslatedValue(): string
    {
        return __("limits.market_cap.category.{$this->value}");
    }

    // micro: < 250m $
    // small: >= 250m $ and < 2b $
    // mid: >= 2b $ and < 10b $
    // large: >= 10b and < 200b $
    // mega: >= 200b $
    public static function createFromValue(float|int $value): self
    {
        $value = floatval($value);

        if ($value < 250_000_000.0) {
            return self::MICRO;
        }

        if ($value < 2_000_000_000.0) {
            return self::SMALL;
        }

        if ($value < 10_000_000_000.0) {
            return self::MID;
        }

        if ($value < 200_000_000_000.0) {
            return self::LARGE;
        }

        return self::MEGA;
    }
}
