<?php

declare(strict_types=1);

namespace axy\arr\helpers;

final class ArrayMapHelper
{
    /** Walks and changes elements */
    public static function map(
        mixed $input,
        callable $callback,
        mixed $path = null,
        mixed $default = null,
        mixed $extra = null,
    ): array {
        if (!is_array($input)) {
            return [];
        }
        $result = [];
        foreach ($input as $key => $element) {
            $value = $element;
            if ($path !== null) {
                $value = ArrayPathHelper::get($value, $path, $default);
            }
            $value = call_user_func($callback, $value, $key, $element, $extra);
            $result[$key] = $value;
        }
        return $result;
    }

    /** Walks, changes and filters elements */
    public static function withFilter(
        mixed $input,
        callable $callback,
        mixed $path = null,
        mixed $default = null,
        mixed $extra = null,
        ?bool $isDictionary = null,
    ): array {
        $input = self::map($input, $callback, $path, $default, $extra);
        $result = [];
        $filtered = false;
        foreach ($input as $k => $v) {
            if ($v !== null) {
                $result[$k] = $v;
            } else {
                $filtered = true;
            }
        }
        if ($filtered) {
            if ($isDictionary === null) {
                $isDictionary = !array_is_list($input);
            }
            if (!$isDictionary) {
                $result = array_values($result);
            }
        }
        return $result;
    }

    /** Returns a column from each element */
    public static function column(
        mixed $input,
        mixed $path = null,
        mixed $default = null,
    ): array {
        if (!is_array($input)) {
            return [];
        }
        foreach ($input as &$v) {
            $v = ArrayPathHelper::get($v, $path, $default);
        }
        unset($v);
        return $input;
    }

    /** Filters element fields */
    public static function fields(
        mixed $input,
        ?array $cols = null,
        ?array $defaults = null,
    ): array {
        if (!is_array($input)) {
            return [];
        }
        $cols = self::normalizeColsBeforeFields($cols, $defaults);
        if ($cols === null) {
            $result = [];
            foreach (array_keys($input) as $key) {
                $result[$key] = [];
            }
            return $result;
        }
        if ($defaults === null) {
            $defaults = [];
        }
        foreach (array_keys($cols) as $key) {
            if (!array_key_exists($key, $defaults)) {
                $defaults[$key] = null;
            }
        }
        $result = [];
        foreach ($input as $rootKey => $element) {
            $item = [];
            foreach ($cols as $k => $path) {
                $item[$k] = ArrayPathHelper::get($element, $path, $defaults[$k]);
            }
            $result[$rootKey] = $item;
        }
        return $result;
    }

    private static function normalizeColsBeforeFields(?array $cols, ?array $defaults): ?array
    {
        if ($cols === null) {
            if ($defaults === null) {
                return null;
            }
            $cols = [];
            foreach (array_keys($defaults) as $key) {
                $cols[$key] = $key;
            }
        }
        if (array_is_list($cols)) {
            $result = [];
            foreach ($cols as $v) {
                if (is_array($v)) {
                    if (!empty($v)) {
                        $key = $v[count($v) - 1] ?? null;
                        if ($key !== null) {
                            $result[$key] = $v;
                        }
                    }
                } else {
                    $result[$v] = $v;
                }
            }
            $cols = $result;
        }
        foreach ($cols as $k => &$v) {
            if ($v === null) {
                $v = $k;
            }
        }
        unset($v);
        return $cols;
    }
}
