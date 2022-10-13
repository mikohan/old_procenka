<?php

if ($_SERVER['DOCUMENT_ROOT'] == "")
   $_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__);
$path = $_SERVER['DOCUMENT_ROOT'] . '/';

define('ANG_ROOT', $path);

define("DBHOST", "192.168.0.24");
define("DBUSER", "newproject");
define("DBPASS", "newproject33338");
define("DB", "ang_tecdoc");

define('ANG_HOST', 'localhost');
define('ANG_DBNAME', 'ang_tecdoc');
define('ANG_DBNAME_ANGARA_TEST', 'angara_test');
define('ANG_DBUSER', 'root');
define('ANG_DBPASS', 'manhee33338');

$connection = @mysqli_connect(DBHOST, DBUSER, DBPASS, DB) or die("Нет соединения с БД");
mysqli_set_charset($connection, "utf8") or die("Не установлена кодировка соединения");

require ANG_ROOT . 'functions.php';
