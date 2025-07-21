<?php

declare(strict_types=1);

namespace axy\arr\helpers\tests\keys;

use axy\arr\helpers\ArrayKeysHelper;
use axy\arr\helpers\tests\BaseTestCase;

/** Covers ArrayKeysHelper::toKey() */
class ArrayKeysToKeyTest extends BaseTestCase
{
    public function testToKey(): void
    {
        $input = [
            [
                'user' => ['id' => 10, 'age' => 25],
                'group' => ['id' => 10],
            ],
            [
                'user' => ['id' => 11, 'age' => 35],
                'group' => ['id' => 11],
            ],
            [
                'user' => ['id' => 12],
                'group' => ['id' => 12],
            ],
        ];
        $this->assertSame($input, ArrayKeysHelper::toKey($input));
        $this->assertSame([
            10 => $input[0],
            11 => $input[1],
            12 => $input[2],
        ], ArrayKeysHelper::toKey($input, ['user', 'id']));
        $this->assertSame([
            10 => 25,
            11 => 35,
            12 => 18,
        ], ArrayKeysHelper::toKey($input, ['user', 'id'], ['user', 'age'], 18));
        $input[] = [ // collision
            'user' => ['id' => 11, 'age' => 28],
        ];
        $input[] = [
            'user' => ['id' => 20, 'age' => 28],
        ];
        $input[] = [
            'user' => ['age' => 29],
        ];
        $input[] = [
            'user' => ['id' => [], 'age' => 30],
        ];
        $this->assertSame([
            10 => 25,
            11 => 35,
            12 => 18,
            20 => 28,
        ], ArrayKeysHelper::toKey($input, ['user', 'id'], ['user', 'age'], 18));
    }

    public function testWrongInput(): void
    {
        $this->assertSame([], ArrayKeysHelper::toKey(3));
    }
}
