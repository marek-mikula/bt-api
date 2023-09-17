<?php

namespace App\Data\Casts;

use BackedEnum;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Uncastable;
use Spatie\LaravelData\Exceptions\CannotCastEnum;
use Spatie\LaravelData\Support\DataProperty;
use Throwable;

class EnumCast implements Cast
{
    public function __construct(
        protected ?string $type = null,
        protected bool $nullable = false,
    ) {
    }

    public function cast(DataProperty $property, mixed $value, array $context): BackedEnum|Uncastable|null
    {
        if ($this->nullable && empty($value)) {
            return null;
        }

        $type = $this->type ?? $property->type->findAcceptedTypeForBaseType(BackedEnum::class);

        if ($type === null) {
            return Uncastable::create();
        }

        /** @var class-string<BackedEnum> $type */
        try {
            return $type::from($value);
        } catch (Throwable) {
            throw CannotCastEnum::create($type, $value);
        }
    }
}
