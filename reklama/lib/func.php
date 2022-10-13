<?php


function filetime_callback($a, $b) {
    if (filemtime($a) === filemtime($b))
        return 0;
    return filemtime($a) > filemtime($b) ? -1 : 1;
}

function get_image($id) {
    $f = '';
    $dir = ANG_ROOT . "img/parts/parts/";
    // p($dir);
    $pattern = strtolower($dir . '*-' . $id . '\.{jpg,png,gif}');
    // p($pattern);
    $file2 = glob($pattern, GLOB_BRACE);
    // p(glob("/home/angara/zakupki.loc/img/parts/parts/*-" . $id . ".jpg"));
    // p($file2);



    usort($file2, "filetime_callback");
    foreach ($file2 as $ft) {
        $temp = explode('/', $ft);
        $file_ext = end($temp);
        $file3[] = array('time' => filemtime($ft), 'file' => $file_ext);
    }
    if (isset($file3)) {
        //p($file);
        return $file3[0]['file'];
    } else {
        $file = '';
        return $file;
    }

}//Конец функции

function db2() {
    $ANG_HOST = 'localhost';
    $ANG_DBNAME ='product';
    $ANG_DBUSER = 'root';
    $ANG_DBPASS = 'manhee33338';
    try {
        $dsn = 'mysql:dbname=' . $ANG_DBNAME . ';host=' . $ANG_HOST;
        $pdo = new PDO($dsn, $ANG_DBUSER, $ANG_DBPASS);
        $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo -> exec("set names utf8");
    } catch(PDOException $e) {
        echo $e -> getMessage();
    }
    return $pdo;
}

function db1() {
    $ANG_HOST = 'localhost';
    $ANG_DBNAME ='ang_tecdoc';
    $ANG_DBUSER = 'root';
    $ANG_DBPASS = 'manhee33338';
    try {
        $dsn = 'mysql:dbname=' . $ANG_DBNAME . ';host=' . $ANG_HOST;
        $pdo = new PDO($dsn, $ANG_DBUSER, $ANG_DBPASS);
        $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo -> exec("set names utf8");
    } catch(PDOException $e) {
        echo $e -> getMessage();
    }
    return $pdo;
}

function db3() {
    $ANG_HOST = 'localhost';
    $ANG_DBNAME ='auctioner';
    $ANG_DBUSER = 'root';
    $ANG_DBPASS = 'manhee33338';
    try {
        $dsn = 'mysql:dbname=' . $ANG_DBNAME . ';host=' . $ANG_HOST;
        $pdo = new PDO($dsn, $ANG_DBUSER, $ANG_DBPASS);
        $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo -> exec("set names utf8");
    } catch(PDOException $e) {
        echo $e -> getMessage();
    }
    return $pdo;
}

function db4() {
    $ANG_HOST = 'localhost';
    $ANG_DBNAME ='test';
    $ANG_DBUSER = 'root';
    $ANG_DBPASS = 'manhee33338';
    try {
        $dsn = 'mysql:dbname=' . $ANG_DBNAME . ';host=' . $ANG_HOST;
        $pdo = new PDO($dsn, $ANG_DBUSER, $ANG_DBPASS);
        $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo -> exec("set names utf8");
    } catch(PDOException $e) {
        echo $e -> getMessage();
    }
    return $pdo;
}

function get_parsing_info(){
	$m = db2();
	$query = 'SELECT DISTINCT *
	FROM parsing';
	//$param1 = $m -> quote($param1);
	$sth = $m -> prepare($query);
	$sth -> execute();
	$data = $sth -> fetchAll(PDO::FETCH_ASSOC);
	return $data;


}
function get_all_from_table($table){
	$m = db2();
	$query = 'SELECT DISTINCT *
	FROM ' . $table;
	//$param1 = $m -> quote($param1);
	$sth = $m -> prepare($query);
	$sth -> execute();
	$data = $sth -> fetchAll(PDO::FETCH_ASSOC);
	return $data;


}

function get_by_car($table,$car){
	$m = db2();
	if (empty($car)) {
		$where='';
	}else{
		$where=' WHERE my_car LIKE "%' . $car . '%"';
	}
	$query = 'SELECT DISTINCT *
	FROM ' . $table . $where;
	//$param1 = $m -> quote($param1);
	$sth = $m -> prepare($query);
	$sth -> execute();
	// p($sth);
	$data = $sth -> fetchAll(PDO::FETCH_ASSOC);
	return $data;
}







function check_before_insert_to_car($r,$final_table){
	$a='';
	$a2='';
	foreach ($r as $key => $value) {
		if ($key=="id") {

		}else{
		$a .=$key . ',';
		$a2 .=$key . '="' . $value . '" AND ';
		}
	}
	$res=rtrim($a,',');
	$res2=rtrim($a2,' AND ');
	$m = db2();
	$query = 'SELECT DISTINCT *
	FROM ' . $final_table . ' WHERE ' . $res2;
	$sth = $m -> prepare($query);
	$sth -> execute();
	$data = $sth -> fetchAll(PDO::FETCH_ASSOC);
	return $data;
}


function insert_to_car($r,$final_table) {
	$a='';
	$a2='';
	foreach ($r as $key => $value) {
		if ($key=="id") {

		}else{
		$a .=$key . ',';
		$a2 .='"' . $value . '", ';
		}
	}
	$res=rtrim($a,',');
	$res2=rtrim($a2,', ');
	$m = db2();
	$query = 'INSERT INTO ' . $final_table . ' (' . $res . ') VALUES (' . $res2 . ')';
	// p($query);
	$sth = $m -> prepare($query);
	$sth -> execute();

}



function get_one_tovar($table) {

   $m = db2();
   $query = 'SELECT *
   FROM ' . $table . ' LIMIT 1';
   //$param1 = $m -> quote($param1);
   $sth = $m -> prepare($query);
   $sth -> execute();
   $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
   return $data;
 }//Конец функции


 function get_uniqe_number($table) {

    $m = db2();
    $query = 'SELECT *
    FROM ' . $table . '';
    //$param1 = $m -> quote($param1);
    $sth = $m -> prepare($query);
    $sth -> execute();
    $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
    return $data;
  }//Конец функцииupdate_uniqe_name_magaz



function get_tovar_magaz($table_all,$seller_id,$number) {

	 $m = db2();
	 $query = 'SELECT DISTINCT number,name,brand
	 FROM ' . $table_all . ' WHERE seller=' . $seller_id . ' AND number LIKE "' . $number . '"';
	 //$param1 = $m -> quote($param1);
	 $sth = $m -> prepare($query);
	 $sth -> execute();
	 $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
	 return $data;
 }//Конец функции



function update_uniqe_magaz($table_uniqe,$seller_id,$count_magaz,$id) {

	$m = db2();
	$query = 'UPDATE ' . $table_uniqe . ' SET `sel' . $seller_id . '`=' . $count_magaz . ' WHERE id=' . $id;
	// p($query);
	$sth = $m -> prepare($query);
	$sth -> execute();

}

function update_uniqe_name_magaz($table_uniqe,$name,$id) {

	$m = db2();
	$query = 'UPDATE ' . $table_uniqe . ' SET name="' . $name . '" WHERE id=' . $id . ' AND name=""';
	 p($query);
	$sth = $m -> prepare($query);
	$sth -> execute();

}



function select_brands($table,$number) {

	 $m = db2();
	 $query = 'SELECT DISTINCT brand
	 FROM ' . $table . ' WHERE number LIKE "' . $number . '"';
	 //$param1 = $m -> quote($param1);
	 $sth = $m -> prepare($query);
	 $sth -> execute();
	 $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
	 return $data;
 }//Конец функции





function update_brands_pole($table_uniqe,$id,$count_brands,$brands) {

	$m = db2();
	$query = 'UPDATE ' . $table_uniqe . ' SET count_brands="' . $count_brands . '", brand="' . $brands . '" WHERE id=' . $id . '';
	 p($query);
	$sth = $m -> prepare($query);
	$sth -> execute();

}


function update_brands_pole_procenka($table_uniqe,$id,$count_brands,$brands,$pole_brands,$pole_count) {

	$m = db2();
	$query = 'UPDATE ' . $table_uniqe . ' SET ' . $pole_count . '="' . $count_brands . '", ' . $pole_brands . '="' . $brands . '" WHERE id=' . $id . '';
	 p($query);
	$sth = $m -> prepare($query);
	$sth -> execute();

}




function select_brands_procenka($table,$number) {

	 $m = db1();
	 $query = 'SELECT DISTINCT brand
	 FROM ' . $table . ' WHERE orig_number LIKE "' . $number . '" OR oem_number LIKE "' . $number . '"';
	 //$param1 = $m -> quote($param1);
	 $sth = $m -> prepare($query);
	 $sth -> execute();
	 $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
	 return $data;
 }//Конец функции


function get_uniqe_number_zzap($table) {

	 $m = db3();
	 $query = 'SELECT *
	 FROM ' . $table . ' WHERE cheked_zzap=1';
	 //$param1 = $m -> quote($param1);
	 $sth = $m -> prepare($query);
	 $sth -> execute();
	 $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
	 return $data;
 }//Конец функцииupdate_uniqe_name_magaz


 function select_brands_zzap($table,$number,$stop_brand2,$stop_brand1) {

 	 $m = db3();
 	 $query = 'SELECT DISTINCT class_man as brand
 	 FROM ' . $table . ' WHERE query_partnumber LIKE "' . $number . '" AND (type_search=21 OR type_search=31) AND class_man NOT LIKE "%' . $stop_brand2 . '%" AND class_man NOT LIKE "%' . $stop_brand1 . '%"';
 	 //$param1 = $m -> quote($param1);
 	 $sth = $m -> prepare($query);
 	 $sth -> execute();
 	 $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
 	 return $data;
  }//Конец функции

	function update_brands_pole_zzap($table_uniqe,$number,$count_brands,$brands,$pole_brands,$pole_count) {

		$m = db2();
		$query = 'UPDATE ' . $table_uniqe . ' SET ' . $pole_count . '="' . $count_brands . '", ' . $pole_brands . '="' . $brands . '" WHERE number="' . $number . '"';
		 p($query);
		$sth = $m -> prepare($query);
		$sth -> execute();

	}


	function get_my_car_info($table) {

  	 $m = db2();
  	 $query = 'SELECT DISTINCT *
  	 FROM ' . $table;
  	 //$param1 = $m -> quote($param1);
  	 $sth = $m -> prepare($query);
  	 $sth -> execute();
  	 $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
  	 return $data;
   }//Конец функции


function update_mycar($sql) {

	$m = db2();
	$query = $sql;
	$sth = $m -> prepare($query);
	$sth -> execute();

}


function update_sql($sql) {

	$m = db2();
	$query = $sql;
	$sth = $m -> prepare($query);
	$sth -> execute();

}

function select_sql($sql) {

	$m = db2();
	$query = $sql;
	$sth = $m -> prepare($query);
	$sth -> execute();
	$data = $sth -> fetchAll(PDO::FETCH_ASSOC);
	return $data;
}

function select_sql_db3($sql) {

	$m = db3();
	$query = $sql;
	$sth = $m -> prepare($query);
	$sth -> execute();
	$data = $sth -> fetchAll(PDO::FETCH_ASSOC);
	return $data;
}

function insert_sql_db3($sql) {

	$m = db3();
	$query = $sql;
	$sth = $m -> prepare($query);
	$sth -> execute();

}

function insert_into($sql) {

	$m = db2();
	$query = $sql;
	$sth = $m -> prepare($query);
	$sth -> execute();
}

function select_sql_db4($sql) {

	$m = db4();
	$query = $sql;
	$sth = $m -> prepare($query);
	$sth -> execute();
	$data = $sth -> fetchAll(PDO::FETCH_ASSOC);
	return $data;
}

function update_sql_db4($sql) {

	$m = db4();
	$query = $sql;
	$sth = $m -> prepare($query);
	$sth -> execute();

}

function get_padeji($id) {

   $m = db();
   $query = 'SELECT DISTINCT *
   FROM category4 WHERE cat_id=' . $id;
   //$param1 = $m -> quote($param1);
   $sth = $m -> prepare($query);
   $sth -> execute();
   $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
   return $data;
 }//Конец функции



?>
