<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require __DIR__ . '/../config.php';
require __DIR__ . '/Conn.php';
require __DIR__ . '/PriceInsert.php';
$obj = new PriceInsert;
$obj->truncateTable('angara');
$c = $obj->insertAngara('/home/angara/zakupki.loc/prices/angara/angara.csv');
$obj -> supplierLoadDate('1000', '14000');

 $dprice = date('Y-m-d', filemtime('/home/angara/zakupki.loc/prices/angara/angara.csv'));


 $dnow =  date('Y-m-d');




if($dprice != $dnow){
    echo "Angara price table has old file";
    $to      = 'angara99@gmail.com';
    $subject = 'Старый файл прайса АНГАРА в проценке! Проверь выгрузку в 1с и в кроне';
    $message = 'Прайс старый! Дата файла  ' . $dprice;
    $headers = 'From: angara99@gmail.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    
    mail($to, $subject, $message, $headers);

	}
