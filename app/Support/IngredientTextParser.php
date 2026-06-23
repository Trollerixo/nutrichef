<?php

namespace App\Support;

class IngredientTextParser
{
    public static function parse(?string $value): array
    {
        if (!is_string($value)) {
            return [];
        }

        $value = trim($value);

        if ($value === '') {
            return [];
        }

        $parts = preg_split('/\s+y\s+|[\/,.\r\n]+/iu', $value, -1, PREG_SPLIT_NO_EMPTY);

        return array_values(array_filter(array_map('trim', $parts ?: []), static fn ($part) => $part !== ''));
    }
}
