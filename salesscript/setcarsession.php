<?php
session_start();
$_SESSION['car_name'] = $_POST['car_name'];
$_SESSION['car_id'] = $_POST['car_id'];
//echo $_SESSION['car_name'];
//print_r($_POST);