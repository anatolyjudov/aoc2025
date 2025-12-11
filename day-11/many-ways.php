<?php
ini_set('memory_limit', '2G');

$edges = [];
foreach(file("input.txt") as $line) {
    $parts = explode(" ", str_replace(': ', ' ', trim($line)));
    if (!isset($edges[$parts[0]])) $edges[$parts[0]] = [];
    foreach(array_slice($parts, 1) as $n) if (!in_array($n, $edges[$parts[0]])) $edges[$parts[0]][] = $n;
}

function ways($from, $to) {
    global $edges;

    $paths = [];
    $q = new \Ds\Queue([$from]);
    do {
        $node = $q->pop();
        if ($node === $to || $node === 'out') continue;
        foreach($edges[$node] as $next) {
            if (!isset($paths[$next])) $paths[$next] = 0;
            $paths[$next] += 1;
            $q->push($next);
        }
    } while ($q->count() > 0);

    return isset($paths[$to]) ? $paths[$to] : 0;
}

function ways2($from, $to) {
    global $edges;
    static $cache;
    $key = $from . '-' . $to;
    if (isset($cache[$key])) return $cache[$key];

    if ($from === $to) return [[$to]];
    
    $ways = [];

    if (isset($edges[$from]))
        foreach($edges[$from] as $next)
            foreach(ways2($next, $to) as $nextWay) $ways[] = [$from, ...$nextWay];

    if (count($ways) < 10) $cache[$key] = $ways;
    return $ways;
}

echo 'Star 1: ' . ways('you', 'out') . PHP_EOL;
$star2 = count(ways2('svr', 'fft')) * count(ways2('fft', 'dac')) * count(ways2('dac', 'out'));
echo 'Star 2: ' . $star2 . PHP_EOL;