<?php

define('ANG_HOST', 'localhost');
define('ANG_DBNAME', 'ang_solaris');
define('ANG_DBUSER', 'root');
define('ANG_DBPASS', 'manhee33338');

$path = $_SERVER['HTTP_HOST'] . '/admin/';
define('ANG_ROOT', $path);
define('ANG_MAP', 'http://angarasolaris.com/');
@define('ANG_HTTP', 'http://' . $_SERVER['SERVER_NAME']);
define('SPHINX_CONN', "mysql:host=127.0.0.1;port=9306;");
define('ADMIN', '/admin');
