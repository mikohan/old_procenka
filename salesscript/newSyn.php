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
p($_POST);
$obj = new ModelInsert;
if(isset($_POST) and !empty($_POST)){
   
    //$obj->synInsert($_POST);
    if($obj->synInsertTestPdo($_POST)){
        header('Location: synonymInsert.php');
    }
    
}
if(isset($_GET['action']) AND $_GET['action'] == 'delete'){
    if($obj->synDelete($_GET['syn_id'])){
        header('Location: synonymInsert.php');
    }
}