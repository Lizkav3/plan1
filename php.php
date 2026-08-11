<?php
function vd($la)
{
    var_dump($la);
    echo ("<br>");
}

$mysiv = [15, 11, 9, 12, 2];
vd($mysiv);
$n = count($mysiv);

vd($mysiv);

$a = 1;

//$mysiv[$a] = $mysiv[$a + 1];


vd($mysiv[$a]);
vd($mysiv[$a + 1]);
vd($mysiv);
vd("");


for ($h = 0; $h < ($n - 1); $h++) {
    $flag = 'ne zashli';

    for ($i = 0; $i < ($n - 1); $i++) {
        if ($mysiv[$i] < $mysiv[$i + 1]) {
            $f = $mysiv[$i];
            $mysiv[$i] = $mysiv[$i + 1];
            $mysiv[$i + 1] = $f;   
            $flag = 23;
        }
    }

    if ($flag == 'ne zashli') {
        break;
    }
    vd($mysiv);
}
?>

  
  <input type = "text">
 
 






<?php




exit;












vd("Privet");


var_dump($n);
echo ("<br>");

var_dump(49);

$lala = 5;
vd($lala);

var_dump("buratino");
echo ("<br>");

$lala = "buratino";
vd($lala);

vd("buratino");

vd(123);

vd($mysiv);
?>