<?php

$ranges = array_map(
    fn($range) => explode('-', $range),
    explode(',', trim(file_get_contents("input.txt")))
);

echo array_reduce($ranges, fn($sum, $range) => $sum += calculate($range[0], $range[1])) . PHP_EOL;

function calculate($begin, $end): int
{
    if (strlen($begin) === strlen($end) - 1) {
        $border = 10 ** strlen($begin);
        return calculate($begin, $border - 1) + calculate($border, $end);
    }

    $sum = 0;
    $len = strlen($begin);

    for($splitSize = 1; $splitSize <= intdiv($len, 2); $splitSize++) {
        if ($len % (int)$splitSize !== 0) continue;
        $split = substr($begin, 0, $splitSize);

        while(($can = str_repeat($split, intdiv($len, $splitSize))) <= $end) {
            if ($can >= $begin && !invalid($split)) $sum += $can;
            $split++;
        }
    }

    return $sum;
}

function invalid(string $num): bool
{
    for($s = 1; $s <= intdiv(strlen($num), 2); $s++) {
        if (strlen($num) % $s !== 0) continue;
        if (str_repeat(substr($num, 0, $s), intdiv(strlen($num), $s)) === $num) return true;
    }

    return false;
}