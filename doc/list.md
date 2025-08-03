# List or dictionary

An array can be considered as a list (numeric array) or a dictionary (associative).
See the standard [array_is_list()](https://www.php.net/array_is_list) function.

This "type" doesn't matter for some methods but for other does.
The library usually tries to preserve the type of the input array.
For example, [sorting](sort.md) a dictionary returns a dictionary with the same keys but reordered.
Sorting of a list returns also a list.

There are exceptions.
These are methods that imply the type changing.
E.g. [ArrayKeysHelper::toKey()](keys.md) always creates a dictionary.

If a method takes the boolean argument `isDictionary` it makes it handle a list as a dictionary.
E.g. the list is `[1, 0, 2, 0, 3]`.
[ArrayFilterHelper::filter()](filter.md) returns `[1, 2, 3]` for it.
But with `isDictionary` the indexes will be preserved as keys: `[0 => 1, 2 => 2, 4 => 3]`.
