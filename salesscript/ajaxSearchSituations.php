<?php
session_start();
if(!isset($_SESSION['car_name']) OR empty($_SESSION['car_name'])){
    $_SESSION['car_name'] = 'porter';
}
require $_SERVER['DOCUMENT_ROOT'] .  '/errors.php';
require $_SERVER['DOCUMENT_ROOT'] .  '/config.php';
require $_SERVER['DOCUMENT_ROOT'] .  '/insertClass/Conn.php';
require_once __DIR__ . '/lib/ModelGet.php';


$car_name = $_SESSION['car_name'];
$car_id = $_SESSION['car_id'];
//p($_SESSION);

//p($_POST);
$obj = new ModelGet;
$_POST['action'] = 'get_situations';
    if($_POST['action'] == 'get_situations'){
        $searchData = $obj->getSituationQuery($_POST['to_search']  , $car_id);
        $result = json_encode($searchData, JSON_UNESCAPED_UNICODE);
        echo $result;
        
    }