<?php

define("DB_HOST","supers06.mysql.tools");
define("DB_NAME","supers06_bystron"); //Имя базы
define("DB_USER","supers06_bystron"); //Пользователь
define("DB_PASSWORD","7xnf7x6v"); //Пароль
define("PREFIX",""); //Префикс если нужно

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$mysqli -> query("SET NAMES 'utf8'") or die ("Error connection with database");

if(!empty($_POST["referal"])){ //Принимаем данные

    $referal = trim(strip_tags(stripcslashes(htmlspecialchars($_POST["referal"]))));

    $db_referal = $mysqli -> query("SELECT * from ".PREFIX." orders WHERE part_number  LIKE '%$referal%' or laser_marking LIKE '%$referal%'" )
    or die('Error №'.__LINE__.'<br>Please contact with Bogdan, and tell number of error.');

    $db_referal_statistic = $mysqli -> query("SELECT * from ".PREFIX." statistic WHERE partnumber  LIKE '%$referal%' " )
    or die('Error №'.__LINE__.'<br>Please contact with Bogdan, and tell number of error.');

    echo '<table class="table">' .
            '<thead>' .
            '<tr>' .
            '<th>PACKAGE ID</th>' .
            '<th>USER</th>' .
            '<th>ORDER NUMBER</th>' .
            '<th>QUANTITY</th>' .
            '<th>DATE</th>' .
            '</tr>' .
            '</thead>';

    while ($row = $db_referal -> fetch_array()) {
        echo '<tr>' .
                '<td>' . $row['package_id'] . '</td>' .
                '<td>' . $row['author'] . '</td>' .
                '<td>' . $row['order_number'] . '</td>' .
                '<td>' . $row['quantity'] . '</td>' .
                '<td>' . $row['date'] . '</td>' .
             '</tr>';
    }


    while ($row = $db_referal_statistic -> fetch_array()) {
        echo '<tr>' .
                '<td>' . $row['id'] . '</td>' .
                '<td>' . $row['user'] . '</td>' .
                '<td>' . $row['ordernumber'] . '</td>' .
                '<td>' . $row['lils'] . '</td>' .
                '<td>' . $row['date'] . '</td>' .
             '</tr>';
    }

}


?>