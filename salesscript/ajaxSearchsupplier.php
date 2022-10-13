<?php
error_reporting(0);
//error_reporting(E_ALL ^ E_NOTICE);  
session_start();
$car_name = $_SESSION['car_name'];
//require $_SERVER['DOCUMENT_ROOT'] .  '/errors.php';
require $_SERVER['DOCUMENT_ROOT'] .  '/config.php';
require $_SERVER['DOCUMENT_ROOT'] .  '/insertClass/Conn.php';
require_once __DIR__ . '/lib/ModelGet.php';
require __DIR__ . '/../SearchClasses/SearchClass.php';
//$obj = new ModelGet;
$object = new SearchClass;
$object->table_name = 'ang_prices_all';
$object->table_nameSlow = 'ang_prices_all_no_beginning';

//$_POST['action'] = 'get_supplier';
//$_POST['search'] = '1805086';

    if($_POST['action'] == 'get_supplier'){
        if(isset($_POST['search'])){
        $search = trim($_POST['search']);
       // $dataAngara = $object->getSearchAngara($search);
        $data = $object->getSearch($search);
        $data2 = $object->getSearchSlow($search);
        $data = array_merge($data,$data2);
        $croses = $object->getCroses($search);
        foreach($croses as $ck=>$cv){
            if(!empty($cv['microtime'])){
                continue;
            }
            $data3[] = $object->getSearchCross($cv['partnumber']);
            //p($cv['partnumber']);
        }
        foreach($data3 as $d3k=>$d3v){
            $data = array_merge($data, $data3[$d3k]);
        }
        
        foreach($data as $keystrip => $valuestrip){
            @$valuestrip['price'] = str_replace("'", "", @$valuestrip['price']);
            $data[$keystrip]['price'] = $valuestrip['price'];
            //p($data[$keystrip]);
        }
        usort($data, function($a, $b) {
            return ($a['price'] - $b['price']);
        });
            echo json_encode($data,JSON_UNESCAPED_UNICODE);
            
        }else{
            $data = [];
        }
    }
    
    
    
    
    