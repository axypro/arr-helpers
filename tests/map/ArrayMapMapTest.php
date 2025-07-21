<?php

declare(strict_types=1);

namespace axy\arr\helpers\tests\map;

use axy\arr\helpers\ArrayMapHelper;
use axy\arr\helpers\tests\BaseTestCase;

/** Covers ArrayMapHelper::map() */
class ArrayMapMapTest extends BaseTestCase
{
    public function testSimpleMap(): void
    {
        $input = [
            'a' => 1,
            'b' => 2,
            'c' => 3,
            'd' => 4,
        ];
        $callback = function (mixed $value, string $key, mixed $element, mixed $extra): string {
            return implode(':', [
                $value,
                $key,
                $element,
                $extra,
            ]);
        };
        $this->assertEquals([
            'a' => '1:a:1:e',
            'b' => '2:b:2:e',
            'c' => '3:c:3:e',
            'd' => '4:d:4:e',
        ], ArrayMapHelper::map($input, $callback, extra: 'e'));
    }

    public function testPath(): void
    {
        $input = [
            'one' => ['a' => ['b' => [1, 2]]],
            'two' => ['a' => ['b' => [3, 4]]],
            'three' => [],
        ];
        $callback = function (mixed $value, string $key, mixed $element): array {
            if (is_array($value)) {
                $value = array_sum($value);
            } else {
                $value = 0;
            }
            $element['sum'] = $value;
            return $element;
        };
        $this->assertSame([
            'one' => ['a' => ['b' => [1, 2]], 'sum' => 3],
            'two' => ['a' => ['b' => [3, 4]], 'sum' => 7],
            'three' => ['sum' => 9],
        ], ArrayMapHelper::map($input, $callback, path: ['a', 'b'], default: [4, 5]));
    }
}
