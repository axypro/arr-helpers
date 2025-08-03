<?php

declare(strict_types=1);

use axy\arr\helpers\ArraySortHelper;

require_once __DIR__ . '/../index.php';

$arr = [1, 2, 5, 2, 3];
echo "sort(" . json_encode($arr) . ")" . PHP_EOL;
print_r(ArraySortHelper::sort($arr));

$arr = [1, 2, 5, 2, 3];
echo "sort(" . json_encode($arr) . ") as dictionary" . PHP_EOL;
print_r(ArraySortHelper::sort($arr, isDictionary: true));

$arr = ['a' => 1, 'b' => 2, 'c' => 1, 'd' => 3];
echo "sort(" . json_encode($arr) . ")" . PHP_EOL;
print_r(ArraySortHelper::sort($arr, isDictionary: true));

$arr = [
    ['id' => 1, 'values' => [3, 5, 4, 6]],
    ['id' => 2, 'values' => [2, 4, 2, 7]],
    ['id' => 3, 'values' => [8, 8, 1, 10]],
    ['id' => 4, 'values' => [2, 2, 4, 7]],
];
$sorter = function (array $a, array $b): int {
    $sum1 = array_sum($a ?? []);
    $sum2 = array_sum($b ?? []);
    if ($sum1 > $sum2) {
        return 1;
    }
    if ($sum1 < $sum2) {
        return -1;
    }
    return 0;
};
echo 'sort with user function (asc):' . PHP_EOL;
print_r(ArraySortHelper::sort($arr, path: 'values', sorter: $sorter));

echo 'sort with user function (desc):' . PHP_EOL;
print_r(ArraySortHelper::sort($arr, path: 'values', desc: true, sorter: $sorter));

$path = function (array $item): int {
    return array_sum($item['values'] ?? []);
};
echo 'sort with path closure:' . PHP_EOL;
print_r(ArraySortHelper::sort($arr, path: $path));
