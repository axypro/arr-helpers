<?php

declare(strict_types=1);

namespace axy\arr\helpers\tests;

use PHPUnit\Framework\Attributes\DataProvider;
use axy\arr\helpers\ArrayConverterHelper;
use ArrayIterator;

class ArrayConverterTest extends BaseTestCase
{
    #[DataProvider('providerToNativeArray')]
    public function testToNativeArray(mixed $input, array $expected): void
    {
        $this->assertSame($expected, ArrayConverterHelper::toNativeArray($input));
    }

    public static function providerToNativeArray(): array
    {
        return [
            'list' => [
                [3, 2, 5],
                [3, 2, 5],
            ],
            'dictionary' => [
                ['a' => 1, 'b' => 2],
                ['a' => 1, 'b' => 2],
            ],
            'traversable' => [
                new ArrayIterator(['a' => 3, 'b' => 4]),
                ['a' => 3, 'b' => 4],
            ],
            'object' => [
                new readonly class (4, 7) {
                    public function __construct(
                        public int $x,
                        public int $y,
                    ) {
                    }
                },
                ['x' => 4, 'y' => 7],
            ],
            'null' => [
                null,
                [],
            ],
            'scalar' => [
                'string',
                ['string'],
            ],
            'false' => [
                false,
                [false],
            ],
        ];
    }
}
