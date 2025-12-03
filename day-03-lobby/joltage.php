<?php

echo array_reduce(
    file("input.txt"),
    fn($sum, $line) => $sum += getMax(trim($line), 12)
);

function getMax(string $line, $length = 2): int
{
    list($max, $r) = find($line, $length - 1);

    for($i = $length - 2; $i >= 0; $i--) {
        list($m, $r) = find($r, $i);
        $max .= $m;
    }

    return (int)$max;
}

function find(string $line, $ignore = 0): array
{
    $max = max(str_split($ignore > 0 ? substr($line, 0, -1 * $ignore) : $line));
    $rest = substr($line, strpos($line, $max) + 1);
    return [$max, $rest];
}