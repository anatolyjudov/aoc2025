<?php
ini_set('memory_limit', '10G');

$star1 = $star2 = 0;
$machines = [];

foreach(file("input.txt") as $line) {
    $parts = explode(' ', $line);
    $machines[] = [
        'ind' => array_map(
            fn($l) => match($l) {'#' => 1, default => 0},
            str_split(substr($parts[0], 1, strlen($parts[0]) - 2))
        ),
        'but' => array_map(
            fn($b) => array_map(fn($w) => (int)$w, explode(',', substr($b, 1, strlen($b) - 2))),
            array_slice($parts, 1, count($parts) - 2)
        ),
        'jol' => array_map(
            fn($j) => (int)$j,
            explode(',', trim($parts[count($parts) - 1], '{}'))
        )
    ];
}

foreach($machines as $n => $machine) {
    $min = minConfiguration($machine);
    echo 'Machine ' . $n . ': ' . $min . PHP_EOL; 
    $star1 += minConfiguration($machine);
}
echo $star1 . PHP_EOL;

foreach($machines as $n => $machine) {
    $min = minPower($machine['but'], $machine['jol']);
    echo 'Machine ' . $n . ' power: ' . $min . PHP_EOL; 
    $star2 += $min;
}
echo $star2 . PHP_EOL;

function minConfiguration(array $machine): int
{
    $q = new \Ds\Queue([[array_fill(0, count($machine['ind']), 0), 0]]);

    do {
        list($ind, $step) = $q->pop();
        $step = $step + 1;
        for($p = 0; $p < count($machine['but']); $p++) {
            $newInd = $ind;
            foreach($machine['but'][$p] as $wire) $newInd[$wire] = $newInd[$wire] === 0 ? 1 : 0;
            if ($newInd === $machine['ind']) return $step;
            $t = [$newInd, $step];
            $q->push($t);
        }
    } while (true);

    return $step;
}

function minPower(array $buttons, array $counters): int|false
{
    // let's solve it for the counter which is in less buttons
    $counterButtons = [];
    for($b = 0; $b < count($buttons); $b++) {
        foreach($buttons[$b] as $wire) {
            $counterButtons[$wire][] = $b;
        }
    }
    $minWire = $wire;
    foreach($counterButtons as $wire => $wiredButtons) {
        if (count($wiredButtons) < count($counterButtons[$minWire])) $minWire = $wire;
    }

    $solvingWire = $minWire;
    $solvingButtons = $counterButtons[$minWire];

    $min = false;
    // loop over all possible values of this buttons
    foreach(splitter($counters[$solvingWire], count($solvingButtons)) as $pushes) {
        //echo 'pushed ' . arr($pushes) . PHP_EOL;
        $newCounters = $counters;
        // for each possible value calculate counters
        foreach($solvingButtons as $n => $pushedButton) {
            $buttonPushed = $pushes[$n]; // times
            foreach($buttons[$pushedButton] as $wire) {
                $newCounters[$wire] = $newCounters[$wire] - $buttonPushed;
                // if some counters negative reject value
                if ($newCounters[$wire] < 0) continue 3;
            }
        }

        if (array_sum($newCounters) !== 0) {
            // calculate new counters and buttons
            $newButtons = [];
            foreach($buttons as $n => $button) {
                if (!in_array($n, $solvingButtons)) $newButtons[] = $button;
            }

            if (count($newButtons) === 0) {
                continue;
            }

            if ($min !== false && (min($newCounters) + $counters[$solvingWire]) >= $min) {
                continue;
            }
            foreach($newCounters as $cn => $cv) if ($cv > 0) {
                $found = false;
                foreach($newButtons as $wires) {
                    if (in_array($cn, $wires)) {
                        $found = true;
                        break;
                    }
                }
                if ($found === false) continue 2;
            }

            // recursive call, 
            $add = minPower($newButtons, $newCounters);
            // if return false reject value
            if ($add === false) continue;

        } else {
            $add = 0;
        }
        
        // else, add value returned by that recursive call to a possible value 
        $total = $counters[$solvingWire] + $add;
        // compare with minimal value
        if ($min === false || $total < $min) {
            $min = $total;
        }
    }

    return $min;
}

function splitter(int $sum, int $parts): \Generator {
    if ($parts === 0) die();
    if ($parts === 1)
        yield [$sum];
    else for($i = 0; $i < $sum + 1; $i++)
        foreach(splitter($sum - $i, $parts - 1) as $other) yield [$i, ...$other];
}

function arr($a) {
    if (!is_array($a)) return (string)$a;
    return '[' . implode(',', array_map("arr", $a)) . ']';
}