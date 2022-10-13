<?php
session_start();
if(!isset($_SESSION['car_name']) OR empty($_SESSION['car_name'])){
    $_SESSION['car_name'] = 'porter';
}
require $_SERVER['DOCUMENT_ROOT'] .  '/errors.php';
require $_SERVER['DOCUMENT_ROOT'] .  '/config.php';
require $_SERVER['DOCUMENT_ROOT'] .  '/insertClass/Conn.php';
require_once __DIR__ . '/lib/ModelGet.php';



$situation_id = $_POST['situation_id'];

$obj = new ModelGet;
    if($_POST['action'] == 'get_tree'){
        $id_tmp = explode('-', $situation_id);
        $id = $id_tmp[1];
        $searchData = $obj->getSituationTree($id);
        $result = json_encode($searchData, JSON_UNESCAPED_UNICODE);
        echo $result;
    }