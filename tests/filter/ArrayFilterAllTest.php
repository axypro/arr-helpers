<?php

declare(strict_types=1);

namespace axy\arr\helpers\tests\filter;

use axy\arr\helpers\ArrayFilterHelper;
use axy\arr\helpers\tests\BaseTestCase;

/** Covers ArrayFilterHelper::all() */
class ArrayFilterAllTest extends BaseTestCase
{
    public function testAll(): void
    {
        $input = [
            ['x' => 0, 'y' => 1],
            ['x' => 1, 'y' => 2],
            ['x' => 2, 'y' => 3],
            ['x' => 3, 'y' => 4],
        ];
        $this->assertTrue(ArrayFilterHelper::all($input));
        $this->assertTrue(ArrayFilterHelper::all($input, path: 'y'));
        $this->assertFalse(ArrayFilterHelper::all($input, path: 'x'));

        $callback = function (int $value): bool {
            return ($value < 4);
        };
        $this->assertTrue(ArrayFilterHelper::all($input, $callback, path: 'x'));
        $this->assertFalse(ArrayFilterHelper::all($input, $callback, path: 'y'));

        $this->assertFalse(ArrayFilterHelper::all($input, 2, path: 'y'));
    }
}
