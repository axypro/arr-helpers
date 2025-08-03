# ArraySortHelper

## `ArraySortHelper::sort()`

Stable sorting of elements with preserving keys of dictionaries.
Arguments:

* `input` (array)
* `path` (mixed) - if specified then sort by nested value
* `default` (mixed) - for the `path`
* `$desc` (bool) - descending order (ascending by default)
* `$sorter` (callable) - custom sorter (by default the standard used). Takes two elements and returns -1,0,1
* [$isDictionary](list.md) (bool)

## Closures

Sometimes the `$sorter` can be replaced by a closure in the `$path`:

```php
$arr = [
    ['id' => 1, 'values' => [3, 5, 4, 6]],
    ['id' => 2, 'values' => [2, 4, 2, 7]],
    ['id' => 3, 'values' => [8, 8, 1, 10]],
    ['id' => 4, 'values' => [2, 2, 4, 7]],
];
$sorter = function (array $a, array $b): int {
    $sum1 = array_sum($a ?? []);
    $sum2 = array_sum($b ?? []);
    if ($sum1 > $sum2) {
        return 1;
    }
    if ($sum1 < $sum2) {
        return -1;
    }
    return 0;
};
print_r(ArraySortHelper::sort($arr, path: 'values', sorter: $sorter));

$path = function (array $item): int {
    return array_sum($item['values'] ?? []);
};
print_r(ArraySortHelper::sort($arr, path: $path));
```

With the `$sorter` each element will be compared with another many times and each time the sum of values will be calculated.
In the example with `$path` firstly the sum of each element will be calculated and then the array of sums will be sorted.
