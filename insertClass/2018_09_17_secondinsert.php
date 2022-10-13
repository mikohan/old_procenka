<?php
ob_start();
require '../tpl/header.php';
?>
<div class="spacer-60"></div>
<div class="container-fluid">
	<div class="row">

		<?php
        require '../tpl/left.php';
		?>
		<main class="col-sm-9 ml-sm-auto col-md-10 pt-3" role="main">
			<!-- <h1>Dashboard</h1> -->
			<!-- <h2>Добавление поставщика</h2> -->

			<?php

            // Этот файл будет читать ексель а insertClass записывать в базу
            ini_set('memory_limit', '-1');
            //error_reporting(E_ALL);
            //ini_set("display_errors", 1);
            //require 'config.php';
            //require __DIR__ . '/Conn.php';

            require __DIR__ . '/PriceInsert.php';
            require_once __DIR__ . '/../Excel/PHPExcel/IOFactory.php';

            //Выбираем все поля поставщика из таблицы suppliers

            $obj = new PriceInsert;
            $table = 'ang_prices_all';
            //$obj->fields =  ['orig_number'=>2, 'oem_number'=>3, 'brand'=>4, 'name'=>5, 'stock'=>6, 'price'=>8, 'supplier'=>'tokus'];

            $supplier = $obj -> getSupplier($_GET['supplier_id']);
            $tt = $supplier[0];
            $tt['price_orig_number'] = $obj -> converterBack($tt['price_orig_number']);
            $tt['price_oem_number'] = $obj -> converterBack($tt['price_oem_number']);
            $tt['price_brand'] = $obj -> converterBack($tt['price_brand']);
            $tt['price_name'] = $obj -> converterBack($tt['price_name']);
            $tt['price_stock'] = $obj -> converterBack($tt['price_stock']);
            $tt['price_price'] = $obj -> converterBack($tt['price_price']);
            $tt['price_kratnost'] = $obj -> converterBack($tt['price_kratnost']);
            $tt['price_notes'] = $obj -> converterBack($tt['price_notes']);
            $obj -> fields = $tt;

            //$obj->p($tt);

            //$obj->fields =  ['orig_number'=>4, 'oem_number'=>2, 'brand'=>1, 'name'=>3, 'stock'=>7, 'price'=>9, 'notes'=>4, 'supplier'=>'moskvorechie'];

            if ($_GET['action'] == 'insert_price') {
                $obj -> deleteOldPrice($_GET['supplier_id']);
                //удаляем старый прайс
                //если есть конкретный массив файлов
                // БЛОК КОДА ЕСЛИ ФАЙЛЫ ЗАДАНЫ
                //
                //
                //
                //
                $count = 0;
                if (!empty($tt['supplier_file1'])) {
                    $custom_files = explode(',', $tt['supplier_file1']);
                    
                    //p($custom_files);

                    foreach ($custom_files as $cfk => $cfv) {
                        $file = glob(__DIR__ . '/../prices/' . $tt['folder'] . '/*' . $cfv . '*');
                       // p($file);

                        $inputFileName = $file[0];

                        if (pathinfo($inputFileName, PATHINFO_EXTENSION) == 'csv' OR pathinfo($inputFileName, PATHINFO_EXTENSION) == 'txt') {

                            //echo '<br> txt OR csv <br>';
                            $count += $obj -> insertCsv($inputFileName, $_GET['supplier_id']);

                        } else {
                            try {
                                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                                $objPHPExcel = $objReader -> load($inputFileName);
                            } catch(Exception $e) {
                                die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e -> getMessage());
                            }//end of catch
                            //p($inputFileType);

                            //  Get worksheet dimensions
                            $sheet = $objPHPExcel -> getSheet(0);
                            //p($sheet);
                            $highestRow = $sheet -> getHighestRow();
                            $highestColumn = $sheet -> getHighestColumn();

                            
                            //  Loop through each row of the worksheet in turn
                            for ($row = 6; $row <= $highestRow; $row++) {
                                //  Read a row of data into an array
                                $rowData = $sheet -> rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

                                //if($rowData[0][$obj->fields['orig_number']] == '') continue;
                                //$rowData[0][$obj->fields['name']] = mb_strtolower($rowData[0][$obj->fields['name']], 'UTF-8');
                                //echo $rowData[0][$obj->fields['name']];
                                //$rowData[0][$obj -> fields['orig_number']] = mb_strtolower(trim(str_replace("'", '', @$rowData[0][$obj -> fields['orig_number']]), 'UTF-8'));
                                //$rowData[0][$obj -> fields['oem_number']] = mb_strtolower(trim(str_replace("'", '', @$rowData[0][$obj -> fields['oem_number']]), 'UTF-8'));
                                $rowData[0][$obj -> fields['price_price']] = trim(preg_replace('@[\,]|\.00|\sруб.@u', '', @$rowData[0][$obj -> fields['price_price']]));
                                $rowData[0][$obj -> fields['price_stock']] = trim(preg_replace('@[\<\>]@u', '', @$rowData[0][$obj -> fields['price_stock']]));

                                //$obj->p($rowData);
                                
                                $count += $obj -> insertPrice($rowData);
                            }
                        }//end of if extention check
                    }//end of foreeach

                    $res = $obj -> supplierLoadDate($_GET['supplier_id'], $count);
                    if ($res === TRUE) {
                        echo 'вставлено ' . $count . ' строк!';
                        sleep(3);
                        header('Location: /suppliers/index.php');
                    }

                }
                // Конец  БЛОК КОДА ЕСЛИ ФАЙЛЫ ЗАДАНЫ
                //
                //
                //
                //Если файлы не заданы жестко то проверяем папку
                else {
                        //$count = 0;
                    //проверяем папку на наличие файлов

                     $supplier_files = glob(__DIR__ . '/../prices/' . $tt['folder'] . '/*.{csv,txt,xls,xlsx,xlsm}', GLOB_BRACE);
                     //p($supplier_files);

                    foreach ($supplier_files as $cfk => $cfv) {
                        //p($cfv);
                        //$file = glob(__DIR__ . '/../prices/' . $tt['folder'] . '/*' . $cfv . '*');
                        //p($file);

                        $inputFileName = $cfv;

                        if (pathinfo($inputFileName, PATHINFO_EXTENSION) == 'csv' OR pathinfo($inputFileName, PATHINFO_EXTENSION) == 'txt') {

                            //echo '<br> txt OR csv <br>';
                            $count += $obj -> insertCsv($inputFileName, $_GET['supplier_id']);

                        } else {
                            try {
                                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                                $objPHPExcel = $objReader -> load($inputFileName);
                            } catch(Exception $e) {
                                die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e -> getMessage());
                            }//end of catch
                            //p($inputFileType);

                            //  Get worksheet dimensions
                            $sheet = $objPHPExcel -> getSheet(0);
                            //p($sheet);
                            $highestRow = $sheet -> getHighestRow();
                            //$highestRow = 40;
                            $highestColumn = $sheet -> getHighestColumn();

                            
                            //  Loop through each row of the worksheet in turn
                            for ($row = 7; $row <= $highestRow; $row++) {
                                //  Read a row of data into an array
                                $rowData = $sheet -> rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

                                //if($rowData[0][$obj->fields['orig_number']] == '') continue;
                                //$rowData[0][$obj->fields['name']] = mb_strtolower($rowData[0][$obj->fields['name']], 'UTF-8');
                                //echo $obj->rowKnife($rowData[0][$obj->fields['price_name']], 15);
                                //p($obj->fields);
                                //$rowData[0][$obj -> fields['price_orig_number']] = mb_strtolower(trim(str_replace("'", '', @$rowData[0][$obj -> fields['pice_orig_number']]), 'UTF-8'));
                                //$rowData[0][$obj -> fields['price_oem_number']] = mb_strtolower(trim(str_replace("'", '', @$rowData[0][$obj -> fields['price_oem_number']]), 'UTF-8'));
                                $rowData[0][$obj -> fields['price_price']] = trim(preg_replace('@[^\d\,\.]@', '', @$rowData[0][$obj -> fields['price_price']]));
                                $rowData[0][$obj -> fields['price_stock']] = trim(preg_replace('@[^\d\,\.]@', '', @$rowData[0][$obj -> fields['price_stock']]));
                                
                                //$obj->p($rowData);
                                
                                $count += $obj -> insertPrice($rowData);
                            }
                        }//end of if extention check
                    }//end of foreeach
                    $res = $obj -> supplierLoadDate($_GET['supplier_id'], $count);
                    if ($res === TRUE) {
                        echo 'вставлено ' . $count . ' строк!';
                        sleep(5);
                        header('Location: /suppliers/index.php');
                    }
                }
            }
        ?>
<?php
require '../tpl/footer.php';
?>
