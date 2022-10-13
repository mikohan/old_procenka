<?php
session_start();
if(!isset($_SESSION['car_name']) OR empty($_SESSION['car_name'])){
    $_SESSION['car_name'] = 'porter';
}
$car_name = $_SESSION['car_name'];
require $_SERVER['DOCUMENT_ROOT'] .  '/errors.php';
require $_SERVER['DOCUMENT_ROOT'] .  '/config.php';
require $_SERVER['DOCUMENT_ROOT'] .  '/insertClass/Conn.php';
require_once __DIR__ . '/lib/ModelGet.php';

//p($_POST);
$obj = new ModelGet;
    if($_POST['action'] == 'get_search_query'){
        $searchData = $obj->getSearchQuery($_POST['to_search'], $car_name);
        $result = json_encode($searchData, JSON_UNESCAPED_UNICODE);
        echo $result;
    }