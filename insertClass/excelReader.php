<?php
ob_start();
 require '../tpl/header.php';?>
 <div class="spacer-60"></div>
    <div class="container-fluid">
      <div class="row">
        
<?php require '../tpl/left.php';?>
        <main class="col-sm-9 ml-sm-auto col-md-10 pt-3" role="main">
          <!-- <h1>Dashboard</h1> -->
          <!-- <h2>Добавление поставщика</h2> -->
          
          <?php
          

// Этот файл будет читать ексель а insertClass записывать в базу
ini_set('memory_limit', '-1');
error_reporting(E_ALL); 
ini_set("display_errors", 1);
//require 'config.php';
//require __DIR__ . '/Conn.php';

require __DIR__ .  '/PriceInsert.php';
require_once __DIR__ . '/../Excel/PHPExcel/IOFactory.php';

//Выбираем все поля поставщика из таблицы suppliers

$obj = new PriceInsert;

//$obj->fields =  ['orig_number'=>2, 'oem_number'=>3, 'brand'=>4, 'name'=>5, 'stock'=>6, 'price'=>8, 'supplier'=>'tokus'];

$supplier = $obj->getSupplier($_GET['supplier_id']);
$tt = $supplier[0];
$tt['price_orig_number'] = $obj->converterBack($tt['price_orig_number']);
$tt['price_oem_number'] = $obj->converterBack($tt['price_oem_number']);
$tt['price_brand'] = $obj->converterBack($tt['price_brand']);
$tt['price_name'] = $obj->converterBack($tt['price_name']);
$tt['price_stock'] = $obj->converterBack($tt['price_stock']);
$tt['price_price'] = $obj->converterBack($tt['price_price']);
$tt['price_kratnost'] = $obj->converterBack($tt['price_kratnost']);
$tt['price_notes'] = $obj->converterBack($tt['price_notes']);
$obj->fields = $tt;

//$obj->p($tt);

//$obj->fields =  ['orig_number'=>4, 'oem_number'=>2, 'brand'=>1, 'name'=>3, 'stock'=>7, 'price'=>9, 'notes'=>4, 'supplier'=>'moskvorechie'];



if($_GET['action'] == 'insert_price'){
        
        
        //проверяем папку на наличие файлов
    
//если есть имя конкретного файл, берем его, если нет, то первый ексель
    if(!empty($tt['supplier_file1'])){
        echo __DIR__ . '/../prices/' . $tt['folder'] . '/*' . $tt['supplier_file1'] . '*';
        $file = glob(__DIR__ . '/../prices/' . $tt['folder'] . '/*' . $tt['supplier_file1'] . '*');
        p($file);
    }else{

    $file = glob(__DIR__ . '/../prices/' . $tt['folder'] . '/*.xls*');
    }
    
    if($file){
        //удаляем старый прайс лист
    $obj->deleteOldPrice($_GET['supplier_id']);
//$obj->p($file);
$inputFileType = 'Excel5';
$inputFileName = $file[0];



//  Read your Excel workbook
try {
    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    //$objReader->setLoadAllSheets();
    //$sheetnames = array('DAEWOO CHEVROLET','HYUNDAI KIA');
    //$objReader->setLoadSheetsOnly($sheetnames);
    $objPHPExcel = $objReader->load($inputFileName);
} catch(Exception $e) {
    die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
}

//Условие если файл на самом деле csv

 echo $inputFileType . '<br>';
    if($inputFileType == 'CSV'){
        echo 'it is csv <br>';
        echo $file . '<br>';
         
        $count .= $obj->insertCsv($file[$fk], $_GET['supplier_id']);
        
    }else{ 
            //  Get worksheet dimensions
            $sheet = $objPHPExcel->getSheet(0);
            //p($sheet); 
            $highestRow = $sheet->getHighestRow(); 
            $highestColumn = $sheet->getHighestColumn();
            
            $count = 0;
            //  Loop through each row of the worksheet in turn
            for ($row = 1; $row <= $highestRow; $row++){ 
                //  Read a row of data into an array
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                                NULL,
                                                TRUE,
                                                FALSE);
                                                
                //if($rowData[0][$obj->fields['orig_number']] == '') continue;
               //$rowData[0][$obj->fields['name']] = mb_strtolower($rowData[0][$obj->fields['name']], 'UTF-8');
                //echo $rowData[0][$obj->fields['name']];
                //$rowData[0][$obj->fields['orig_number']] = mb_strtolower(trim(str_replace('-', '', $rowData[0][$obj->fields['orig_number']]), 'UTF-8'));
                //$rowData[0][$obj->fields['oem_number']] = mb_strtolower(trim(str_replace('-', '', $rowData[0][$obj->fields['oem_number']]), 'UTF-8'));
                $rowData[0][$obj->fields['price_price']] = trim(preg_replace('@[\,]|\.00|\sруб.@u', '', $rowData[0][$obj->fields['price_price']]));
                $rowData[0][$obj->fields['price_stock']] = trim(preg_replace('@[\<\>]@u', '', $rowData[0][$obj->fields['price_stock']]));
                
                //$obj->p($rowData);
                
                $count += $obj->insertPrice($rowData);
               
                
                }               //  Insert row data array into your database of choice here
}

echo 'Вставлено - ' . $count . ' строк';
//}//elseif($file = glob(__DIR__ . '/../prices/' . $tt['folder'] . '/*.txt')){
 //    echo $file[0];
 //    $obj->deleteOldPrice($_GET['supplier_id']); 
 //    $count = $obj->getCsvTest($file[0]);
       
    
    //$count = $obj->insertCsv($file[0], $_GET['supplier_id']);
    //echo $count;
//}elseif($file = glob(__DIR__ . '/../prices/' . $tt['folder'] . '/*.txt')){
 //       
 //   //$obj->deleteOldPrice($_GET['supplier_id']);
//    $count = $obj->insertCsv($file[0], $_GET['supplier_id']);
//    echo $count;
}elseif($file = glob(__DIR__ . '/../prices/' . $tt['folder'] . '/*.{csv,txt}', GLOB_BRACE)){
    echo $file[0];
     $obj->deleteOldPrice($_GET['supplier_id']); 
     //$count = $obj->getCsvTest($file[0]);
     foreach($file as $fk=>$fv){
        
         echo '<br>' . $fk . ' hhh ' . $fv . '<br>'; 
        $count += $obj->insertCsv($file[$fk], $_GET['supplier_id']);
        }
     //$count = $obj->insertCsv($file[0],$_GET['supplier_id']);
    
}

$res = $obj->supplierLoadDate($_GET['supplier_id'], $count);

if($res === TRUE){
    echo 'вставлено ' . $count . ' строк!';
    sleep(3);
    //header('Location: /suppliers/index.php');
}

}
?>
<?php require '../tpl/footer.php';?>
