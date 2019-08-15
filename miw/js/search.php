<?php

define("DB_HOST","supers06.mysql.tools");
define("DB_NAME","supers06_bystron"); //Имя базы
define("DB_USER","supers06_bystron"); //Пользователь
define("DB_PASSWORD","7xnf7x6v"); //Пароль
define("PREFIX",""); //Префикс если нужно

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$mysqli -> query("SET NAMES 'utf8'") or die ("Ошибка соединения с базой!");

if(!empty($_POST["referal"])){ //Принимаем данные

    $referal = trim(strip_tags(stripcslashes(htmlspecialchars($_POST["referal"]))));

    $db_referal = $mysqli -> query("SELECT * from ".PREFIX." orders WHERE part_number LIKE '%$referal%'")
    or die('Ошибка №'.__LINE__.'<br>Обратитесь к администратору сайта пожалуйста, сообщив номер ошибки.');

    while ($row = $db_referal -> fetch_array()) {
        echo "\n<li>".$row["author"]."</li>"; //$row["name"] - имя таблицы
        echo "\n<li>".$row["laser_marking"]."</li>";
        echo "\n<li>".$row["order_number"]."</li>";
        echo "\n<li>".$row["quantity"]."</li>";
        echo "\n<li>".$row["date"]."</li>";
    }

}


?>