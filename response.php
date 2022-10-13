<?php 
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once __DIR__ . '/tpl/header.php';
require_once __DIR__ . '/insertClass/Conn.php';

$id = $_GET['action'];
Class Response extends Conn{
function getdata($id) {
        $starttime = microtime(true);   
        $m = $this -> db();
        $q = 'SELECT name FROM ang_suppliers WHERE id = :id';
        $t = $m -> prepare($q);
        $t -> execute(array(':id'=>$id));
        $data = $t -> fetchAll(PDO::FETCH_ASSOC);
        $endtime = microtime(true);
        $duration = $endtime - $starttime;
        array_unshift($data, ['microtime' =>round($duration,4)]);
        //$this->p($data);
        return $data;

    }
}

$obj = new Response;

$data = $obj->getdata($id);
    //$obj->p($data);
    echo $data[1]['name'];
