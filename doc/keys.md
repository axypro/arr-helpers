# ArrayKeysHelper

## `toKey()`

Arguments:

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
