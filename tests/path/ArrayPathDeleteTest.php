<?php

declare(strict_types=1);

namespace axy\arr\helpers\tests\path;

use PHPUnit\Framework\Attributes\DataProvider;
use axy\arr\helpers\ArrayPathHelper;
use axy\arr\helpers\tests\BaseTestCase;

/** Covers ArrayPathHelper::set() */
class ArrayPathDeleteTest extends BaseTestCase
{
    #[DataProvider('providerDelete')]
    public function testDelete(mixed $input, mixed $path, array $expected): void
    {
        $this->assertSame($expected, ArrayPathHelper::delete($input, $path));
    }

    public static function providerDelete(): array
    {
        $input = [
            'a' => ['b' => ['c' => 3, 'd' => 4]],
            'd' => 5,
        ];
        return [
            'not_found_1' => [
                $input,
                ['a', 'b', 'c', 'd'],
                $input,
            ],
            'not_found_2' => [
                $input,
                'e',
                $input,
            ],
            'not_found_3' => [
                $input,
                ['e', 'f'],
                $input,
            ],
            'top_1' => [
                $input,
                'a',
                ['d' => 5],
            ],
            'top_2' => [
                $input,
                'd',
                ['a' => $input['a']],
            ],
            'nested_1' => [
                $input,
                ['a', 'b', 'c'],
                ['a' => ['b' => ['d' => 4]], 'd' => 5],
            ],
            'nested_2' => [
                $input,
                ['a', 'b'],
                ['a' => [], 'd' => 5],
            ],
            'all' => [
                $input,
                [],
                [],
            ],
            'no_array' => [
                5,
                'a',
                [],
            ],
        ];
    }

    public function testDeleteFromAll(): void
    {
        $input = [
            'one' => ['a' => ['b' => ['c' => 3], 'd' => 4]],
            'two' => ['d' => 4],
            'three' => 'x',
        ];
        $this->assertSame([
            'one' => ['a' => ['d' => 4]],
            'two' => ['d' => 4],
            'three' => [],
        ], ArrayPathHelper::deleteFromAll($input, ['a', 'b']));
    }
}
