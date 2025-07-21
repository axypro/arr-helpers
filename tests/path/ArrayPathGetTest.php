<?php

declare(strict_types=1);

namespace axy\arr\helpers\tests\path;

use PHPUnit\Framework\Attributes\DataProvider;
use axy\arr\helpers\ArrayPathHelper;
use axy\arr\helpers\tests\BaseTestCase;

/** Covers ArrayPathHelper::get() */
class ArrayPathGetTest extends BaseTestCase
{
    #[DataProvider('providerGet')]
    public function testGet(mixed $input, mixed $path, mixed $default, mixed $expected): void
    {
        $this->assertSame($expected, ArrayPathHelper::get($input, $path, $default));
    }

    public static function providerGet(): array
    {
        $input = [
            'first' => [
                'second' => [
                    5,
                    [
                        'x' => 1,
                        'y' => 33,
                    ],
                    6,
                ],
                'third' => null,
            ],
        ];
        return [
            'path' => [
                $input,
                ['first', 'second', 1, 'y'],
                null,
                33,
            ],
            'default_found' => [
                $input,
                ['first', 'second', 1, 'y'],
                'default',
                33,
            ],
            'top' => [
                $input,
                'first',
                null,
                $input['first'],
            ],
            'not_found_null' => [
                $input,
                ['first', 'second', 'third', 'fourth'],
                null,
                null,
            ],
            'not_found_default' => [
                $input,
                ['first', 'second', 'third', 'fourth'],
                'default',
                'default',
            ],
            'null_is_ok' => [
                $input,
                ['first', 'third'],
                'default',
                null,
            ],
            'empty' => [
                $input,
                [],
                'default',
                $input,
            ],
            'no_array' => [
                5,
                'first',
                'default',
                'default',
            ],
            'empty_no_array' => [
                5,
                [],
                'default',
                5,
            ],
        ];
    }

    public function testGetOfAll(): void
    {
        $input = [
            'a' => ['x' => ['y' => 5]],
            'b' => ['x' => ['y' => null]],
            'c' => ['x' => ['z' => 5]],
        ];
        $this->assertSame([
            'a' => 5,
            'b' => null,
            'c' => 'default',
        ], ArrayPathHelper::getOfAll($input, ['x', 'y'], 'default'));
    }

    public function testClosure(): void
    {
        $input = [
            'a' => ['x' => 1, 'y' => 5],
            'b' => ['x' => 2, 'y' => 6],
            'c' => ['x' => 0, 'y' => 0],
            'd' => ['x' => 3, 'y' => 7],
        ];
        $path = function (array $element, int $default): mixed {
            return ($element['x'] + $element['y']) ?: $default;
        };
        $this->assertSame([
            'a' => 6,
            'b' => 8,
            'c' => 15,
            'd' => 10,
        ], ArrayPathHelper::getOfAll($input, $path, 15));
    }

    public function testReturnSelf(): void
    {
        $input = [
            'a' => ['x' => 1],
            'b' => ['x' => 2],
            'c' => ['x' => 3],
        ];
        $this->assertSame($input, ArrayPathHelper::getOfAll($input, [], 'default'));
        $this->assertSame($input, ArrayPathHelper::getOfAll($input, null, 'default'));
    }

    public function testWrongInput(): void
    {
        $this->assertSame([], ArrayPathHelper::getOfAll(3, null));
    }
}
