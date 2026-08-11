<?php

$alphabet = '0123456789';

$repeat = str_repeat($alphabet, 11);

$shuffle = str_shuffle($repeat);
$randomString100 = substr($shuffle, 0, 100);
echo $randomString100 . '<br>';

$shuffle1 = str_shuffle($alphabet);
$randomString2 = substr($shuffle1, 0, 2);
echo $randomString2 . '<br>';

if (str_contains($randomString100, $randomString2)) {
    echo("Nachlo<br>");
} else {
    echo("NE Nachlo<br>");
}

function sum($text) {
    $s1 = 3+8;
    echo($text . $s1);
}

sum('lalala');

function fullName($name,$surname)
{

    $full1 = $surname . ' ' . $name . "<br>";
    echo $full1;
}
fullName('vasya','pupkin');
fullName('dima','ivanov');

function work ($perviy, $vtoroi, $tretiy)
{
    $q1= str_shuffle($perviy);
    $q2=($vtoroi * 100);
    echo $tretiy . $q1 . mb_strtolower($tretiy) . $q2;
}
work('abcde','8','KY');



