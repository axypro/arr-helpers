<?php

declare(strict_types=1);

namespace axy\arr\helpers\tests\unique;

use axy\arr\helpers\ArrayUniqueHelper;
use axy\arr\helpers\tests\BaseTestCase;

/** Covers ArrayUniqueHelper::unique() */
class ArrayUniqueUniqueTest extends BaseTestCase
{
    public function testPlainList(): void
    {
        $this->assertSame(
            [1, 2, 3, 4, 5],
            ArrayUniqueHelper::unique([1, 2, 3, 2, 4, 2, 3, 5]),
        );
    }

    public function testPlainDictionary(): void
    {
        $this->assertSame(
            ['a' => 1, 'b' => 2, 'c' => 3, 'e' => 4, 'h' => 5],
            ArrayUniqueHelper::unique(['a' => 1, 'b' => 2, 'c' => 3, 'd' => 2, 'e' => 4, 'f' => 2, 'g' => 3, 'h' => 5]),
        );
    }

    public function testPlainForcedDictionary(): void
    {
        $this->assertSame(
            [0 => 1, 1 => 2, 2 => 3, 4 => 4, 7 => 5],
            ArrayUniqueHelper::unique([1, 2, 3, 2, 4, 2, 3, 5], isDictionary: true),
        );
    }

    public function testPath(): void
    {
        $input = [
            'a' => ['a' => 5],
            'b' => ['a' => 6],
            'c' => ['a' => 5],
            'd' => ['a' => 4],
        ];
        $this->assertSame([
            'a' => ['a' => 5],
            'b' => ['a' => 6],
            'd' => ['a' => 4],
        ], ArrayUniqueHelper::unique($input, path: 'a'));
    }

    public function testCmpPlain(): void
    {
        $input = [
            'a' => 3,
            'b' => -3,
            'c' => -5,
            'd' => 6,
            'e' => 5,
            'f' => -5,
            'g' => 7,
        ];
        $cmp = function (mixed $a, mixed $b): bool {
            return (abs((int)$a) === abs((int)$b));
        };
        $this->assertSame([
            'a' => 3,
            'c' => -5,
            'd' => 6,
            'g' => 7,
        ], ArrayUniqueHelper::unique($input, cmp: $cmp));
    }

    public function testCmpPath(): void
    {
        $input = [
            'a' => ['x' => 3],
            'b' => ['x' => -3],
            'c' => ['x' => -5],
            'd' => [],
            'e' => ['x' => 5],
            'f' => ['x' => -5],
            'g' => ['x' => 7],
        ];
        $cmp = function (mixed $a, mixed $b): bool {
            return (abs((int)$a) === abs((int)$b));
        };
        $this->assertSame([
            'a' => ['x' => 3],
            'c' => ['x' => -5],
            'd' => [],
            'g' => ['x' => 7],
        ], ArrayUniqueHelper::unique($input, path: 'x', default: 6, cmp: $cmp));
    }

    public function testPathClosure(): void
    {
        $input = [
            10,
            15,
            37,
            25,
            22,
            10,
            35,
            11,
        ];
        $path = function (int $value): int {
            return (int)($value / 10);
        };
        $this->assertSame([
            10,
            37,
            25,
        ], ArrayUniqueHelper::unique($input, path: $path));
    }

    public function testWrongInput(): void
    {
        $this->assertSame([], ArrayUniqueHelper::unique(5));
    }
}
