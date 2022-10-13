<?php
session_start();
if(!isset($_SESSION['car_name']) OR empty($_SESSION['car_name'])){
    $_SESSION['car_name'] = 'porter';
}
//$car_name = $_SESSION['car_name'];
require $_SERVER['DOCUMENT_ROOT'] .  '/errors.php';
require $_SERVER['DOCUMENT_ROOT'] .  '/config.php';
require $_SERVER['DOCUMENT_ROOT'] .  '/insertClass/Conn.php';
require_once __DIR__ . '/lib/ModelInsert.php';
//p($_POST);
$obj = new ModelInsert;
if(isset($_POST) and !empty($_POST)){
    if(isset($_POST['keywords'])){
        $table = 'ss_situation';
        $field = 'ang_name';
        $value = $_POST['keywords'];
        $id = $_POST['id'];
        $update = $obj->caseUpdate($table, $field, $value, $id);
        echo($update['ang_name']);
    }elseif(isset($_POST['cases'])){
        $table = 'ss_situation';
        $field = 'situation_name';
        $value = $_POST['cases'];
        $id = $_POST['id'];
        $update = $obj->caseUpdate($table, $field, $value, $id);
        echo($update['situation_name']);
    }elseif(isset($_POST['cars'])){
        $table = 'ss_cars';
        $field = 'car';
        $value = $_POST['cars'];
        $id = $_POST['id'];
        $update = $obj->insertCarsId($value, $id);
        echo($update);
    }
}
if(isset($_GET['action']) AND $_GET['action'] == 'delete'){
    if($obj->caseDelete($_GET['case_id'])){
        header('Location: caseInsert.php');
    }
}