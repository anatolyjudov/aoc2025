<?php

$data = explode(PHP_EOL, file_get_contents("input.txt"));

$ops = trim($data[count($data) - 1], PHP_EOL);
$data = array_slice($data, 0, -1);

$sum = 0;
$newExpression = true;
for($i = 0; $i < strlen($ops); $i++) {
    if ($newExpression) {
        $op = $ops[$i];
        $nums = [];
        $newExpression = false;
    }
    $num = 0;
    for($d = 0; $d < count($data); $d++) {
        if ($data[$d][$i] === ' ') continue;
        $num = $num * 10 + $data[$d][$i];
    }
    if ($num !== 0) $nums[] = $num;
    if ($num === 0 || $i === strlen($ops) - 1) {
        $res = 0;
        foreach($nums as $num)
            $res = ($res === 0) ? $num : (($op === "+") ? $res + $num : $res * $num);
        $sum += $res;
        $newExpression = true;
    }
}

echo $sum;