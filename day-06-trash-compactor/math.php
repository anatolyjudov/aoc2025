<?php

$data = array_map(
    fn($s) => preg_split("/\s+/", trim($s)),
    explode(PHP_EOL, trim(file_get_contents("input.txt")))
);

$ops = $data[count($data) - 1];
$data = array_slice($data, 0, -1);

$sum = 0;
for($i = 0; $i < count($data[0]); $i++) {
    $res = 0;
    foreach(array_column($data, $i) as $num)
        $res = ($res === 0) ? $num : (($ops[$i] === "+") ? $res + $num : $res * $num);
    $sum += $res;
}

print_r($sum);