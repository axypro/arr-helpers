# ArrayConverterHelper

## `toNativeArray(mixed $value): array`

Converts a value to a native array:

* For `array` returns the `array` itself
* `Traversable` is expanded to an array
* Other `object`s is cast to an array (object properties to array keys)
* `NULL` is empty array
* any other value is array with one element
