# ArrayUniqueHelper

## `unique()`

Similar to [array_unique()](https://www.php.net/array_unique).
Arguments:

* `input` (array)
* `path` (mixed) - if specified then uniqueness will be checked for nested values
* `default` (mixed) - for `path`
* `cmp` (?callable) - takes two arguments and returns if they equal
* [isDictionary](list.md) (bool)

## Closures

In difficult cases you can use the `$cmp`.
But it is non-effective on large arrays because called for each possible pair.
Sometimes it can be replaced with a closure in the [$path](path.md).
See [sort()](sort.md) with similar example.
