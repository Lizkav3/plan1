<?php

echo('hello');
$arr = [];
$r = "<br>";
$r = "\r\n";

for ($i = 0; $i < 5; $i++) {
    $arr[] = rand(0, 99);
    echo("{$r} i={$i}");
    echo("{$r}" . json_encode($arr));
}
echo("\r\n");
var_dump($arr);
