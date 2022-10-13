<?php 

function db() {
    $ANG_HOST = 'localhost';
    $ANG_DBNAME ='ang_tecdoc';
    $ANG_DBUSER = 'root';
    $ANG_DBPASS = 'manhee33338';
    try {
        $dsn = 'mysql:dbname=' . $ANG_DBNAME . ';host=' . $ANG_HOST;
        $pdo = new PDO($dsn, $ANG_DBUSER, $ANG_DBPASS);
        $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo -> exec("set names utf8");
    } catch(PDOException $e) {
        echo $e -> getMessage();
    }
    return $pdo;
}
