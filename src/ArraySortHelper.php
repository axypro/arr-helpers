<?php

declare(strict_types=1);

namespace axy\arr\helpers;

final class ArraySortHelper
{
    /** Stable sorting */
    public static function sort(
        mixed $input,
        mixed $path = null,
        mixed $default = null,
        bool $desc = false,
        ?callable $sorter = null,
        ?bool $isDictionary = null,
    ): array {
        if (!is_array($input)) {
            return $input;
        }
        $values = $input;
        $isPathUsed = (($path !== null) && ($path !== []));
        if ($isPathUsed) {
            $values = ArrayPathHelper::getOfAll($values, $path, $default);
        }
        if ($sorter !== null) {
            if ($desc) {
                uasort($values, function (mixed $a, mixed $b) use ($sorter) {
                    return call_user_func($sorter, $b, $a);
                });
            } else {
                uasort($values, $sorter);
            }
        } else {
            if ($desc) {
                arsort($values);
            } else {
                asort($values);
            }
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
}
