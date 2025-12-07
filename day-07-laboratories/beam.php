<?php

$file = array_map(fn($s) => trim($s), file("input.txt"));

$file[0] = str_replace('S', '|', $file[0]);

$star1 = 0;
$timelines = [0 => array_fill(0, strlen($file[0]), 0)];
$timelines[0][strpos($file[0], '|')] = 1;
for($s = 1; $s < count($file); $s++) {
	if (strlen($file[$s]) === 0) break;
	$timelines[$s] = array_fill(0, strlen($file[$s]), 0);
	for($x = 0; $x < strlen($file[0]); $x++) {
		if ($file[$s - 1][$x] === '.') continue;
		if ($file[$s - 1][$x] === '^') continue;
		if ($file[$s - 1][$x] === '|') {
			if ($file[$s][$x] === '^') {
				$star1++;
			}
			if ($file[$s][$x] === '|') {
				$timelines[$s][$x] += $timelines[$s - 1][$x];
				continue;
			}
			if ($file[$s][$x] === '.') {
				$file[$s][$x] = '|';
				$timelines[$s][$x] = $timelines[$s - 1][$x];
				continue;
			}
			if ($x > 0) {
				$file[$s][$x - 1] = '|';
				$timelines[$s][$x - 1] += $timelines[$s - 1][$x];
			}
			if ($x < strlen($file[0]) - 1) {
				$file[$s][$x + 1] = '|';
				$timelines[$s][$x + 1] += $timelines[$s - 1][$x];
			}
		}
	}
}

//print_r($file);
//print_r($timelines[count($file) - 1]);

echo $star1 . PHP_EOL;
echo array_sum($timelines[count($file) - 1]) . PHP_EOL;