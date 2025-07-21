# axy/arr-helpers

[![Latest Stable Version](https://img.shields.io/packagist/v/axy/arr-helpers.svg?style=flat-square)](https://packagist.org/packages/axy/arr-helpers)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.4-8892BF.svg?style=flat-square)](https://php.net/)
[![Tests](https://github.com/axypro/arr-helpers/actions/workflows/test.yml/badge.svg)](https://github.com/axypro/arr-helpers/actions/workflows/test.yml)
[![Coverage Status](https://coveralls.io/repos/github/axypro/arr-helpers/badge.svg?branch=master)](https://coveralls.io/github/axypro/arr-helpers?branch=master)
[![License](https://poser.pugx.org/axy/arr-helpers/license)](LICENSE)

Just some helpers for array transformations.
Used, for example, in `axy/arr`.
It just a set of classes with static methods.
Are located in the `axy\arr\helpers` namespace.

Transformation methods takes a native array.
It isn't modified in place but the transformation result is returned.

## Validation of arguments

It is implied that incoming array can be received from an unsafe place.
It may not even be an array (that's why the `$input` argument is usually typified as `mixed`).
The library try to process it without fatal errors.

But other arguments usually are not special validated.
If the `$path` argument (see below) as array must have only strings or integers this is the area of responsibility of the external code.
The library will not check each step.

## List or dictionary

An array can be considered as a list (numeric array) or a dictionary (associative).
See the standard [array_is_list()](https://www.php.net/array_is_list).

After transformation of a list usually another list is returned (if the method itself doesn't imply changing of this type).
But, some methods take the argument `isDictionary` that makes them handle a list as a dictionary.

For example, the list is `[1, 0, 2, 0, 3]`.
Filtering by it returns `[1, 2, 3]`.
But with `isDictionary` indexes will be preserved as keys: `[0 => 1, 2 => 2, 4 => 3]`.

## ArrayPathHelper

Some methods work with values inside an array specified by "paths".
A path is `Array<string|int>` with a sequence of keys or just `string` or `int` as a path with one step.
If the value by this path is not found then the default value is used (usually it is `NULL`).

You also can use a closure for the `$path`.
It takes two arguments: the full input array and the default value.
And returns any value.

### `ArrayPathHelper::get(mixed $input, string|int|array $path, mixed $default): mixed`

Returns the value of a nested element of the `$input`

For example, the `$input` is:

```php
[
    'first' => [
        'second' => [
            'third' => [5, 6, 7],
            'null' => null,
        ],
    ],
]
```

* `get($input, ['first', 'second', 'third', 1])` returns 6 (`$input['first']['second']['third'][1]`)
* `get($input, 'first')` returns the `$input['first']`
* `get($input, 'second')` returns `NULL`
* `get($input, 'second', 5)` returns 5 (default value)
* `get($input, ['first', 'second', 'third', 1, 2])` returns `NULL` because the last element is not array
* `get($input, ['first', 'second', 'null'], 'default')` returns not 'default', but `NULL` because `NULL` is existed element
* `get($input, [])` - empty path (or NULL) returns the whole input

### `ArrayPathHelper::set(mixed $input, string|int|array $path, mixed $value): array`

Set a value inside an array by a path and return the modified array.
If the path doesn't exist it will be created.

### `ArrayPathHelper::delete(mixed $input, string|int|array $path): array`

Unset a nested element if found.

### Bulk methods

These methods have the same arguments that methods above and do similar things.
But they work with each element of the `$input`.

* `getOfAll()` - returns an array where keys are keys from the `$input` and values are nested values of each element
* `setForAll()` - set the value inside each element
* `deleteFromAll()` - delete part of each element

## ArrayMapHelper

Methods of this class transform each element of an array according to a certain algorithm.
By default, all elements, their keys and order remain.

### `ArrayMapHelper::map()`

Arguments:

* `input` (array)
* `callback` (callable)
* `path` (mixed, optional)
* `default` (mixed, optional)
* `extra` (mixed, optional)

Each element is passed through the `callback` and the result is written instead of the element.
The `callback` is called with the following arguments:

* The value. If the `$path` and `$default` are specified then it is nested value of the element. By default, it's the element itself.
* The key or index (string|int)
* The element (if no `$path` it equals to the first argument).
* The `$extra` value

### `ArrayMapHelper::withFilter()`

Do not confuse with `ArrayFilterHelper::filter()` (see below).
Does the same thing as `map()` but if the `callback` returns `NULL` the element is removed.
In the `map()` it stored as `NULL`.

The arguments list is the same as for the `map()` but the `$isDictionary` (bool) is added to the tail.
It the `$input` is a list and some elements will be removed by filter it will be cast to not a sparse list again.
`isDictionary` prevents this.

### `ArrayMapHelper::column()`

Arguments:

* `input` (array)
* `path` (mixed)
* `default` (mixed=NULL)

An analog of `array_column` with the following nuanses:

* instead of the key you can use a path (and the default value)
* keys of the `$input` always remain
* all elements remain even that doesn't contain the column

### `ArrayMapHelper::fields()`

Similar to `::column()` but takes not only one column but several.
Arguments:

* `input` (array)
* `$cols` (?array) - the list of required columns (paths)
* `$defaults` (?array) - default values

```php
$data = [
    'one' => [
        'schema' => ['identifier' => 'first', 'value' => 5],
        'rate' => 5,
        'extra' => 7,
    ],
    'two' => [
        'schema' => ['identifier' => 'first', 'value' => 6],
        'rate' => 11,
        'extra' => 8,
    ],
    'three' => [
        'schema' => ['identifier' => 'second', 'value' => 7],
        'extra' => 9,
    ],
];

$simplified = ArrayMapHelper::fields([
    'schema_id' => ['schema', 'identifier'],
    'rate' => 'rate',
]);

// result
[
    'one' => ['schema_id' => 'first', 'rate' => 5],
    'two' => ['schema_id' => 'first', 'rate' => 11],
    'three' => ['schema_id' => 'second', 'rate' => null],
];
```

The `$cols` can be a dictionary or a list.
The example with a dictionary is above.
Keys of `$cols` are keys of result.
Values are paths inside.
`NULL` in value means that keys equal (`'rate' => null` equals to `'rate' => 'rate'`).

When the `$cols` is a list it is just paths.
E.g. `[['schema', 'identifier'], 'rate']`.
The last key of the path will be used to store the result.
It can lead to collisions.

The `$default` is a dictionary of default values.
In simple case it can be used without `$cols`:

```php
ArrayMapHelper::fields(default: [
    'x' => 1,
    'y' => null,
]);
```

It means take from the top level "x" and "y" (with 1 and NULL as default values) and store them with the same key.

## ArrayFilterHelper

In all these methods the `$callback` argument can be:

* `Closure` - call with arguments like the `map()` callback. If returns a TRUE-compatible value it means that the element successfully passed the filter
* `NULL` - just cheks that the element or a nested value is TRUE-compatible
* any other value - the element or a nested value must equal to this value

### ArrayFilterHelper::filter()

Arguments:

* `input` (array)
* `callback` (optional callable)
* `path` (optional)
* `default` (optional)
* `extra` (optional)
* `isDictionary` (bool, optional)

Walks all elements and removes those that don't pass the filter.
Passed element are not changed.

### ArrayFilterHelper::all(): bool

Arguments equal to `filter()` (without `isDictionary`).
Returns TRUE if all elements have passed the filter.

### ArrayFilterHelper::first(): string|int|null

Arguments equal to `filter()` (without `isDictionary`).
Returns the key or the index of the first element that passed the `callback`.
`NULL` if all failed.

### ArrayFilterHelper::any(): bool

Returns TRUE if at least one element passed the `callback`.

## ArraySortHelper

### ArraySortHelper::sort()

Stable sorting of elements with preserving keys of dictionaries.
Arguments:

* `input` (array)
* `path` (mixed) - if specified then sort by nested value
* `default` (mixed) - for the `path`
* `$desc` (bool) - descending order (ascending by default)
* `$sorter` (callable) - custom sorter (by default the standard used). Takes two elements and returns -1,0,1
* `$isDictionary` (bool)

## ArrayUniqueHelper

### ArrayUniqueHelper::unique()

Similar to `array_unique()`.
Arguments:

* `input` (array)
* `path` (mixed) - if specified then uniqueness will be checked for nested values
* `default` (mixed) - for `path`
* `cmp` (?callable) - compare implementation for difficult cases. Takes two arguments and returns if they equal
* `isDictionary` (bool)

## ArrayKeysHelper

### ArrayKeysHelper::toKey()

* `input` (array)
* `key` (mixed) - the path to a value that will be the key
* `value` (mixed, optional) - the path to a value that will be the value
* `default` (mixed=NULL) - for `value`

Changes keys of the input array on the value found by the `key` path.
If it's value is not `int` or `string` the element will be removed.
If keys are not unique the first element will be taken.

If the `value` is specified then the element itself also will be changed.

```php
$input = [
    [
        'user' => ['id' => 10, 'age' => 25],
        'group' => ['id' => 10],
    ],
    [
        'user' => ['id' => 11, 'age' => 35],
        'group' => ['id' => 11],
    ],
    [
        'user' => ['id' => 12, 'age' => 23],
        'group' => ['id' => 12],
    ],
];

ArrayKeysHelper::toKey($input, ['user', 'id'], ['user', 'age']);

// result
[
    10 => 25,
    11 => 35,
    12 => 23,
];
```

## ArrayConverterHelper

### ArrayConverterHelper::toNativeArray(mixed $value): array

Converts a value to a native array:

* For `array` returns the `array` itself
* `Traversable` is expanded to an array
* Other `object`s is cast to an array (object properties to array keys)
* `NULL` is empty array
* any other value is array with one element
