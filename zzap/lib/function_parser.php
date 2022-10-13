<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include_once('simple_html_dom.php');
include_once('curl_query.php');


function db() {
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

function tovar_category ($table,$column1,$column2) {

        $m = db();
        $query = 'SELECT id,' . $column1 . ',' . $column2 . ' FROM `' . $table . '` WHERE cheked_zzap=0';
        //$param1 = $m -> quote($param1);
        $sth = $m -> prepare($query);
        $sth -> execute(array($table));
        $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
        return $data;


    }//Конец функции
    function to_bd_done ($table,$id) {

            $m = db();
            $query = 'UPDATE `' . $table . '` SET cheked_zzap=1 WHERE id=' . $id;
            //$param1 = $m -> quote($param1);
            $sth = $m -> prepare($query);
            $sth -> execute();
          }//Конец функции

          function to_bd_error ($table,$id,$errortext) {

                  $m = db();
                  $query = 'UPDATE `' . $table . '`
                  SET cheked_zzap=2,errortext="' . $errortext . '"
                  WHERE id=' . $id;
                  //$param1 = $m -> quote($param1);
                  $sth = $m -> prepare($query);
                  $sth -> execute();
                }//Конец функции

                function to_bd_none ($table,$id) {

                        $m = db();
                        $query = 'UPDATE `' . $table . '`
                        SET cheked_zzap=3
                        WHERE id=' . $id;
                        //$param1 = $m -> quote($param1);
                        $sth = $m -> prepare($query);
                        $sth -> execute();
                      }//Конец функции

    function tobd ($vValue,$r2,$r3,$table2) {
            $m = db();

            foreach($vValue as $key => $svalue){
								//p($svalue);

            $query = 'INSERT INTO ' . $table2 . ' (real_brand,class_man,query_partnumber,partnumber,class_cat,imagepath,
              qty,instock,wholesale,local,price,price_date,descr_price,descr_qty,class_user,
            descr_rating_count,rating,descr_address,phone1,order_text,user_key,logopath,used,type_search)

            VALUES (:real_brand,:class_man,:query_partnumber,:partnumber,:class_cat,:imagepath,:qty,:instock,:wholesale,:local,:price,:price_date,:descr_price,:descr_qty,:class_user,:descr_rating_count,
    				:rating,:descr_address,:phone1,:order_text,:user_key,:logopath,:used,:type_search)';
            //$param1 = $m -> quote($param1);

            $sth = $m -> prepare($query);
            $sth -> execute(array(
            ':real_brand' =>$r3,
            ':class_man' =>$svalue['class_man']=preg_replace("/[^\p{L},\s]/u","",$svalue['class_man']),
            ':query_partnumber' =>$r2,
            ':partnumber' =>$svalue['partnumber'],
            ':class_cat' =>$svalue['class_cat'],
            ':imagepath' =>$svalue['imagepath'],
            ':qty' =>$svalue['qty'],
		    		':instock' =>$svalue['instock'],
		    		':wholesale' =>$svalue['wholesale'],
		    		':local' =>$svalue['local'],
		    		':price' =>preg_replace('/[^0-9]/','',$svalue['price']),
		    		':price_date' =>$svalue['price_date'],
		    		':descr_price' =>$svalue['descr_price'],
		    		':descr_qty' =>$svalue['descr_qty'],
		    		':class_user' =>$svalue['class_user'],
		    		':descr_rating_count' =>$svalue['descr_rating_count'],
		    		':rating' =>$svalue['rating'],
		    		':descr_address' =>$svalue['descr_address'],
		    		':phone1' =>$svalue['phone1'],
		    		':order_text' =>$svalue['order_text'],
		    		':user_key' =>$svalue['user_key'],
		    		':logopath' =>$svalue['logopath'],
		    		':used' =>$svalue['used'],
            ':type_search' =>$svalue['type_search']));
            //$data = $sth -> fetchAll(PDO::FETCH_ASSOC);
            //return $data;
            }

        }//Конец функции
