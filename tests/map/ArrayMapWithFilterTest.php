<?php

declare(strict_types=1);

namespace axy\arr\helpers\tests\map;

use axy\arr\helpers\ArrayMapHelper;
use axy\arr\helpers\tests\BaseTestCase;

/** Covers ArrayMapHelper::withFilter() */
class ArrayMapWithFilterTest extends BaseTestCase
{
    public function testWithFilter(): void
    {
        $input = [
            'one' => ['a' => 1],
            'two' => ['a' => 2],
            'three' => ['a' => 3],
            'four' => ['a' => 4],
        ];
        $callback = function (int $value): ?int {
            return ($value % 2 === 1) ? $value * 2 : null;
        };
        $this->assertSame([
            'one' => 2,
            'three' => 6,
        ], ArrayMapHelper::withFilter($input, $callback, path: 'a'));
    }

    public function testIsDictionary(): void
    {
        $input = [
            ['a' => 1],
            ['a' => 2],
            ['a' => 3],
            ['a' => 4],
        ];
        $callback = function (int $value): ?int {
            return ($value % 2 === 1) ? $value * 2 : null;
        };
        $this->assertSame([
            2,
            6,
        ], ArrayMapHelper::withFilter($input, $callback, path: 'a'));
        $this->assertSame([
            0 => 2,
            2 => 6,
        ], ArrayMapHelper::withFilter($input, $callback, path: 'a', isDictionary: true));
    }
}
