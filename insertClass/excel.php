<?php
// Этот файл переводит в excel в csv
error_reporting(E_ALL); 
ini_set("display_errors", 1);

require_once 'Excel/PHPExcel/IOFactory.php';

$inputFileType = 'Excel5';
$inputFileName = 'prices/tokus.xls';

$objReader = PHPExcel_IOFactory::createReader($inputFileType);
$objPHPExcelReader = $objReader->load($inputFileName);

$loadedSheetNames = $objPHPExcelReader->getSheetNames();

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcelReader, 'CSV');

foreach($loadedSheetNames as $sheetIndex => $loadedSheetName) {
    $objWriter->setSheetIndex($sheetIndex);
    $objWriter->save($loadedSheetName.'.csv');
}