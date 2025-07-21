<?php

declare(strict_types=1);

namespace axy\arr\helpers;

use Closure;

final class ArrayPathHelper
{
    /** Finds a value inside an array by a path */
    public static function get(
        mixed $input,
        string|int|array|Closure|null $path,
        mixed $default = null,
    ): mixed {
        if (($path === null) || ($path === [])) {
            return $input;
        }
        if (!is_array($input)) {
            if ($path instanceof Closure) {
                return $path->__invoke($input, $default);
            }
            return $default;
        }
        if (!is_array($path)) {
            if ($path instanceof Closure) {
                return $path->__invoke($input, $default);
            }
            $last = $path;
            $path = [];
        } else {
            $last = array_pop($path);
        }
        foreach ($path as $p) {
            $input = $input[$p] ?? null;
            if (!is_array($input)) {
                return $default;
            }
        }
        if (!array_key_exists($last, $input)) {
            return $default;
        }
        return $input[$last];
    }

    /** Set a value inside an array by a path */
    public static function set(
        mixed $input,
        string|int|array|null $path,
        mixed $value = null,
    ): array {
        if (($path === null) || ($path === [])) {
            if (is_array($value)) {
                return $value;
            }
            return [];
        }
        if (!is_array($input)) {
            $input = [];
        }
        if (!is_array($path)) {
            $last = $path;
            $path = [];
        } else {
            $last = array_pop($path);
        }
        $origin = &$input;
        foreach ($path as $p) {
            if ((!isset($input[$p])) || (!is_array($input[$p]))) {
                $input[$p] = [];
            }
            $input = &$input[$p];
        }
        $input[$last] = $value;
        unset($input);
        return $origin;
    }

    /** Delete a nested element of an array by a path */
    public static function delete(
        mixed $input,
        string|int|array|null $path,
    ): array {
        if (!is_array($input)) {
            return [];
        }
        if (($path === null) || ($path === [])) {
            return [];
        }
        if (!is_array($path)) {
            $last = $path;
            $path = [];
        } else {
            $last = array_pop($path);
        }
        $origin = &$input;
        foreach ($path as $p) {
            if ((!isset($input[$p])) || (!is_array($input[$p]))) {
                unset($input);
                return $origin;
            }
            $input = &$input[$p];
        }
        unset($input[$last]);
        unset($input);
        return $origin;
    }

    /** Returns nested values of all elements */
    public static function getOfAll(
        mixed $input,
        string|int|array|Closure|null $path,
        mixed $default = null,
    ): array {
        if (!is_array($input)) {
            return [];
        }
        if (($path === null) || ($path === [])) {
            return $input;
        }
        foreach ($input as &$v) {
            $v = self::get($v, $path, $default);
        }
        unset($v);
        return $input;
    }

    /** Set a value inside each element */
    public static function setForAll(
        mixed $input,
        string|int|array|null $path,
        mixed $value = null,
    ): array {
        if (!is_array($input)) {
            return [];
        }
        foreach ($input as &$v) {
            $v = self::set($v, $path, $value);
        }
        unset($v);
        return $input;
    }

    /** Deletes a part of each element */
    public static function deleteFromAll(
        mixed $input,
        string|int|array|null $path,
    ): array {
        if (!is_array($input)) {
            return [];
        }
        foreach ($input as &$v) {
            $v = self::delete($v, $path);
        }
        unset($v);
        return $input;
    }
}
