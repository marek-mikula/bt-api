<?php

namespace App\Models\Formatters;

use App\Formatters\DateTimeFormatter;
use App\Models\MfaToken;

/**
 * @mixin  MfaToken
 */
trait MfaTokenFormatter
{
    use DateTimeFormatter;

    public function formatValidUntil(): string
    {
        return $this->formatDatetime($this->valid_until);
    }
}
