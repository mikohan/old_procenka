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
p($_GET);
//echo '<hr>';
$obj = new ModelInsert;
if(isset($_POST) and !empty($_POST)){
    if($_POST['action'] == 'insert'){
        if($obj->treeInsert($_POST)){
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }elseif($_POST['action'] == 'update'){
        if($obj->updateInsert($_POST)){
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
        }
}elseif($_GET['action'] == 'delete'){
    if($obj->deleteTree($_GET['element_id'])){
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}