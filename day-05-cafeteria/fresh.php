<?php

$file = file_get_contents("input.txt");

$ranges = array_map(
    fn($x) => explode("-", $x), 
    explode("\n", explode("\n\n", $file)[0])
);

$numbers = array_map("trim",  explode("\n", explode("\n\n", $file)[1]));

$fresh = 0;
foreach($numbers as $number) {
    foreach($ranges as $range) {
        if ($number >= $range[0] && $number <= $range[1]) {
            $fresh++;
            continue 2;
        }
    }
}

echo $fresh . PHP_EOL;

do {
    $hadOverlaps = false;

    $ranges = array_values($ranges);

    for($i = 0; $i < count($ranges) - 1; $i++) {
        for($o = $i + 1; $o < count($ranges); $o++) {
            if (($ranges[$o][0] >= $ranges[$i][0] && $ranges[$o][0] <= $ranges[$i][1])
             || ($ranges[$i][0] >= $ranges[$o][0] && $ranges[$i][0] <= $ranges[$o][1])) {
                $ranges[] = [min($ranges[$i][0], $ranges[$o][0]), max($ranges[$i][1], $ranges[$o][1])];
                unset($ranges[$i]);
                unset($ranges[$o]);
                $hadOverlaps = true;
                continue 3;
            }
        }
    }
} while ($hadOverlaps);

$sum = 0;
foreach ($ranges as $range) {
    $sum += $range[1] - $range[0] + 1;
}

echo $sum . PHP_EOL;