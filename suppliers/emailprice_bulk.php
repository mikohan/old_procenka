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

$obj->deleteEmails();
$weight = 20;

$suppliersData = $obj -> getSupplierEmailsWeight($weight);// Выбираем всех поставщиков вес которых больше 40
//p($suppliersData);
$message = '<html><body>';
foreach ($suppliersData as $supk => $supv) {
    try{
    $emailSearch = $supv['email'];
    $emailSearch2 = $supv['email2'];
    $folder = $supv['folder'];
    //p($folder);
     recursiveRemoveDirectory(__DIR__ . "/../prices/" . $folder);
    $email = $obj -> myImapTest($emailSearch, $emailSearch2, $folder);
    if ($email == TRUE) {
        //recursiveRemoveDirectory(__DIR__ . "/../prices/" . $folder);
        echo '<div class="col-md-4">';
        echo  '<div class="alert alert-success" role="alert"><span>'.$supk .' </span>Прайс посавщика: ' . $supv['name'] . ' за дату: ' . $email . ' получен и сохранен!</div>';
        echo  '</div>';
        $obj -> insertDateEmail(date('Y-m-d', strtotime($email)), $supv['id']);
        $obj -> checkExtention($folder);
        $obj -> checkExtentionRar($folder);
        $message .= '<div style="background-color:#55efc4; padding: 0.75rem; width: 400px; margin-top: 5px;">Прайс посавщика: ' . $supv['name'] . ' за дату: ' . $email . ' получен и сохранен!</div>';
    } else {
        echo  '<div class="col-md-4" class="col-md-4">';
        echo  '<div class="alert alert-warning" role="alert"> Прайс поставщика: ' . $supv['name'] . ' не получен!</div>';
        echo  '</div>';
        
        $message .= '<div style="background-color:#fd79a8; padding: 0.75rem; width: 400px; margin-top: 5px;"> Прайс поставщика: ' . $supv['name'] . ' не получен!</div>';
    }
    
    
    }catch(Exeption $e){
        echo $e;
    }
}

$message .= '</body></html>';
$subject = 'Получены прайсы поставщиков';
$obj->sendEmail($subject, $message);
?>
<?php
require '../tpl/footer.php';
?>