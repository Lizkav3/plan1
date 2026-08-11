<?php

if (1) {
    $id = $_GET['id'];
    $tel = $_GET['tel'];
    $name = $_GET['name'];
    $count = $_GET['count'];
    $price = $_GET['price'];

    if (!is_numeric($id)) {
        die('Поле  ID принимает только числовые значения, проверьте');
    }

    if (!is_numeric($tel) && ($tel<= 380200000000 || $tel >= 380999999999) ) {
        die('Не верный формат номера телефона');
    }

    echo("<br>id = " . $id);
    echo("<br>tel = " . $tel);
    echo("<br>name = " . $name);
    echo("<br>count = " . $count);
    echo("<br>price = " . $price);
    echo("<br>sum = " . ($count * $price));

} else {
    echo("Такого параметра нет, проверяйте");
}
































/* = pivo
$email = trtrtuyu
$price = zavtra
$discount = 968%


function nds($sum) {
    return $sum * 0.22;
}

$price = 243;

/*if (nds(100) == 20) {
    $pricePlusTaxes = $price + nds($price);
} else {
    echo("nds schitaet krivo! proverte file testing, func nds()");
}

10 2 4
= 22

5 8 12
= 'zdraate'
*/










