<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

	include_once('../../admin/app/lib/simple_html_dom.php');
	include_once('../../admin/app/lib/curl_query.php');



function db() {

    try {
        $dsn = 'mysql:dbname=u66745_ducato' . ';host=localhost';
        $pdo = new PDO($dsn, 'root', 'manhee33338');
        $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo -> exec("set names utf8");

    } catch(PDOException $e) {
        echo $e -> getMessage();
    }
    return $pdo;

}


function tovar_category ($cat_number) {
        $m = db();
        $query = 'SELECT id,article_orig FROM parts_name_ducato1 WHERE id BETWEEN 1970 AND 2970';
        //$param1 = $m -> quote($param1);
        $sth = $m -> prepare($query);
        $sth -> execute(array($cat_number));
        $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }//Конец функции



    function tobd ($kValue, $origpin) {
            $m = db();

						$tvalue = get_object_vars ($kValue);
						p($kValue);
						//var_dump($tvalue);
						//var_dump($value);

            $query = 'INSERT INTO ducato_armtek1 (ORIGPIN, PIN, BRAND, NAME, ARTID, PARNR, KEYZAK, RVALUE, RDPRF, MINBM, VENSL, PRICE, WAERS, DLVDT, ANALOG) VALUES (:ORIGPIN, :PIN,  :BRAND, :NAME, :ARTID, :PARNR, :KEYZAK, :RVALUE, :RDPRF, :MINBM, :VENSL, :PRICE, :WAERS, :DLVDT, :ANALOG)';
            //$param1 = $m -> quote($param1);

						// $price_int = preg_replace('/\.+.*/','',$tvalue['PRICE']);


//p($price_int);
            $sth = $m -> prepare($query);
            $sth -> execute(array(
						':ORIGPIN' =>$origpin,
            ':PIN' =>$tvalue['PIN'],
            ':BRAND' =>$tvalue['BRAND'],
            ':NAME' =>$tvalue['NAME'],
            ':ARTID' =>$tvalue['ARTID'],
		    		':PARNR' =>$tvalue['PARNR'],
		    		':KEYZAK' =>$tvalue['KEYZAK'],
		    		':RVALUE' =>$tvalue['RVALUE'],
		    		':RDPRF' =>$tvalue['RDPRF'],
		    		':MINBM' =>$tvalue['MINBM'],
		    		':VENSL' =>$tvalue['VENSL'],
		    		':PRICE' =>$tvalue['PRICE'],
		    		':WAERS' =>$tvalue['WAERS'],
		    		':DLVDT' =>$tvalue['DLVDT'],
		    		':ANALOG' =>$tvalue['ANALOG']));
            //$data = $sth -> fetchAll(PDO::FETCH_ASSOC);
            //return $data;



        }//Конец функции
