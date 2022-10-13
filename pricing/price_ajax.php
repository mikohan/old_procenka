<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require "lib/MyDb.php";
require "lib/GetPart.php";
if(isset($_POST['id'])){
$obj = new GetPart;
$table = $_POST['table'];
$obj -> updateNewPrice($table, $_POST['id'], $_POST['price']);
echo ($obj -> getNewPrice($table, $_POST['id'])[0]['new_price']);
}
