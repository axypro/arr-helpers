# ArrayFilterHelper

In all these methods the `$callback` argument can be:

* `Closure` - call with arguments like the `map()` callback. If returns a TRUE-compatible value it means that the element successfully passed the filter
* `NULL` - just cheks that the element or a nested value is TRUE-compatible
* any other value - the element or a nested value must equal to this value

## `filter()`

Arguments:

* `input` (array)
* `callback` (optional callable)
* `path` (optional)
* `default` (optional)
* `extra` (optional)
* `isDictionary` (bool, optional)

Walks all elements and removes those that don't pass the filter.
Passed element are not changed.

## `all(): bool`

Arguments equal to `filter()` (without `isDictionary`).
Returns TRUE if all elements have passed the filter.

## `first(): string|int|null`

Arguments equal to `filter()` (without `isDictionary`).
Returns the key or the index of the first element that passed the `callback`.
`NULL` if all failed.

## `any(): bool`

Returns TRUE if at least one element passed the `callback`.
