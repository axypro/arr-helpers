# Path inside arrays

Many methods in this library takes `$path` as an argument.
It allows to specify an element inside the input array or inside in each element.
E.g. [sort()](sort.md) can sort a list by elements themselves or by nested values.

The `$path` can be of the following types:

* `string|int` - a key or an index inside the array on the top level.
* `Array<string|int>` - a sequences of keys (a path). E.g. `['a', 'b', 'c']` points to `$input['a']['b']['c']`.
* `NULL` or an empty path array - the input array itself.
* A `closure` that
    * takes two arguments: the `$input` array and the `$default` value (see below)
    * returns the searched value (`mixed`)

For example:

```php
$arr1 = [1, 3, 2, 4];
$sorted1 = ArraySortHelper::sort($arr1); // sorted just by values

$arr2 = [
    ['user' => ['name' => 'John', 'age' => 25], 'id' => 1],
    ['user' => ['name' => 'Jack', 'age' => 27], 'id' => 2],
    ['user' => ['name' => 'George', 'age' => 27], 'id' => 3],
];
$sortedByAge = ArraySortHelper::sort($arr2, path: ['user', 'age']);
```

If the element is not found or the path is wrong (in the example, the "user" is not array) the library doesn't throw any errors, just uses a default value.
This value usually transmitted in the argument `$default (mixed)` and is `NULL` by default.

## `ArrayPathHelper`

Provides basic methods to work with paths that other helpers use and can be used directly.

### `get(mixed $input [, mixed $path, mixed $default]): mixed`

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

### `set(mixed $input [, mixed $path, mixed $value]): array`

Set a value inside an array by a path and return the modified array.
If the path doesn't exist it will be created.

### `delete(mixed $input, string|int|array $path): array`

Unset a nested element if found.

### Bulk methods

These methods have the same arguments that methods above and do similar things.
But they work with each element of the `$input`.

* `getOfAll()` - returns an array where keys are keys from the `$input` and values are nested values of each element
* `setForAll()` - set the value inside each element
* `deleteFromAll()` - delete part of each element
