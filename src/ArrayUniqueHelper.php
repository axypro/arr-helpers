<?php

declare(strict_types=1);

namespace axy\arr\helpers;

final class ArrayUniqueHelper
{
    /** Removes non-unique elements */
    public static function unique(
        mixed $input,
        mixed $path = null,
        mixed $default = null,
        ?callable $cmp = null,
        ?bool $isDictionary = null,
    ): array {
        if (!is_array($input)) {
            return [];
        }
        $values = $input;
        $isPathUsed = !(($path === null) || ($path === []));
        if ($isPathUsed) {
            $values = ArrayPathHelper::getOfAll($input, $path, $default);
        }
        if ($cmp !== null) {
            $values = self::customCompare($cmp, $values);
        } else {
            $values = array_unique($values);
        }
        if ($isPathUsed) {
            $result = [];
            foreach (array_keys($values) as $key) {
                $result[$key] = $input[$key];
            }
        } else {
            $result = $values;
        }
        if ($isDictionary === null) {
            $isDictionary = !array_is_list($input);
        }
        if (!$isDictionary) {
            $result = array_values($result);
        }
        return $result;
    }

    private static function customCompare(callable $cmp, array $values): array
    {
        $result = [];
        foreach ($values as $k => $v) {
            if (self::findFirst($cmp, $result, $v) === null) {
                $result[$k] = $v;
            }
        }
        return $result;
    }

    private static function findFirst(callable $cmp, array $values, mixed $value): string|int|null
    {
        foreach ($values as $k => $v) {
            if (call_user_func($cmp, $v, $value)) {
                return $k;
            }
        }
        return null;
    }
}
