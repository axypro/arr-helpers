<?php

declare(strict_types=1);

namespace axy\arr\helpers\tests\map;

use PHPUnit\Framework\Attributes\DataProvider;
use axy\arr\helpers\ArrayMapHelper;
use axy\arr\helpers\tests\BaseTestCase;

/** Covers ArrayMapHelper::column() */
class ArrayMapColumnTest extends BaseTestCase
{
    #[DataProvider('providerColumn')]
    public function testColumn(mixed $input, mixed $path, mixed $default, mixed $expected): void
    {
        $this->assertSame($expected, ArrayMapHelper::column($input, $path, $default));
    }

    public static function providerColumn(): array
    {
        $input = [
            'one' => ['x' => ['y' => 3], 'z' => 4],
            'two' => ['x' => ['y' => [3, 4]], 'z' => 5],
            'three' => ['x' => ['y' => null]],
            'four' => [['x' => 3], 'z' => 7],
            'five' => 4,
        ];
        return [
            'nested' => [
                $input,
                ['x', 'y'],
                'default',
                [
                    'one' => 3,
                    'two' => [3, 4],
                    'three' => null,
                    'four' => 'default',
                    'five' => 'default',
                ],
            ],
            'top' => [
                $input,
                ['z'],
                'default',
                [
                    'one' => 4,
                    'two' => 5,
                    'three' => 'default',
                    'four' => 7,
                    'five' => 'default',
                ],
            ],
            'whole' => [
                $input,
                null,
                null,
                $input,
            ],
            'no_array' => [
                3,
                ['x', 'y'],
                null,
                [],
            ],
        ];
    }
}
