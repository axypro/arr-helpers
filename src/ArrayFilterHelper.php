<?php

declare(strict_types=1);

namespace axy\arr\helpers;

use Closure;

final class ArrayFilterHelper
{
    /** Removes items that don't match the filter */
    public static function filter(
        mixed $input,
        mixed $callback = null,
        mixed $path = null,
        mixed $default = null,
        mixed $extra = null,
        ?bool $isDictionary = null,
    ): array {
        if (!is_array($input)) {
            return [];
        }
        $result = [];
        $filtered = false;
        foreach ($input as $key => $element) {
            $value = $element;
            if ($path !== null) {
                $value = ArrayPathHelper::get($value, $path, $default);
            }
            if ($callback !== null) {
                if ($callback instanceof Closure) {
                    $value = call_user_func($callback, $value, $key, $element, $extra);
                } else {
                    $value = ($value === $callback);
                }
            }
            if (!empty($value)) {
                $result[$key] = $element;
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

    /** Checks if all elements match the filter */
    public static function all(
        mixed $input,
        mixed $callback = null,
        mixed $path = null,
        mixed $default = null,
        mixed $extra = null,
    ): bool {
        if (!is_array($input)) {
            return false;
        }
        foreach ($input as $key => $element) {
            $value = $element;
            if ($path !== null) {
                $value = ArrayPathHelper::get($value, $path, $default);
            }
            if ($callback !== null) {
                if ($callback instanceof Closure) {
                    $value = call_user_func($callback, $value, $key, $element, $extra);
                } else {
                    $value = ($value === $callback);
                }
            }
            if (empty($value)) {
                return false;
            }
        }
        return true;
    }

    /** Returns the first element matchs the filter */
    public static function first(
        mixed $input,
        mixed $callback = null,
        mixed $path = null,
        mixed $default = null,
        mixed $extra = null,
    ): string|int|null {
        if (!is_array($input)) {
            return null;
        }
        foreach ($input as $key => $element) {
            $value = $element;
            if ($path !== null) {
                $value = ArrayPathHelper::get($value, $path, $default);
            }
            if ($callback !== null) {
                if ($callback instanceof Closure) {
                    $value = call_user_func($callback, $value, $key, $element, $extra);
                } else {
                    $value = ($value === $callback);
                }
            }
            if (!empty($value)) {
                return $key;
            }
        }
        return null;
    }

    /** Checks if at least one element matchs the filter */
    public static function any(
        mixed $input,
        mixed $callback = null,
        mixed $path = null,
        mixed $default = null,
        mixed $extra = null,
    ): bool {
        return (self::first($input, $callback, $path, $default, $extra) !== null);
    }
}
