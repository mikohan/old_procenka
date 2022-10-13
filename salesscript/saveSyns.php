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
        $table = 'ss_slang';
        $field = 'slang_name';
        $value = $_POST['keywords'];
        $id = $_POST['id'];
        $update = $obj->synUpdate($table, $field, $value, $id);
        echo($update['slang_name']);
    }elseif(isset($_POST['origwords'])){
        $table = 'ss_syn_orig';
        $field = 'orig_name';
        $value = $_POST['origwords'];
        $id = $_POST['id'];
        $update = $obj->synUpdate($table, $field, $value, $id);
        echo($update['orig_name']);
    }elseif(isset($_POST['stopwords'])){
        $table = 'ss_syn_orig';
        $field = 'stop_words';
        $value = $_POST['stopwords'];
        $id = $_POST['id'];
        $update = $obj->synUpdate($table, $field, $value, $id);
        echo($update['stop_words']);
    }
    
    
}