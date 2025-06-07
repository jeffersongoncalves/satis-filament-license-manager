<?php

namespace App\Support;

use BackedEnum;

if (! function_exists('App\Support\enum_equals')) {
    function enum_equals(BackedEnum|string|int|null $value, BackedEnum|array $enum): bool
    {
        if (is_array($enum)) {
            return array_reduce($enum, fn (bool $carry, BackedEnum $enum) => $carry || enum_equals($enum, $value), false);
        }

        if (! $value instanceof BackedEnum) {
            return $enum::tryFrom($value) === $enum;
        }

        return $enum === $value;
    }
}
