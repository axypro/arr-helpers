<?php

declare(strict_types=1);

namespace axy\arr\helpers\tests\sort;

use axy\arr\helpers\ArraySortHelper;
use axy\arr\helpers\tests\BaseTestCase;

/** Covers ArraySortHelper::sort() */
class ArraySortSortTest extends BaseTestCase
{
    public function testPlainList(): void
    {
        $this->assertSame(
            [1, 2, 2, 3, 5, 6],
            ArraySortHelper::sort([1, 3, 2, 5, 2, 6]),
        );
        $this->assertSame(
            [6, 5, 3, 2, 2, 1],
            ArraySortHelper::sort([1, 3, 2, 5, 2, 6], desc: true),
        );
    }

    public function testPlainDictionary(): void
    {
        $this->assertSame(
            ['a' => 1, 'c' => 2, 'e' => 2, 'b' => 3, 'd' => 5, 'f' => 6],
            ArraySortHelper::sort(['a' => 1, 'b' => 3, 'c' => 2, 'd' => 5, 'e' => 2, 'f' => 6]),
        );
        $this->assertSame(
            ['f' => 6, 'd' => 5, 'b' => 3, 'c' => 2, 'e' => 2, 'a' => 1],
            ArraySortHelper::sort(['a' => 1, 'b' => 3, 'c' => 2, 'd' => 5, 'e' => 2, 'f' => 6], desc: true),
        );
    }

    public function testPlainForcedDictionary(): void
    {
        $this->assertSame(
            [0 => 1, 2 => 2, 4 => 2, 1 => 3, 3 => 5, 5 => 6],
            ArraySortHelper::sort([1, 3, 2, 5, 2, 6], isDictionary: true),
        );
        $this->assertSame(
            [5 => 6, 3 => 5, 1 => 3, 2 => 2, 4 => 2, 0 => 1],
            ArraySortHelper::sort([1, 3, 2, 5, 2, 6], desc: true, isDictionary: true),
        );
    }

    public function testPathSorterAndStability(): void
    {
        $input = [
            'one' => ['a' => 5],
            'two' => ['a' => 7],
            'three' => ['a' => 15],
            'four' => [],
            'five' => ['a' => 1],
        ];
        $this->assertSame([
            'five' => ['a' => 1],
            'four' => [],
            'one' => ['a' => 5],
            'two' => ['a' => 7],
            'three' => ['a' => 15],
        ], ArraySortHelper::sort($input, path: 'a', default: 3));
        $this->assertSame([
            'three' => ['a' => 15],
            'two' => ['a' => 7],
            'one' => ['a' => 5],
            'four' => [],
            'five' => ['a' => 1],
        ], ArraySortHelper::sort($input, path: 'a', default: 3, desc: true));
        $sorter = function (int $a, int $b): int {
            $a = abs(10 - $a);
            $b = abs(10 - $b);
            if ($a < $b) {
                return -1;
            }
            if ($a > $b) {
                return 1;
            }
            return 0;
        };
        $this->assertSame([
            'two' => ['a' => 7],
            'one' => ['a' => 5],
            'three' => ['a' => 15],
            'four' => [],
            'five' => ['a' => 1],
        ], ArraySortHelper::sort($input, path: 'a', default: 3, sorter: $sorter));
        $this->assertSame([
            'five' => ['a' => 1],
            'four' => [],
            'one' => ['a' => 5],
            'three' => ['a' => 15],
            'two' => ['a' => 7],
        ], ArraySortHelper::sort($input, path: 'a', default: 3, desc: true, sorter: $sorter));
    }

    public function testWrongInput(): void
    {
        $this->assertSame([], ArraySortHelper::sort(5));
    }
}
