<?php

declare(strict_types=1);

namespace axy\arr\helpers\tests\path;

use PHPUnit\Framework\Attributes\DataProvider;
use axy\arr\helpers\ArrayPathHelper;
use axy\arr\helpers\tests\BaseTestCase;

/** Covers ArrayPathHelper::set() */
class ArrayPathSetTest extends BaseTestCase
{
    #[DataProvider('providerSet')]
    public function testSet(mixed $input, mixed $path, mixed $value, mixed $expected): void
    {
        $this->assertSame($expected, ArrayPathHelper::set($input, $path, $value));
    }

    public static function providerSet(): array
    {
        $input = [
            'a' => ['b' => 2],
        ];
        return [
            'path' => [
                $input,
                ['a', 'b'],
                3,
                ['a' => ['b' => 3]],
            ],
            'high' => [
                $input,
                'a',
                3,
                ['a' => 3],
            ],
            'top' => [
                $input,
                'b',
                3,
                ['a' => ['b' => 2], 'b' => 3],
            ],
            'create' => [
                $input,
                ['a', 'b', 'c', 'd'],
                3,
                ['a' => ['b' => ['c' => ['d' => 3]]]],
            ],
            'whole' => [
                5,
                null,
                ['x' => 1],
                ['x' => 1],
            ],
            'no_array' => [
                5,
                [],
                3,
                [],
            ],
            'in_not_array' => [
                5,
                ['a', 'b'],
                3,
                ['a' => ['b' => 3]],
            ],
        ];
    }

    public function testSetForAll(): void
    {
        $input = [
            ['x' => ['y' => 1]],
            ['x' => ['y' => ['z' => 5]]],
            ['x' => ['y' => ['a' => 5]]],
            ['y' => 4],
        ];
        $this->assertSame([
            ['x' => ['y' => ['z' => 'value']]],
            ['x' => ['y' => ['z' => 'value']]],
            ['x' => ['y' => ['a' => 5, 'z' => 'value']]],
            ['y' => 4, 'x' => ['y' => ['z' => 'value']]],
        ], ArrayPathHelper::setForAll($input, ['x', 'y', 'z'], 'value'));
    }

    public function testWrongInput(): void
    {
        $this->assertSame([], ArrayPathHelper::setForAll(3, null));
    }
}
