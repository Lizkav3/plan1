<?php

/**
 * Проверяет в архивных базах старые записи о счете и пополнениях для водителя и телефона.
 * http://157.180.61.126:15480/sandbox/account.php?driver=13101&phone=380632694255&list=36
 */


/*
Основная задача: научится ценить качественное ТЗ. 

Второстепенная задача: научится понимать мутное ТЗ. 

Третьестепенная задача: выполнить ТЗ. 

--------------------------------------------------
Пример совсем мутного тех-задания:
    Тут неудобно клацать и писать сверху надо.

--------------------------------------------------
Пример мутного тех-задания:
    Щас неудобно, надо чтоб можно вводить было и чтоб не листать долго.

--------------------------------------------------
Пример качественного тех-задания:
    01. Запустить этот скрипт на своем тестовом сервере.
    02. Ничего не сломать.
    03. Понять что тут происходит и как это работает.
    04. Сделать так, чтоб если в запросе "list=" значение превышает количество элементов массива - выбирался самый последний элемент.
    05. На страницу добавить поле ввода "телефон".
    06. На страницу добавить поле ввода "id водителя".
    07. На страницу добавить кнопку "искать".
    08. Кнопка "искать" проверяет содержимое "телефон" и "id водителя" и переадресует страницу на гет запрос с введенными данными.
    09. Под кнопками "РАНЬШЕЕ" и "ПОЗЖЕЕ" добавить ссылки на элементы массива и подписать в формате "мм.гг - мм.гг".
    10. Все запросы и переадресации этой страницы открывать в той же вкладке.
    11. При большом желании - сделать красиво. Но не обязательно. Достаточно сделать ровно.
    12. Проанализировать допущенные ошибки потребовавшие времени на исправление.
    13. Порадоваться за свои успехи.
    
    З.ы. 
    Функции для доступа к базам данных намеренно отключены в этом файле.
*/



ini_set("memory_limit", "1024M"); //можно занять памяти
error_reporting(-1); //-1 - выводить любые ошибки и предупреждения; 0 - ничего не выводить
set_time_limit(300);

/**********************************************************/
/* отправляет запрос в БД, выдает ответ в виде обьекта */
function fb($query)
{
    $answer  = '';

    //обработка результата
    if (mb_substr($answer, 0, 14, 'utf-8') == '{"RESULT":"ok"') { //запрос выполнен успешно
        $answer = json_decode($answer);
        if (isset($answer->ROWS)) { //вернуть выборку
            $answer = $answer->ROWS;
        } else { //запрос без выборки
            $answer = 1;
        }
    } else { //ошибка выполнения запроса
        $answer = false;
    }

    return $answer;
}


/**********************************************************/
/* отправляет запрос в архивную БД, выдает ответ в виде обьекта */
function fb_arc($db, $query)
{
    $answer = null;
    $db = '127.0.0.1/15450:/var/db/arc/t6.' . $db . '.fdb';
    $username = 'SYSDBA';
    $password = 'masterkey';
    $link = '';

    if (gettype($link) == 'resource') {
        $rules = IBASE_READ | IBASE_COMMITTED | IBASE_REC_VERSION | IBASE_WAIT;
        $transaction = ibase_trans($rules, $link);
        $result = ibase_query($transaction, $query); //выполнить запрос

        if (gettype($result) == 'resource') {
            $answer = array();

            while ($row = ibase_fetch_object($result)) {
                $answer[] = $row;
            }
            ibase_free_result($result); //освобождаем результат в БД

        } else {
            $answer = $result;
        }

        if (ibase_commit($transaction) === false) {
            $answer = false;
        }
        ibase_close($link); //отключаемся от БД и от хоста
    }

    return $answer; //выдаем ответ
}


/**********************************************************/
$dbArr = [
    ['2017-03-01', '2017-04-01', '2017-05-01'],
    ['2017-06-01', '2017-07-01', '2017-08-01'],
    ['2017-09-01', '2017-10-01', '2017-11-01'],
    ['2017-12-01', '2018-01-01', '2018-02-01'],

    ['2018-03-01', '2018-04-01', '2018-05-01'], //и=4

    ['2018-06-01', '2018-07-01', '2018-08-01'], //i + 1
    ['2018-09-01', '2018-10-01', '2018-11-01'],
    ['2018-12-01', '2019-01-01', '2019-02-01'],
    ['2019-03-01', '2019-04-01', '2019-05-01'],
    ['2019-06-01', '2019-07-01', '2019-08-01'],
    ['2019-09-01', '2019-10-01', '2019-11-01'],
    ['2019-12-01', '2020-01-01', '2020-02-01'],
    ['2020-03-01', '2020-04-01', '2020-05-01'],
    ['2020-06-01', '2020-07-01', '2020-08-01'],
    ['2020-09-01', '2020-10-01', '2020-11-01'],
    ['2020-12-01', '2021-01-01', '2021-02-01'],
    ['2021-03-01', '2021-04-01', '2021-05-01'],
    ['2021-05-13', '2021-06-19', '2021-07-01'],
    ['2021-08-01', '2021-09-01', '2021-10-01'],
    ['2021-11-01', '2021-12-01', '2022-01-01'],
    ['2022-02-01', '2022-03-01', '2022-04-01'],
    ['2022-05-01', '2022-06-01', '2022-07-01'],
    ['2022-08-01', '2022-09-01', '2022-10-01'],
    ['2022-11-01', '2022-12-01', '2023-01-01'],
    ['2023-02-01', '2023-03-01', '2023-04-01'],
    ['2023-05-01', '2023-06-01', '2023-07-01'],
    ['2023-08-01', '2023-09-01', '2023-10-01'],
    ['2023-11-01', '2023-12-01', '2024-01-01'],
    ['2024-02-01', '2024-03-01', '2024-04-01'],
    ['2024-05-01', '2024-06-01', '2024-07-01'],
    ['2024-08-01', '2024-09-01', '2024-10-01'],
    ['2024-11-01', '2024-12-01', '2025-01-01'],
    ['2025-02-01', '2025-03-01', '2025-04-01'],
    ['2025-05-01', '2025-06-01', '2025-07-01'],
    ['2025-08-01', '2025-09-01', '2025-10-01'],
    ['2025-11-01', '2025-12-01', '2026-01-01'],
    ['2026-02-01', '2026-03-01']
];

$r = "</br>";
$driver = intval($_GET['driver']);
//$driver = 11775;
$phone = floatval($_GET['phone']);
//$phone = 380632694255;
$pays = new stdClass();

if (isset($_GET['list'])) {
    $list = intval($_GET['list']);
} else {
    $list = count($dbArr);
}
//var_dump($dbArr);
if ($list < 0) {
    $list = 0;
}

if ($list >= count($dbArr)) {
    $list = count($dbArr) - 1;
}
//var_dump($list);
foreach ($dbArr[$list] as $db) {
    if ($driver > 0) {
        $answer = fb_arc($db, "select * from sg_pays_user({$driver}, 1, 2100000000);");
        //пример ответа:         $answer = json_decode('[{"YID":89566899,"YDATE":1769723055,"YORDER":150424334,"YSUM":-4732,"YORIGIN":20161,"YCASH":"","YTYPE":"tariff","YUSER":null,"YHOST":"","YNOTE":"комиссия с водителя за заказ: 1 слободская, д 43, по 2"},{"YID":89591121,"YDATE":1769786522,"YORDER":0,"YSUM":40000,"YORIGIN":15429,"YCASH":"","YTYPE":"transfer","YUSER":null,"YHOST":"","YNOTE":"terminal-N-1-100024 (400,00uah)"},{"YID":89621880,"YDATE":1769876070,"YORDER":0,"YSUM":-200,"YORIGIN":55429,"YCASH":"","YTYPE":"event","YUSER":null,"YHOST":"","YNOTE":"абонплата за пользование программой"},{"YID":89622221,"YDATE":1769876765,"YORDER":150448606,"YSUM":-1341,"YORIGIN":55229,"YCASH":"","YTYPE":"tariff","YUSER":null,"YHOST":"","YNOTE":"комиссия с водителя за заказ: генерала попеля, д 119, по чд"},{"YID":89623076,"YDATE":1769878716,"YORDER":150448970,"YSUM":-1431,"YORIGIN":53888,"YCASH":"","YTYPE":"tariff","YUSER":null,"YHOST":"","YNOTE":"комиссия с водителя за заказ: проспект корабелів, д 18а, по 1"},{"YID":89626826,"YDATE":1769889533,"YORDER":150450429,"YSUM":20872,"YORIGIN":40389,"YCASH":"","YTYPE":"payment","YUSER":null,"YHOST":"","YNOTE":"пополнение водителю за б/н за заказ: диамант фирма горького пос."},{"YID":89626827,"YDATE":1769889533,"YORDER":150450429,"YSUM":-2713,"YORIGIN":61261,"YCASH":"","YTYPE":"tariff","YUSER":null,"YHOST":"","YNOTE":"комиссия с водителя за заказ: диамант фирма горького пос."},{"YID":89627062,"YDATE":1769890475,"YORDER":150450703,"YSUM":3000,"YORIGIN":58548,"YCASH":"","YTYPE":"tariff","YUSER":null,"YHOST":"","YNOTE":"водителю за \"ложный (клиент)\" за заказ: атб напротив фуршета ост. 50"},{"YID":89627352,"YDATE":1769891370,"YORDER":150450760,"YSUM":-1150,"YORIGIN":61548,"YCASH":"","YTYPE":"tariff","YUSER":null,"YHOST":"","YNOTE":"комиссия с водителя за заказ: корабелов пр., д 16, по 1"},{"YID":89628647,"YDATE":1769896663,"YORDER":150451273,"YSUM":-2818,"YORIGIN":51802,"YCASH":"","YTYPE":"tariff","YUSER":null,"YHOST":"","YNOTE":"комиссия с водителя за заказ: айвазовского, д 3, по 2"}]');

        foreach ($answer as $e) {
            $e->YDATE = date('Y.m.d H:i:s', $e->YDATE);
            $id = $e->YID;
            $pays->$id = [$e->YDATE, $e->YORIGIN, $e->YSUM, $e->YNOTE, $driver];
        }
    }

    if ($phone > 0) {
        $answer = fb_arc($db, "select * from sg_pays_phone({$phone}, 9500);");
        //пример ответа:         $answer = json_decode('[{"YID":81638094,"YDATE":1743766848,"YORDER":0,"YSUM":2500,"YORIGIN":211791,"YCASH":"безнал.","YTYPE":"bonus","YUSER":null,"YHOST":"","YNOTE":"акционные бонусы по промо коду 994"},{"YID":84123019,"YDATE":1752062019,"YORDER":0,"YSUM":5000,"YORIGIN":214291,"YCASH":"безнал.","YTYPE":"bonus","YUSER":null,"YHOST":"","YNOTE":"акционные бонусы по промо коду 994"},{"YID":86154222,"YDATE":1758720417,"YORDER":0,"YSUM":5000,"YORIGIN":215291,"YCASH":"безнал.","YTYPE":"bonus","YUSER":null,"YHOST":"","YNOTE":"акционные бонусы по промо коду 99425"},{"YID":88204172,"YDATE":1765535795,"YORDER":149905018,"YSUM":90,"YORIGIN":220291,"YCASH":"безнал.","YTYPE":"bonus","YUSER":null,"YHOST":"","YNOTE":"бонус: спонсор 0.5% за заказ: vitaliia bokhonka street, д 29"}]');

        foreach ($answer as $e) {
            $e->YDATE = date('Y.m.d H:i:s', $e->YDATE);
            $id = $e->YID;
            $pays->$id = [$e->YDATE, $e->YORIGIN, $e->YSUM, $e->YNOTE, $phone];
        }
    }
}

$text = '<!DOCTYPE html>
<html>
 <head>
  <meta charset="utf-8">
  <title>' . $driver . ' + ' . $phone . '</title>
  <style>
   span {
    background-color: orange;
   }
  </style>
 </head>
 <body> ';

$text .= "{$r}<b>Движение по счету водителя {$driver} и телефона {$phone}</b>{$r}{$r}";
$text .= "<div>страница - $list, бэкапы: " . (json_encode($dbArr[$list])) . "</div>{$r}";
var_dump($list);
$text .= "

<div>
&nbsp;&nbsp;&nbsp;&nbsp;
<button style='background-color: #229ae5; padding: 10px; color: white; text-shadow: 1px 1px 2px rgb(0, 43, 92) ;' onclick='buttonSearch(-1)'>
  <b>РАНЬШЕE</b>
</button>
&nbsp;&nbsp;&nbsp;&nbsp;
<button style='background-color: #229ae5; padding: 10px; color: white; text-shadow: 1px 1px 2px black;' ' onclick='buttonSearch(1)'>
  <b>ПОЗЖЕE</b>
</button>

</div>{$r}{$r}";

$text .= '<label>Дата:</label>
<input type="date" id="calendar">';

$text .= "{$r}{$r}";
//var_dump($_SERVER);
$text .= '
<label>Телефон:</label>
<input type="tel" id = "input_tel" placeholder="380930000000">
<label>ID водителя:</label>
<input type="text" id="input_driver_id" placeholder="445827">';
$text .= "{$r}{$r}";
$text .= '<button style="background-color: #4800ff; padding: 10px; color: white; text-shadow: 1px 1px 2px black;"onclick="buttonSearch(0)">
  <b>Искать</b>
</button>';
$text .= "{$r}{$r}";

$text .= "<script>
const dbArr = " . json_encode($dbArr) . ";
const test = 1;
";


$text .= '
document.addEventListener("DOMContentLoaded", function() {';
$text .= " reloadpage();
 document.getElementById('calendar').addEventListener('change', function () {
        let selectedDate = this.value;
        let index = findIndexByDate(selectedDate);

        if (index === -1) {
            alert('Дата не найдена');
            return;
        }

        let number_phone = Number(document.getElementById('input_tel').value);
        let driver_id = Number(document.getElementById('input_driver_id').value);
        
        if (test == 0) { 
            window.location.href =
                'http://localhost/vsiakoe/py.php?driver='
                + driver_id
                + '&phone='
                + number_phone
                + '&list='
                + index
                + '&date='
                + selectedDate;
        }
    });
});";

$text .= " function reloadpage(){
    const urlParams = new URLSearchParams(window.location.search);
    const driverid = urlParams.get('driver'); 
    const phone = urlParams.get('phone');  
    const selectedDate = urlParams.get('date');
    document.getElementById('input_tel').value = phone;
    document.getElementById('input_driver_id').value = driverid;
    document.getElementById('calendar').value = selectedDate || '';
  }
function findIndexByDate(selectedDate){
    for (let i = 0; i < dbArr.length; i++) {
        let end = dbArr[i][dbArr[i].length - 1];
        let start;

        if (i == 0) {
            start = dbArr[i][i];
            end = dbArr[i][dbArr[i].length - 1];
        } else {
            start = dbArr[i - 1][dbArr[i - 1].length - 1];
        }

        console.log(start);
        console.log(end);
        /*
        ['2025-05-01', '2025-06-01', '2025-07-01'],
        ['2025-08-01', '2025-09-01', '2025-10-01'],
       dbArr[i - 1] ['2025-11-01', '2025-12-01', '2026-01-01'],
        ['2026-02-01', '2026-03-01']
        */        

        if (selectedDate >= start && selectedDate <= end) {
            return (i);
        }
    }
    return -1;
}
  function buttonSearch(l){
    const urlParams = new URLSearchParams(window.location.search);
    const list = urlParams.get('list'); 
    let newlist = list*1 + l*1;
    let numberphone = Number(document.getElementById('input_tel').value);
    let driverid = Number(document.getElementById('input_driver_id').value);
    let selectedDate = document.getElementById('calendar').value;
    
    if (test == 0) { 
        window.location.href='http://localhost/vsiakoe/py.php?driver='+ driverid +'&phone='+ numberphone +'&list=' + newlist +'&date='+ selectedDate;
    }
  }

</script>";


$text .= "<table cellspacing='0' border='1' cellpadding='2'>
    <tr style=\"background-color: lightgrey;\">
        <th>Дата</th>
        <th>Было</th>
        <th>Сумма</th>
        <th>Коммент</th>
        <th>Владелец</th>
    <tr>";

foreach ($pays as $e) {
    $date = $e[0];
    $origin = number_format(round(($e[1] / 100), 2), 2);
    $sum = number_format(round(($e[2] / 100), 2), 2);
    if ($sum < 0) $sum = "<font color='red'>" . $sum . "</font>";
    $note = $e[3];

    $text .= "
    <tr>
        <td>&nbsp;" . $date . "&nbsp;</td>
        <td align='right'>&nbsp;&nbsp;&nbsp;" . $origin . "&nbsp;&nbsp;&nbsp;</td>
        <td align='right'>&nbsp;&nbsp;&nbsp;<b>" . $sum . "</b>&nbsp;&nbsp;&nbsp;</td>
        <td>&nbsp;" . $note . "&nbsp;</td>
        <td>&nbsp;<b>" . $e[4] . "<b>&nbsp;</td>
    </tr>";
}

$text .= "</table>\n<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>\n</body>\n</html>";
echo $text;
