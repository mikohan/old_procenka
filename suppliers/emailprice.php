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

if ($_GET['action'] == 'get_email') {
    $query = $obj -> getSupplierEmail($_GET['supplier_id'])[0];
    $emailSearch = $query['email'];
    $emailSearch2 = $query['email2'];
    $folder = $query['folder'];
    //p($folder);

    //танцы с бубном акко
    if ($_GET['supplier_id'] == 11 ) {

        recursiveRemoveDirectory(__DIR__ . "/../prices/" . $folder);
        $email = $obj -> myImap($emailSearch, $emailSearch2, $folder);
        $sup = $obj -> getSupplierEmail($_GET['supplier_id']);
        if ($email !== FALSE) {
            $obj -> insertDateEmail(date('Y-m-d H:i:s', strtotime($email)), $_GET['supplier_id']);
            $obj -> checkExtention($folder);
            $obj -> checkExtentionRar($folder);
            $file = glob(__DIR__ . '/../prices/' . $folder . '/*.xls');
            //p($file);
            rename ($file[0],$file[0] . '.csv');
            //echo $file[0] . 'good@ <br>';
            header('Location: index.php');
        } else {
            echo $sup[0]['name'] . 'Прайса нет! - ' . date('Y-m-d');
            
        }
      //танцы с бубном ЗСК  
    }elseif($_GET['supplier_id'] == 480 OR $_GET['supplier_id'] == 490){
        
        
        recursiveRemoveDirectory(__DIR__ . "/../prices/" . $folder);
        $email = $obj -> myImapAkko($emailSearch, $emailSearch2, $folder);
        $sup = $obj -> getSupplierEmail($_GET['supplier_id']);
        if ($email !== FALSE) {
            $obj -> insertDateEmail(date('Y-m-d H:i:s', strtotime($email)), $_GET['supplier_id']);
            $obj -> checkExtention($folder);
            $obj -> checkExtentionRar($folder);
            header('Location: index.php');
        } else {
            echo $sup[0]['name'] . 'Прайса нет! - ' . date('Y-m-d');
            
        }
        
        
    }else{

        recursiveRemoveDirectory(__DIR__ . "/../prices/" . $folder);
        $email = $obj -> myImapTest($emailSearch, $emailSearch2, $folder);
        echo $email;
        //$obj->insertDateEmail(date('Y-m-d', strtotime($email)),21);
        $sup = $obj -> getSupplierEmail($_GET['supplier_id']);
        if ($email !== FALSE) {
            //костыль для Роско
            if($_GET['supplier_id'] == 61){
                $file = glob(__DIR__ . '/../prices/' . $folder . '/*');
            p($file);
            chmod($file[0],0777);
            rename ($file[0], $file[0] . '.zip');
            }
            $obj -> insertDateEmail(date('Y-m-d H:i:s', strtotime($email)), $_GET['supplier_id']);
            $obj -> checkExtention($folder);
            $obj -> checkExtentionRar($folder);
 //Костыль для переименования файла со скобками и всяким дерьмом
            
            if($_GET['supplier_id'] == 66){
                $file = glob(__DIR__ . '/../prices/' . $folder . '/*.csv');
            //p($file);
            chmod($file[0],0777);
            rename ($file[0],  __DIR__ . '/../prices/' . $folder . '/converted.csv');
            }
            
            header('Location: index.php');
        } else {
            echo $sup[0]['name'] . 'Прайса нет! - ' . date('Y-m-d');
            
        }
    }//проверка акко
    //переименовываем файл автоевро
    
    if($_GET['supplier_id'] == 66){
        
    }
} elseif ($_GET['action'] == 'test1') {
    $suppliersData = $obj -> getSupplierEmails();
    //p($suppliersData);
    foreach ($suppliersData as $supk => $supv) {
        $emailSearch = $supv['email'];
        $emailSearch2 = $supv['email2'];
        $folder = $supv['folder'];
        //p($folder);
        // recursiveRemoveDirectory(__DIR__ . "/../prices/" . $folder);
        $email = $obj -> myImap($emailSearch, $emailSearch2, $folder);
        if ($email == TRUE) {
            //recursiveRemoveDirectory(__DIR__ . "/../prices/" . $folder);
            echo '<div class="col-md-4">';
            echo '<div class="alert alert-success" role="alert">Прайс посавщика: ' . $supv['name'] . ' за дату: ' . $email . ' получен и сохранен!</div>';
            echo '</div>';
            $obj -> insertDateEmail(date('Y-m-d', strtotime($email)), $supv['id']);
            $obj -> checkExtention($folder);
            $obj -> checkExtentionRar($folder);
        } else {
            echo '<div class="col-md-4">';
            echo '<div class="alert alert-warning" role="alert"> Прайс поставщика: ' . $supv['name'] . ' не получен!</div>';
            echo '</div>';
        }

    }
}

//$obj->emailSearch = 'Price_Avtomir_0DN';
//$obj->myImap('Price_Avtomir_0DN');
?>

<?php
require '../tpl/footer.php';
?>