<?php

$nodes = array_map(fn($n) => explode(',', $n), explode(PHP_EOL, trim(file_get_contents('input.txt'))));

$circuits = [];
foreach(edges() as $n => $pair) {
    list($a, $b) = array_map(fn($s) => (int)$s, explode('-', $pair));
    $ca = $cb = false;
    foreach($circuits as $cnum => $circuit) {
        if ($ca === false && in_array($a, $circuit)) $ca = $cnum;
        if ($cb === false && in_array($b, $circuit)) $cb = $cnum;
        if ($ca !== false && $cb !== false) break;
    }

    if ($ca === false && $cb === false) {
        $circuits[] = [$a, $b];
    } elseif ($ca === false) {
        $circuits[$cb][] = $a;
    } elseif ($cb === false) {
        $circuits[$ca][] = $b;
    } elseif ($ca !== $cb) {
        $circuits[$ca] = array_merge($circuits[$ca], $circuits[$cb]);
        unset($circuits[$cb]);
    }

    if ($n === 999) {
        usort($circuits, fn($a, $b) => count($b) <=> count($a));
        printf("Star 1: %s\n", count($circuits[0]) * count($circuits[1]) * count($circuits[2]));
    }

    if (count($circuits) === 1 && count(current($circuits)) === count($nodes)) break;
}

list($a, $b) = explode('-', $pair);
printf("Star 2: %s\n", $nodes[$a][0] * $nodes[$b][0]);

function edges(): array {
    global $nodes;
    
    $edges = [];
    for($a = 0; $a < count($nodes) - 1; $a++) for($b = $a + 1; $b < count($nodes); $b++)
        $edges[$a . '-' . $b] = sqrt(
            ($nodes[$a][0] - $nodes[$b][0]) ** 2 +
            ($nodes[$a][1] - $nodes[$b][1]) ** 2 +
            ($nodes[$a][2] - $nodes[$b][2]) ** 2
        );

    asort($edges);
    return array_keys($edges);
}