<?php

echo array_reduce(
    array_map(
        fn($range) => explode('-', $range),
        explode(',', trim(file_get_contents("input.txt")))
    ),
    fn($sum, $range) => $sum += calculate($range[0], $range[1])
) . PHP_EOL;

function calculate($begin, $end): int
{
    if (strlen($begin) === strlen($end) - 1) {
        $border = 10 ** strlen($begin);
        return calculate($begin, $border - 1) + calculate($border, $end);
    }

    if (strlen($begin) % 2 !== 0) return 0;

    $sum = 0;
    $split = substr($begin, 0, strlen($begin) >> 1);

    while(($can = str_repeat($split++, 2)) <= $end)
        $sum += ($can >= $begin) ? $can : 0;

    return $sum;
}