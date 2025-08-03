# ArrayMapFilter

Methods of this class transform each element of an array according to a certain algorithm.
By default, all elements, their keys and order remain.

### `map()`

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

### `withFilter()`

Do not confuse with [ArrayFilterHelper::filter()](filter.md).
Does the same thing as `map()` but if the `callback` returns `NULL` the element is removed.
In the `map()` it is stored as `NULL`.

The arguments list is the same as for the `map()` but the `$isDictionary` (bool) is added to the tail.
It the `$input` is a list and some elements will be removed by filter it will be cast to not a sparse list again.
`isDictionary` prevents this.

### `column()`

Arguments:

* `input` (array)
* `path` (mixed)
* `default` (mixed=NULL)

An analog of `array_column` with the following nuances:

* instead of the key you can use a path (and the default value)
* keys of the `$input` always remain
* all elements remain even that doesn't contain the column

### `fields()`

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


