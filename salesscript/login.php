<?php

	if ( !empty($_REQUEST['password']) and !empty($_REQUEST['login']) ) {
		$login = $_REQUEST['login'];
		$password = $_REQUEST['password'];
		include_once 'config.php';
        $user = get_user($login, $password);   
		if (!empty($user)) {
            session_start(); 
            $_SESSION['auth'] = true; 
            $_SESSION['id'] = $user[0]['id']; 
            $_SESSION['login'] = $user[0]['login']; 
            header('Location: index.php'); 
        } else {
          //  echo 'false';
		}
        
	}
    

    
function get_user($login, $password){    
    $m = db();    
    $query = 'SELECT * FROM salesscript_users WHERE login= :login AND password= :password';    
    $sth = $m -> prepare($query);
    $sth -> execute(array(':login' => $login, ':password' => $password));
    $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
    return $data;    
}
    

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <link href="style.css" rel="stylesheet">
</head>
<body>

   <div style="width:250px; right: 0;  left: 0;  margin: 0 auto; margin-top:200px">
       <form action='login.php' method='POST' class="form_vertical">
		<input class="input" name='login' required>
		<input class="input" name='password' type='password' required>
		<input class="button button_ok" style="width:100%" type='submit' value='Войти'>
		<a style="margin-top:30px;text-align: center;" href=index.php>На главную</a>
	</form>       
   </div>    
</body>
</html>

