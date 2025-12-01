<?php

$inputLines = file('input.txt');

$star1 = $star2 = 0;
$pos = 50;

foreach($inputLines as $line) {
    $was0 = $pos === 0;

    $pos = $pos + (($line[0] === 'R') ? 1 : -1) * (int) substr(trim($line), 1);

    if ($pos > 99) {
        $star2 += intdiv($pos, 100);
        $pos = $pos % 100;
    } elseif ($pos <= 0) {
        if (!$was0) $star2++;
        $star2 += intdiv(-1 * $pos, 100);
        $pos = (100 + $pos % 100) % 100;
    }

    $star1 += ($pos === 0) ? 1 : 0;
}

echo "Star1: " . $star1 . PHP_EOL . "Star2: " . $star2 . PHP_EOL;