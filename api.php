<?php
//server api
// komanda i parametri
// get zapros text rezult
$number1 = $_GET['number1'];
$number2  = $_GET['number2'];
$command = $_GET['command'];
echo("<br>number1 = " . $number1);
echo("<br>". $command);
echo("<br>number2 = " . $number2);

if($command == "summ"){
    $rezult=$number1+$number2;
}elseif ($command == "add"){
    $rezult=$number1*$number2;
}else{
    $rezult="OSIBKA";
}
echo("<br>rezult = " . $rezult);

?>