<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
ob_start();
require '../tpl/header.php';
require __DIR__ . "/../pricing/lib/MyDb.php";
require __DIR__ . "/../pricing/lib/GetPart.php";


$weight = 20;


$clear = new GetPart;
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
			ini_set('memory_limit', '-1');
			require __DIR__ . '/PriceInsert.php';
			require_once __DIR__ . '/../Excel/PHPExcel/IOFactory.php';
			$obj = new PriceInsert;
			
			$sups = $obj->getSuppliersWeigt($weight);
			$message = '<html><body>';
			
			foreach($sups as $sup){
			    try{
			    $message .= inserterPrice($sup['id'], $obj);
			    if($message == FALSE){
			        throw new Exception("Какая то ошибка при вставке поставщика с id =" . $sup['id']);
			    }
			    }catch(Exception $e){
			        echo 'Caught exception: ',  $e->getMessage(), "\n";
			    }
			}
			$message .= '</body></html>';
			$subject = 'Вставка прайсов поставщиков';
			$obj->sendEmail($subject, $message);
			
			function inserterPrice($supplier_id, $obj){
			    //Выбираем все поля поставщика из таблицы suppliers
			    
			    
			    $supplier = $obj -> getSupplier($supplier_id);
			    $tt = $supplier[0];
			    $tt['price_orig_number'] = $obj -> converterBack($tt['price_orig_number']);
			    $tt['price_oem_number'] = $obj -> converterBack($tt['price_oem_number']);
			    $tt['price_brand'] = $obj -> converterBack($tt['price_brand']);
			    $tt['price_name'] = $obj -> converterBack($tt['price_name']);
			    $tt['price_stock'] = $obj -> converterBack($tt['price_stock']);
			    $tt['price_price'] = $obj -> converterBack($tt['price_price']);
			    $tt['price_kratnost'] = $obj -> converterBack($tt['price_kratnost']);
			    $tt['price_notes'] = $obj -> converterBack($tt['price_notes']);
			    $tt['supplier_id'] = $supplier_id;
			    $obj -> fields = $tt;
			        $obj -> deleteOldPrice($supplier_id);
			        $count = 0;
			        if (!empty($tt['supplier_file1'])) {
			            $custom_files = explode(',', $tt['supplier_file1']);
			            foreach ($custom_files as $cfv) {
			                $file = glob(__DIR__ . '/../prices/' . $tt['folder'] . '/*' . $cfv . '*');
			                if($file == FALSE){
			                    return false;
			                }
			                $inputFileName = $file[0];
			                if (pathinfo($inputFileName, PATHINFO_EXTENSION) == 'csv' OR pathinfo($inputFileName, PATHINFO_EXTENSION) == 'txt') {
			                    $count += $obj -> insertCsv($inputFileName, $supplier_id);
			                    
			                } else {
			                    try {
			                        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
			                        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
			                        $objPHPExcel = $objReader -> load($inputFileName);
			                    } catch(Exception $e) {
			                        die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e -> getMessage());
			                    }//end of catch
			                    //  Get worksheet dimensions
			                    $sheet = $objPHPExcel -> getSheet(0);
			                    //p($sheet);
			                    $highestRow = $sheet -> getHighestRow();
			                    $highestColumn = $sheet -> getHighestColumn();
			                    
			                    
			                    //  Loop through each row of the worksheet in turn
			                    for ($row = 6; $row <= $highestRow; $row++) {
			                        //  Read a row of data into an array
			                        $rowData = $sheet -> rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
			                        $rowData[0][$obj -> fields['price_orig_number']] = substr(@$rowData[0][$obj -> fields['price_orig_number']], 0, 200);
			                        $rowData[0][$obj -> fields['price_oem_number']] = substr(@$rowData[0][$obj -> fields['price_oem_number']], 0, 200);
			                        $rowData[0][$obj -> fields['price_price']] = trim(preg_replace('@[\,\.].*$|\..*$|\sруб.@', '', @$rowData[0][$obj -> fields['price_price']]));
			                        $rowData[0][$obj -> fields['price_stock']] = trim(preg_replace('@[\<\>]@', '', @$rowData[0][$obj -> fields['price_stock']]));
			                        $rowData[0][$obj -> fields['supplier_id']]           = $supplier_id;
			                        $count += $obj -> insertPrice($rowData);
			                    }
			                }//end of if extention check
			            }//end of foreeach
			            
			            $res = $obj -> supplierLoadDate($supplier_id, $count);
			            if ($res === TRUE) {
			                $show = '<div width="500px">';
			                $show .= '<div style="padding: .75rem 1.25rem; margin-bottom: 1rem; border: 1px solid transparent; border-radius: .25rem; color: #155724; background-color: #d4edda; border-color: #c3e6cb;">Поставщик: ' . $tt['name'] . ' Вставлено ' . $count .' строк!</div>';
			                $show .= '</div>';
			                echo $show;
			                return $show;
			                //sleep(3);
			                //header('Location: /suppliers/index.php');
			            }else{
			                return FALSE;
			            }
			            
			        }
			        // Конец  БЛОК КОДА ЕСЛИ ФАЙЛЫ ЗАДАНЫ
			        
			        //Если файлы не заданы жестко то проверяем папку
			        else {
			            //проверяем папку на наличие файлов
			            
			            $supplier_files = glob(__DIR__ . '/../prices/' . $tt['folder'] . '/*.{csv,txt,xls,xlsx,xlsm}', GLOB_BRACE);
			            foreach ($supplier_files as $cfk => $cfv) {
			                $inputFileName = $cfv;
			                if (pathinfo($inputFileName, PATHINFO_EXTENSION) == 'csv' OR pathinfo($inputFileName, PATHINFO_EXTENSION) == 'txt') {
			                    $count += $obj -> insertCsv($inputFileName, $supplier_id);
			                    
			                } else {
			                    try {
			                        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
			                        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
			                        $objPHPExcel = $objReader -> load($inputFileName);
			                    } catch(Exception $e) {
			                        die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e -> getMessage());
			                    }//end of catch
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
			                        $rowData[0][$obj -> fields['price_orig_number']] = substr(@$rowData[0][$obj -> fields['price_orig_number']], 0, 200);
			                        $rowData[0][$obj -> fields['price_oem_number']] = substr(@$rowData[0][$obj -> fields['price_oem_number']], 0, 200);
			                        $rowData[0][$obj -> fields['price_price']] = trim(preg_replace('@[\,\.].*$@', '', @$rowData[0][$obj -> fields['price_price']]));
			                        $rowData[0][$obj -> fields['price_stock']] = trim(preg_replace('@[\,\.].*$@', '', @$rowData[0][$obj -> fields['price_stock']]));
			                        $rowData[0][$obj -> fields['supplier_id']]           = $supplier_id;
			                        $count += $obj -> insertPrice($rowData);
			                    }
			                }//end of if extention check
			            }//end of foreeach
			            $res = $obj -> supplierLoadDate($supplier_id, $count);
			            if ($res === TRUE) {
			                $show = '<div width="500px">';
			                $show .= '<div style="padding: .75rem 1.25rem; margin-bottom: 1rem; border: 1px solid transparent; border-radius: .25rem; color: #155724; background-color: #d4edda; border-color: #c3e6cb;">Поставщик: ' . $tt['name'] . ' Вставлено ' . $count .' строк!</div>';
			                $show .= '</div>';
			                echo $show;
			                return $show;
			            }else{
			                return FALSE;
			            }
			        }
			        
			        
			    
			}//end of inserterPrice function
			
			
			//Очищаем таблицу поставщиков от слова пробел + КОРЕЯ в названии бренда
			 $clear -> clearBrands('ang_prices_all');
			 
			 $clear -> brand_dict_table = 'brands_dict';
			 
			 //Таблица в которой меняем бренды
			 //$obj -> suppliers_table = 'cat_ang_all_cars_pricing_a';
			 $clear -> suppliers_table = 'ang_prices_all';
			 $clear -> changeBrands('ang_prices_all');
			 $clear -> clearBrandsEmp($clear -> suppliers_table);
			 
require '../tpl/footer.php';
?>
