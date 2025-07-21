<?php

declare(strict_types=1);

namespace axy\arr\helpers;

final class ArrayKeysHelper
{
    /** Converts keys and values of elements */
    public static function toKey(
        mixed $input,
        mixed $key = null,
        mixed $value = null,
        mixed $default = null,
    ): array {
        if (!is_array($input)) {
            return [];
        }
        $result = [];
        foreach ($input as $k => $v) {
            if ($key !== null) {
                $k = ArrayPathHelper::get($v, $key);
                if (!(is_string($k) || is_int($k))) {
                    continue;
                }
            }
            if ($value !== null) {
                $v = ArrayPathHelper::get($v, $value, $default);
            }
            if (!array_key_exists($k, $result)) {
                $result[$k] = $v;
            }
        }
        return $result;
    }
}
