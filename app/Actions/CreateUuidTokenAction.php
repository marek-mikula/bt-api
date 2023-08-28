<?php

namespace App\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateUuidTokenAction
{
    use AsAction;

    /**
     * @param  class-string<Model>  $for
     */
    public static function create(string $for, string $field = 'token'): string
    {
        return self::make()->handle($for, $field);
    }

    /**
     * @param  class-string<Model>  $for
     */
    private function handle(string $for, string $field): string
    {
        do {
            $token = Str::uuid()->toString();
        } while ($for::query()->where($field, '=', $token)->exists());

        return $token;
    }
}
