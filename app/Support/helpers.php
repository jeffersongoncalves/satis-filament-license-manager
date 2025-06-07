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

if (! function_exists('App\Support\array_merge_recursive_unique')) {
    function array_merge_recursive_unique(array $array1, array $array2): array
    {
        foreach ($array2 as $key => $value) {
            if (is_array($value) && isset($array1[$key]) && is_array($array1[$key])) {
                $array1[$key] = array_merge_recursive_unique($array1[$key], $value);
            } else {
                $array1[$key] = $value;
            }
        }

        return $array1;
    }
}
