<?php

declare(strict_types=1);

namespace axy\arr\helpers\tests\filter;

use axy\arr\helpers\ArrayFilterHelper;
use axy\arr\helpers\tests\BaseTestCase;

/** Covers ArrayFilterHelper::filter() */
class ArrayFilterFilterTest extends BaseTestCase
{
    public function testSimpleList(): void
    {
        $this->assertSame(
            [1, 3, 2],
            ArrayFilterHelper::filter([1, 3, 0, 2, 0]),
        );
    }

    public function testSimpleDictionary(): void
    {
        $this->assertSame(
            ['a' => 1, 'b' => 3, 'd' => 2],
            ArrayFilterHelper::filter(['a' => 1, 'b' => 3, 'c' => 0, 'd' => 2, 'e' => 0]),
        );
    }

    public function testForcedDictionary(): void
    {
        $this->assertSame(
            [0 => 1, 1 => 3, 3 => 2],
            ArrayFilterHelper::filter([1, 3, 0, 2, 0], isDictionary: true),
        );
    }

    public function testCallback(): void
    {
        $input = [
            'one' => ['x' => ['y' => 1]],
            'two' => ['x' => ['y' => null]],
            'three' => ['x' => ['z' => 1]],
        ];
        $logs = [];
        $callback = function (mixed $value, string $key, array $element, int $extra) use (&$logs): bool {
            $logs[] = [$value, $key, $element, $extra];
            return (!empty($value));
        };
        $this->assertSame([
            'one' => ['x' => ['y' => 1]],
            'three' => ['x' => ['z' => 1]],
        ], ArrayFilterHelper::filter(
            input: $input,
            callback: $callback,
            path: ['x', 'y'],
            default: 5,
            extra: 11,
        ));
        $this->assertEquals([
            [1, 'one', ['x' => ['y' => 1]], 11],
            [null, 'two', ['x' => ['y' => null]], 11],
            [5, 'three', ['x' => ['z' => 1]], 11],
        ], $logs);
    }

    public function testValue(): void
    {
        $input = [
            'one' => ['x' => 1],
            'two' => ['x' => 2],
            'three' => ['x' => 1],
            'four' => ['x' => 2],
        ];
        $this->assertSame([
            'two' => ['x' => 2],
            'four' => ['x' => 2],
        ], ArrayFilterHelper::filter(
            input: $input,
            callback: 2,
            path: 'x',
        ));
    }

    public function testWrongInput(): void
    {
        $this->assertSame([], ArrayFilterHelper::filter(3));
    }
}
