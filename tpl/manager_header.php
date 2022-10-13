<?php
session_start();
//error_reporting(E_ALL); 
//ini_set("display_errors", 1);
require $_SERVER['DOCUMENT_ROOT'] .  '/config.php';
require $_SERVER['DOCUMENT_ROOT'] .  '/insertClass/Conn.php';
require $_SERVER['DOCUMENT_ROOT'] .  '/suppliers/Suppliers.php';

if(!isset($_SESSION['name'])) {
    header('location: /login.php');
}
?>
<!doctype html>
<html lang="en">
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
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <a class="navbar-brand" href="/">АНГАРА</a>
      <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="/">Проценка <span class="sr-only">(current)</span></a>
          </li>
         <!-- <li class="nav-item">
            <a class="nav-link" href="/suppliers/">Поставщики</a>
          </li> -->
          <li class="nav-item">
            <a class="nav-link" href="/salesscript/">Интерфейс</a>
          </li>
          <li>
          	<a class="nav-link" style="color:#18bc9c;" href="../logout.php">Exit</a>
          </li>
          <!--  <li class="nav-item">
            <a class="nav-link" href="#">Profile</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Help</a>
          </li> -->
        </ul>
        <!-- <form class="form-inline mt-2 mt-md-0" name="form1" id="mainForm" method="get" enctype="multipart/form-data" action="">
          <input class="form-control mr-sm-2" type="text" placeholder="Забей номер сюда!" aria-label="Search" name="search">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Press</button>
        </form> -->
      </div>
    </nav>