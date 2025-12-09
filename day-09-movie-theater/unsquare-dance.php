<?php

$reds = array_map(
    fn($l) => array_map(
        fn($n) => (int)$n,
        explode(',', $l)
    ),
    explode(PHP_EOL, trim(file_get_contents("input.txt")))
);

$vedges = $hedges = [];
for($n = 0; $n < count($reds); $n++) {
    $edge = [$n === 0 ? $reds[count($reds) - 1] : $reds[$n - 1], $reds[$n]];
    
    if ($edge[0][0] === $edge[1][0])
        $vedges[$edge[0][0]][] = [min($edge[0][1], $edge[1][1]), max($edge[0][1], $edge[1][1])];
    elseif ($edge[0][1] === $edge[1][1])
        $hedges[$edge[0][1]][] = [min($edge[0][0], $edge[1][0]), max($edge[0][0], $edge[1][0])];
}

$star1 = $star2 = 0;

for($a = 0; $a < count($reds) - 1; $a++) for($b = $a; $b < count($reds); $b++) {
    $square = (abs($reds[$a][0] - $reds[$b][0]) + 1) * (abs($reds[$a][1] - $reds[$b][1]) + 1);
    if ($square > $star1) $star1 = $square;

    $x0 = min($reds[$a][0], $reds[$b][0]);
    $x1 = max($reds[$a][0], $reds[$b][0]);
    $y0 = min($reds[$a][1], $reds[$b][1]);
    $y1 = max($reds[$a][1], $reds[$b][1]);

    foreach($vedges as $vx => $fvedges) if ($vx > $x0 && $vx < $x1)
        foreach($fvedges as $fvedge)
            if (!($fvedge[0] >= $y1 || $fvedge[1] <= $y0))
                continue 3;
        
    foreach($hedges as $vy => $fhedges) if ($vy > $y0 && $vy < $y1)
        foreach($fhedges as $fhedge)
            if (!($fhedge[0] >= $x1 || $fhedge[1] <= $x0))
                continue 3;

    if ($square > $star2) $star2 = $square;
}

echo $star1 . PHP_EOL;
echo $star2 . PHP_EOL;