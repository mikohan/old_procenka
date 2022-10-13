<?php
/* Этот файл заменяет бренд + пробел корея
 * в таблице поставщиков 
 * 
 *  
 *  
 *  */
error_reporting(E_ALL);
ini_set("display_errors", 1);
require "lib/MyDb.php";
require "lib/GetPart.php";

$obj = new GetPart;
//Очищаем таблицу поставщиков от слова пробел + КОРЕЯ в названии бренда
//$obj -> clearBrands('ang_prices_all');

//Переколбашиваем названия брендов в таблице поставщиков по нашему слоарю
$obj -> brand_dict_table = 'brands_dict';
//$table = 'ang_prices_all_jdip';
//$table = 'ang_prices_all';
//$obj -> changeBrandsAngPricesAll($table);

//Таблица в которой меняем бренды
//$obj -> suppliers_table = 'cat_ang_all_cars_pricing_a';

//$obj -> clearBrandsEmp($obj -> suppliers_table);
//$obj -> clearBrandsZelezaka();
//$obj -> suppliers_table = 'parse_zapkia';
$obj -> changeBrandsTestDb('cat_bongo_intersect');
//$obj -> changeCompsBrand();
//$obj -> changeBrands('ang_prices_all');