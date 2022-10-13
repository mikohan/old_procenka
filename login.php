<?php
require $_SERVER['DOCUMENT_ROOT'] .  '/errors.php';
require $_SERVER['DOCUMENT_ROOT'] .  '/config.php';
require $_SERVER['DOCUMENT_ROOT'] .  '/insertClass/Conn.php';
//require_once __DIR__ . '/insertClass/Conn.php';
require_once __DIR__ . '/LoginClass.php';
$ob = new LoginClass;


//p($_POST);
if (isset($_POST['log'])) {
    $user = $_POST['user'];
    $pass = md5($_POST['pass']);
    $auth = $ob->authorization($user, $pass);
    $username = $auth[0]['user'];
    $password = $auth[0]['pass'];
    $type = $auth[0]['type'];
    $name = $auth[0]['username'];
    $user_id = $auth[0]['id'];
    if ($user == $username && $pass == $password) {
        session_start();
        $_SESSION['name'] = $name;
        $_SESSION['type'] = $type;
        $_SESSION['user_id'] = $user_id;
        if ($type == 'admin') {
            echo '<script>location.assign("index.php")</script>';
        } elseif ($type == 'manager') {
            echo '<script>location.assign("index.php")</script>';
        } 
    }
   // p($auth);
}

?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Проценка Ангара</title>
    
    <link rel="icon" href="/favicon.png" type="image/x-icon" />
   <!--  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous"> -->
   <link rel="stylesheet" href="/css/jquery-ui.css">
   <link rel="stylesheet" href="/css/bootstrap.min.css">
   <link rel="stylesheet" href="/font-awesome/css/font-awesome.min.css">
   <link rel="stylesheet" href="/css/style.css">
   
    <script src="/js/jquery-3.2.1.min.js" ></script>
    <script src="/js/jquery-ui.min.js"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> -->
    <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
    <script src="/js/popper.min.js"></script>
    <script src="/js/bootstrap.min.js" ></script>
</head>
<body>
<div class="container">
	<div class="jumbotron">
		<h1>Login page</h1>
	</div>
	<div class="row">
		<div class="col-md-4">
			<form method="post" action="">
				<div class="form-group">
					<label for="exampleInputEmail1">Email address</label> <input
						type="user" class="form-control" id="exampleInputEmail1"
						placeholder="User" name="user">
				</div>
				<div class="form-group">
					<label for="exampleInputPassword1">Password</label> <input
						type="password" class="form-control" id="exampleInputPassword1"
						placeholder="Password" name="pass">
				</div>

				<button type="submit" class="btn btn-default" name="log">Submit</button>
			</form>
		</div>
	</div>
</div>
</body>
</html>

