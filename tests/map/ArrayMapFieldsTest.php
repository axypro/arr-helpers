<?php

declare(strict_types=1);

namespace axy\arr\helpers\tests\map;

use axy\arr\helpers\ArrayMapHelper;
use axy\arr\helpers\tests\BaseTestCase;

/** Covers ArrayMapHelper::fields() */
class ArrayMapFieldsTest extends BaseTestCase
{
    private static array $testData = [
        'one' => [
            'schema' => [
                'identifier' => 'first',
                'value' => 1,
            ],
            'rate' => 5,
            'extra' => -5,
        ],
        'two' => [
            'schema' => [
                'identifier' => 'first',
                'value' => 2,
            ],
            'extra' => -6,
        ],
        'three' => [
            'schema' => [
                'identifier' => 'second',
                'value' => 3,
            ],
            'rate' => null,
            'extra' => -7,
        ],
        'four' => 5,
    ];

    public function testFieldsFull(): void
    {
        $this->assertSame([
            'one' => [
                'schema_id' => 'first',
                'rate' => 5,
            ],
            'two' => [
                'schema_id' => 'first',
                'rate' => 11,
            ],
            'three' => [
                'schema_id' => 'second',
                'rate' => null,
            ],
            'four' => [
                'schema_id' => null,
                'rate' => 11,
            ],
        ], ArrayMapHelper::fields(self::$testData, [
            'schema_id' => ['schema', 'identifier'],
            'rate' => null,
        ], [
            'rate' => 11,
        ]));
    }

    public function testFieldsList(): void
    {
        $this->assertSame([
            'one' => [
                'identifier' => 'first',
                'rate' => 5,
            ],
            'two' => [
                'identifier' => 'first',
                'rate' => null,
            ],
            'three' => [
                'identifier' => 'second',
                'rate' => null,
            ],
            'four' => [
                'identifier' => null,
                'rate' => null,
            ],
        ], ArrayMapHelper::fields(self::$testData, [
            ['schema', 'identifier'],
            'rate',
        ]));
    }

    public function testFieldsDefaults(): void
    {
        $this->assertSame([
            'one' => [
                'rate' => 5,
                'extra' => -5,
                'unk' => 5,
            ],
            'two' => [
                'rate' => 11,
                'extra' => -6,
                'unk' => 5,
            ],
            'three' => [
                'rate' => null,
                'extra' => -7,
                'unk' => 5,
            ],
            'four' => [
                'rate' => 11,
                'extra' => null,
                'unk' => 5,
            ],
        ], ArrayMapHelper::fields(self::$testData, defaults: [
            'rate' => 11,
            'extra' => null,
            'unk' => 5,
        ]));
    }
}
