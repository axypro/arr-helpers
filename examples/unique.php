<?php

declare(strict_types=1);

use axy\arr\helpers\ArrayUniqueHelper;

require_once __DIR__ . '/../index.php';

$arr = [1, 2, 3, 2, 4, 2, 5];
echo 'unique(' . json_encode($arr) . '):' . PHP_EOL;
print_r(ArrayUniqueHelper::unique($arr));

echo 'unique(' . json_encode($arr) . ') as dictionary:' . PHP_EOL;
print_r(ArrayUniqueHelper::unique($arr, isDictionary: true));

$arr = ['a' => 1, 'b' => 2, 'c' => 1, 'd' => 3];
echo 'unique(' . json_encode($arr) . ') as dictionary:' . PHP_EOL;
print_r(ArrayUniqueHelper::unique($arr));

$arr = ['a' => ['value' => 5], 'b' => [], 'c' => ['value' => 10], 'd' => ['value' => 5], 'e' => ['value' => 7]];
echo 'unique of nested value:' . PHP_EOL;
print_r(ArrayUniqueHelper::unique($arr, path: 'value', default: 7));

$arr = [
    ['id' => 1, 'value' => [2, 2]],
    ['id' => 2, 'value' => [2, 3]],
    ['id' => 3, 'value' => [3, 1]],
];
$path = function ($arr) {
    return array_sum($arr['value'] ?? []);
};
echo 'unique with calculate value in the path argument' . PHP_EOL;
print_r(ArrayUniqueHelper::unique($arr, $path));
