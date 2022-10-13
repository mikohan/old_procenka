<?php
error_reporting(O);
ini_set("display_errors", 1);
set_time_limit(3000);
require '../tpl/header.php';
require __DIR__ . '/EmailPrice.php';

$obj = new EmailPrice;
echo "ffffffffffffffffffffffffffffffffffffffffffffff<br>";
echo "ffffffffffffffffffffffffffffffffffffffffffffff<br>";
echo "ffffffffffffffffffffffffffffffffffffffffffffff<br>";
echo "ffffffffffffffffffffffffffffffffffffffffffffff<br>";
echo "ffffffffffffffffffffffffffffffffffffffffffffff<br>";
echo "ffffffffffffffffffffffffffffffffffffffffffffff<br>";
echo "ffffffffffffffffffffffffffffffffffffffffffffff<br>";

//p($folder);
// recursiveRemoveDirectory(__DIR__ . "/../prices/" . $folder);
$email = $obj -> myImap('price@rossko.ru', 'price@rossko.ru', 'rossko');

$folder = 'rossko';

if ($email == TRUE) {
    //recursiveRemoveDirectory(__DIR__ . "/../prices/" . $folder);
   
    
    checkExtention($folder);
}



function checkExtention($folder) {
    
    $file = glob( __DIR__ . '/../prices/' . $folder . '/*.zip');
    p($file);
    
    $file_parts = pathinfo(@$file[0]);
    
    if (@$file_parts['extension'] == 'zip') {
        $zip = new ZipArchive;
        $res = $zip -> open($file[0]);
        $zip -> extractTo(__DIR__ . "/../prices/" . $folder);
        $zip -> close();
        
    }
    return TRUE;
    
}