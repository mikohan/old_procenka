<?php
ob_start();
error_reporting(O);
//ini_set("display_errors", 1);
set_time_limit(3000);
require '../tpl/header.php';
require __DIR__ . '/EmailPrice.php';
?>
<div class="spacer-60"></div>
<div class="container-fluid">
<div class="row">

<?php
require '../tpl/left.php';
?>
<main class="col-sm-9 ml-sm-auto col-md-10 pt-3" role="main">

<?php
$obj = new EmailPrice;

//p($_GET);

//$obj->insertDateEmail(date('Y-m-d', strtotime($email)),21);

$suppliersData = $obj -> getSupplierEmails();
//p($suppliersData);
foreach ($suppliersData as $supk => $supv) {
    $emailSearch = $supv['email'];
    $emailSearch2 = $supv['email2'];
    $folder = $supv['folder'];

   

        recursiveRemoveDirectory(__DIR__ . "/../prices/" . $folder);
        $email = $obj -> myImapAkko($emailSearch, $emailSearch2, $folder);
        $sup = $obj -> getSupplierEmail($supv['id']);
        if ($email !== FALSE) {
            echo '<div class="col-md-4">';
            echo '<div class="alert alert-success" role="alert">Прайс поставщика: ' . $supv['name'] . ' за дату: ' . $email . ' получен и сохранен!</div>';
            echo '</div>';
            $obj -> insertDateEmail(date('Y-m-d H:i:s', strtotime($email)), $supv['id']);
            $obj -> checkExtention($folder);
            $obj -> checkExtentionRar($folder);
            //header('Location: index.php');
        } else {
            echo '<div class="col-md-4">';
            echo '<div class="alert alert-warning" role="alert"> Прайс поставщика: ' . $supv['name'] . ' не получен!</div>';
            echo '</div>';

        }
        //танцы с бубном ЗСК
        
    }



//$obj->emailSearch = 'Price_Avtomir_0DN';
//$obj->myImap('Price_Avtomir_0DN');
?>

<?php
require '../tpl/footer.php';
?>