<div class="row mt-5">
	<div class="col-6">
        <div id="progress" class="progress">
     </div>     
        </div>
</div>


<?php

// session_start();
// error_reporting(E_ALL);
ini_set("display_errors", 1);
include __DIR__ . '/../salesscript/header.php';

ini_set('max_execution_time', 60000000);
require_once 'config.php';
require_once 'autoloader.php';
$time1 = microtime(true);
try {

    // init configuration
    $armtek_client_config = new \armtekrestclient\http\config\config($user_settings);

    // init client
    $armtek_client = new \armtekrestclient\http\armtekrestclient($armtek_client_config);

    $table_car = 'cat_ang_unic_porter';
    $table_armtec = 'ang_prices_all_armtec';
    $cla = new Conn();
    $m = $cla->db1();
    $m2 = $cla->db(); // база ang_tecdoc
    $i = 1;
    $total = 1000;
    $q = "SELECT * FROM " . $table_car . " LIMIT " . $total;
    $t = $m->prepare($q);
    $t->execute(array());
    $data = $t->fetchAll(PDO::FETCH_ASSOC);
    //p($data[0]['cat'] = '411004B077');
    
    
    foreach ($data as $r2) {
        
        // Calculate the percentation
        $percent = intval($i/$total * 100)."%";
        
        echo '<script language="javascript">
    document.getElementById("progress").innerHTML="<div class=\"progress-bar bg-success\" style=\"width:'.$percent.';background-color:#ddd;\">&nbsp;</div>";
    document.getElementById("information").innerHTML="'.$i.' row(s) processed.";
    </script>';
        
        
        // This is for the buffer achieve the minimum size in order to flush data
        echo str_repeat(' ',1024*64);
        
        
        // Send output to browser immediately
        flush();
        
        //usleep(1000);
        
        $i++;
        $params = [
            'VKORG' => '5000',
            'KUNNR_RG' => '43039901',
            'PIN' => $r2['cat'],
            'BRAND' => '',
            'QUERY_TYPE' => '',
            'KUNNR_ZA' => '',
            'INCOTERMS' => '',
            'VBELN' => ''
        ];

        // requeest params for send
        $request_params = [

            'url' => 'search/search',
            'params' => [
                'VKORG' => ! empty($params['VKORG']) ? $params['VKORG'] : (isset($ws_default_settings['VKORG']) ? $ws_default_settings['VKORG'] : ''),
                'KUNNR_RG' => isset($params['KUNNR_RG']) ? $params['KUNNR_RG'] : (isset($ws_default_settings['KUNNR_RG']) ? $ws_default_settings['KUNNR_RG'] : ''),
                'PIN' => isset($params['PIN']) ? $params['PIN'] : '',
                'BRAND' => isset($params['BRAND']) ? $params['BRAND'] : '',
                'QUERY_TYPE' => isset($params['QUERY_TYPE']) ? $params['QUERY_TYPE'] : '',
                'KUNNR_ZA' => isset($params['KUNNR_ZA']) ? $params['KUNNR_ZA'] : (isset($ws_default_settings['KUNNR_ZA']) ? $ws_default_settings['KUNNR_ZA'] : ''),
                'INCOTERMS' => isset($params['INCOTERMS']) ? $params['INCOTERMS'] : (isset($ws_default_settings['INCOTERMS']) ? $ws_default_settings['INCOTERMS'] : ''),
                'VBELN' => isset($params['VBELN']) ? $params['VBELN'] : (isset($ws_default_settings['VBELN']) ? $ws_default_settings['VBELN'] : ''),
                'format' => 'json'
            ]
        ];

        // send data
        $response = $armtek_client->post($request_params);
        // in case of json
        $json_responce_data = $response->json();
        if ($json_responce_data) {
            $res = $json_responce_data->RESP;
            foreach($res as $array){
                if(isset($res -> ERROR)){
                    die("Todays Limit has exeeded:");
                }else{
                    insertArmtek($m2, $r2['cat'], $array, $table_armtec);
                }
                
            }
            
        } else {
            $res = '';
        }
     }
} catch (ArmtekException $e) {

    $json_responce_data = $e->getMessage();
}

//Выводим время в секундах и количество запросов
$time2 = microtime(true);
echo 'Количество запросов: ' . $i . ' | script execution time: ' . ($time2 - $time1) / 60; // value in seconds


function insertArmtek($m2, $cat, $array,$table = 'ang_prices_all_armtec')
{
    
    $supplier = 76;

    $ar = [':orig_number' => $cat,
        ':oem_number' => $array -> PIN,
        ':brand' => $array -> BRAND,
        ':name' => $array -> NAME,
        ':stock' => $array -> RVALUE,
        ':price' => $array -> PRICE,
        ':supplier' => $supplier];
    $q = "INSERT INTO " . $table . " (orig_number, oem_number, brand, name, stock, price, supplier) VALUES (:orig_number, :oem_number, :brand, :name, :stock, :price, :supplier)";
    //p($ar);
    $t = $m2->prepare($q);
    $t->execute($ar);
}
//удаляем старые записи из таблицы армтека
function clearOldArmtek(){
    $table_from = 'ang_prices_all_armtec';
    $table_car = 'cat_ang_unic_porter';
    $q = "DELETE " . $table . " as a FROM a INNER JOIN " . $table_car . " as b ON a.orig_number = b.cat";
    //DELETE a FROM ang_tecdoc.ang_prices_all_armtec a INNER JOIN angara_test.cat_ang_unic_porter as b ON a.orig_number = b.cat
    //p($ar);
}

?>



