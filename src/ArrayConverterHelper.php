<?php

declare(strict_types=1);

namespace axy\arr\helpers;

use Traversable;

final class ArrayConverterHelper
{
    /**
     * Converts a value to a native array
     *
     * @param mixed $value
     *        array - itself
     *        Traversable - expanded to an array
     *        object - cast to an array (object property => array key)
     *        NULL - an empty array
     *        any other value - an array with the only value
     * @return array
     */
    public static function toNativeArray(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }
        if (is_object($value)) {
            if ($value instanceof Traversable) {
                return iterator_to_array($value);
            }
            return (array)$value;
        }
        if ($value === null) {
            return [];
        }
        return [$value];
    }
}
