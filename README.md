# axy/arr-helpers

[![Latest Stable Version](https://img.shields.io/packagist/v/axy/arr-helpers.svg?style=flat-square)](https://packagist.org/packages/axy/arr-helpers)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.4-8892BF.svg?style=flat-square)](https://php.net/)
[![Tests](https://github.com/axypro/arr-helpers/actions/workflows/test.yml/badge.svg)](https://github.com/axypro/arr-helpers/actions/workflows/test.yml)
[![Coverage Status](https://coveralls.io/repos/github/axypro/arr-helpers/badge.svg?branch=main)](https://coveralls.io/github/axypro/arr-helpers?branch=main)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

Just some helpers for array transformations.
Used, for example, in `axy/arr`.
It just a set of classes with static methods.
Are located in the `axy\arr\helpers` namespace.

Transformation methods takes a native array.
It isn't modified in place but the transformation result is returned.

* [List or dictionary](doc/list.md)
* [ArrayPathHelper](doc/path.md)
* [ArrayMapHelper](doc/map.md)
* [ArrayFilterHelper](doc/filter.md)
* [ArraySortHelper](doc/sort.md)
* [ArrayUniqueHelper](doc/unique.md)
* [ArrayKeysHelper](doc/keys.md)
* [ArrayConverterHelper](doc/converter.md)

## Validation of arguments

It is implied that incoming array can be received from an unsafe place.
It may not even be an array (that's why the `$input` argument is usually typified as `mixed`).
The library try to process it without fatal errors.

But other arguments usually are not special validated.
If the `$path` argument (see below) as array must have only strings or integers this is the area of responsibility of the external code.
The library will not check each step.
