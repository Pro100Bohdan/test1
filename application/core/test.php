<?php

/*
*   Данные для подключения базы.
*/

$host     = 'host';
$login    = 'login';
$password = 'pass';
$base     = 'base';

/*
*   Подключение к базе.
*/

$connect = mysql_connect($host, $login, $password);
mysql_select_db($base, $connect);
mysql_query("SET CHARACTER SET 'utf8'", $connect);

/*
*   Запись данных в базу на сервере.
*/

mysql_query('INSERT INTO r_invoices (поля для записи через запятую) VALUES (данные соответствующие каждому полю)', $connect);

?>