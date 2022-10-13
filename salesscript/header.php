<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] .  '/errors.php';
require $_SERVER['DOCUMENT_ROOT'] .  '/config.php';
require $_SERVER['DOCUMENT_ROOT'] .  '/insertClass/Conn.php';
if(!isset($_SESSION['name'])) {
    header('location: /login.php');
}
?>
<!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8" />
        <title>Скрипт продаж компании Ангара</title>
        <link rel="icon" href="/favicon.png" type="image/x-icon" />
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <link rel="stylesheet" href="/css/jquery-ui.css">
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="/css/style.css">
        <script src="/js/jquery-3.2.1.min.js" ></script>
        <script src="/js/jquery-ui.min.js"></script>
        <script src="/js/popper.min.js"></script>
        <script src="/js/bootstrap.min.js" ></script>
    </head>
    <body>
<!-- <header class="header">
    <div class="div_header">
        <div class="wrapper_clean">
            <div class="v_align">
                <h1><a href="index.php">Скрипт продаж компании Ангара</a></h1>
                <div style="display:flex;align-items:center;flex-direction:row"><i class="fa fa-user" aria-hidden="true" style="margin-right:5px"></i><a href="login.php" class="header_login">Войти</a></div>
            </div>
            
        </div>
    </div>
    <div class="header_second">
        <div class="wrapper_clean">
            <div class="menu">
                <ul class="ul_menu">
                    <li><a href="tree.php">Дерево</a></li>
                    <li><a href="categories.php">Категории</a></li>
                    <li><a href="models.php">Модели</a></li>
                    <li><a href="situations.php">Ситуации</a></li>
                    <li><a href="associations.php">Сопутствующие товары</a></li>
                    <li><a href="synonyms.php">Синонимы</a></li>
                </ul>
            </div>
        </div>
    </div>
</header> -->