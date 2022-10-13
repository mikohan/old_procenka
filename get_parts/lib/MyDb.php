<?php
class MyDb{

private $dbname = 'angara_test';
private $dbname1 = 'ang_tecdoc';
private $host = 'localhost';
private $user = 'root';
private $pass = 'manhee33338';

protected function db() {

        try {
            $dsn = 'mysql:dbname=' . $this -> dbname . ';host=' . $this -> host;
            $pdo = new PDO($dsn, $this -> user, $this -> pass);
            $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo -> exec("set names utf8");

        } catch(PDOException $e) {
            echo $e -> getMessage();
        }
        return $pdo;
    }


protected function db1() {

        try {
            $dsn = 'mysql:dbname=' . $this -> dbname1 . ';host=' . $this -> host;
            $pdo = new PDO($dsn, $this -> user, $this -> pass);
            $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo -> exec("set names utf8");

        } catch(PDOException $e) {
            echo $e -> getMessage();
        }
        return $pdo;
    }

    public function p($array) {
           echo "<pre>";
           print_r($array);
           echo "</pre>";
       }




}
