<?php

$row = 0;

foreach(explode(PHP_EOL, trim(file_get_contents("input.txt"))) as $line)
    $rolls[$row++] = str_split(trim($line));

$X = count($rolls[0]);
$Y = count($rolls);

function accessible(int $y, int $x): bool {
    global $rolls, $X, $Y;
    if ($rolls[$y][$x] === '.') return false;
    $n = 0;
    for($dy = -1; $dy <= 1; $dy++) for($dx = -1; $dx <= 1; $dx++) if (($dx !== 0) || ($dy !== 0)) {
        if ((($x + $dx) === $X) || (($y + $dy) === $Y) || (($x + $dx) < 0) || (($y + $dy) < 0)) continue;
        if ($rolls[$y + $dy][$x + $dx] === '@') $n++;
        if ($n === 4) return false;
    }
    return true;
}

$star1 = $star2 = 0;

for($y = 0; $y < $Y; $y++) for($x = 0; $x < $X; $x++) if (accessible($y, $x)) $star1++;

do {
    $last = true;
    for($y = 0; $y < $Y; $y++) for($x = 0; $x < $X; $x++) {
        if (!accessible($y, $x)) continue;
        $rolls[$y][$x] = '.';
        $last = false;
        $star2++;
    }
} while (!$last);

echo $star1 . PHP_EOL;
echo $star2 . PHP_EOL;