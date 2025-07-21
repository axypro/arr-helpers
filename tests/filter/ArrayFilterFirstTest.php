<?php

declare(strict_types=1);

namespace axy\arr\helpers\tests\filter;

use axy\arr\helpers\ArrayFilterHelper;
use axy\arr\helpers\tests\BaseTestCase;

/** Covers ArrayFilterHelper::first() and ::any() */
class ArrayFilterFirstTest extends BaseTestCase
{
    public function testAll(): void
    {
        $input = [
            ['x' => 0, 'y' => 1],
            ['x' => 1, 'y' => 2],
            ['x' => 2, 'y' => 3],
            ['x' => 3, 'y' => 4],
        ];
        $this->assertSame(0, ArrayFilterHelper::first($input));
        $this->assertTrue(ArrayFilterHelper::any($input));
        $this->assertSame(1, ArrayFilterHelper::first($input, path: 'x'));
        $this->assertTrue(ArrayFilterHelper::any($input, path: 'x'));
        $this->assertSame(0, ArrayFilterHelper::first($input, path: 'y'));
        $this->assertTrue(ArrayFilterHelper::any($input, path: 'y'));
        $this->assertSame(null, ArrayFilterHelper::first($input, path: 'z'));
        $this->assertFalse(ArrayFilterHelper::any($input, path: 'z'));
        $this->assertSame(0, ArrayFilterHelper::first($input, path: 'z', default: 5));
        $this->assertTrue(ArrayFilterHelper::any($input, path: 'z', default: 5));

        $callback = function (int $value, string $key, array $element, int $extra): bool {
            return ($value === $extra);
        };
        $this->assertSame(2, ArrayFilterHelper::first($input, callback: $callback, path: 'x', extra: 2));
        $this->assertTrue(ArrayFilterHelper::any($input, callback: $callback, path: 'x', extra: 2));
        $this->assertSame(1, ArrayFilterHelper::first($input, callback: $callback, path: 'y', extra: 2));
        $this->assertTrue(ArrayFilterHelper::any($input, callback: $callback, path: 'y', extra: 2));
        $this->assertSame(null, ArrayFilterHelper::first($input, callback: $callback, path: 'x', extra: 22));
        $this->assertFalse(ArrayFilterHelper::any($input, callback: $callback, path: 'x', extra: 22));

        $this->assertSame(3, ArrayFilterHelper::first($input, callback: 3, path: 'x'));
        $this->assertTrue(ArrayFilterHelper::any($input, callback: 3, path: 'x'));
        $this->assertSame(2, ArrayFilterHelper::first($input, callback: 3, path: 'y'));
        $this->assertTrue(ArrayFilterHelper::any($input, callback: 3, path: 'y'));
        $this->assertSame(null, ArrayFilterHelper::first($input, callback: 33, path: 'y'));
        $this->assertFalse(ArrayFilterHelper::any($input, callback: 33, path: 'x'));
    }

    public function testWrongInput(): void
    {
        $this->assertSame(null, ArrayFilterHelper::first(3));
        $this->assertFalse(ArrayFilterHelper::any(3));
    }
}
