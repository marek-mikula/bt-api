<?php

use Domain\Limits\Enums\MarketCapCategoryEnum;

return [
    'market_cap' => [
        'category' => [
            MarketCapCategoryEnum::MICRO->value => 'MICRO (< 250m $)',
            MarketCapCategoryEnum::SMALL->value => 'SMALL (>= 250m $ and < 2b $)',
            MarketCapCategoryEnum::MID->value => 'MID (2b $ and < 10b $)',
            MarketCapCategoryEnum::LARGE->value => 'LARGE (>= 10b and < 200b $)',
            MarketCapCategoryEnum::MEGA->value => 'MEGA (>= 200b $)',
        ],
    ],
];
