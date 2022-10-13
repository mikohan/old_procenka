<?php
require_once '../config.php';
error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set('max_execution_time', 0);
require_once ANG_ROOT . "functions.php";
include ANG_ROOT . "product/lib/func.php";

$t_my_car="my_car";
$parsing_info=get_parsing_info();
$my_car_info=get_my_car_info($t_my_car);
$my_table_magaz="parsing";
$all_magaz=get_all_from_table($my_table_magaz);
?>
<div class="container" style="border:1px solid #aa4322;padding:10px;">


            <div class="col-md-4">
            Работа с таблицами парсинга
            <form action="" method="get" style="border:1px solid #aa4322;padding:10px;">
              <select name="do">
                <option value="0"></option>
              <option value="1">найти и вставить машины</option>
              <option value="2">соединить в общую таблицу</option>
              <option value="33">вставляем уники по машине</option>
              <option value="3">кол-во предложений и пересечений</option>
              <option value="4">наименования для уников</option>
            </select>
            <select name="table">
              <option value=""></option>
              <?php

              $selected="selected";
            foreach ($parsing_info as $key => $value) {?>
              <?php if ($value['table_name']==$_GET['table']){ ?>
                <option <?=$selected?> value=<?=$value['table_name']?>><?=$value['company']?></option>
              <?php }else{ ?>
                <option value=<?=$value['table_name']?>><?=$value['company']?></option>
              <?php } ?>
              <?php }?>
              <option value="auto">auto</option>
            </select>
               <p><input type="submit" value="Отправить"></p>
              </form>
            </div>



            <div class="col-md-4">
            работа с брендами
            <form action="" method="get" style="border:1px solid #aa4322;padding:10px;">
              <select name="brands_do">
                <option value="0"></option>
              <option value="1">из спаршеных</option>
              <option value="2">из проценки основные поставщики</option>
              <option value="3">из проценки все поставщики 3млн</option>
              <option value="4">из zzap вытаскиваем бренды где 21 и 31</option>
              <option value="auto">auto</option>
            </select>
            <select name="table">
              <option value=""></option>
              <?php

              $selected="selected";
            foreach ($parsing_info as $key => $value) {?>
              <?php if ($value['table_name']==$_GET['table']){ ?>
                <option <?=$selected?> value=<?=$value['table_name']?>><?=$value['company']?></option>
              <?php }else{ ?>
                <option value=<?=$value['table_name']?>><?=$value['company']?></option>
              <?php } ?>
              <?php }?>
              <option value="p_all">p_all</option>
              <option value="auto">auto</option>
            </select>
               <p><input type="submit" value="Отправить"></p>
              </form>
            </div>



            <div class="col-md-4">
            словарь брендов
            <form action="" method="get" style="border:1px solid #aa4322;padding:10px;">
              <select name="s_brands">
                <option value="0"></option>
              <option value="1">вставить бренды в словарь с ззап</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="auto">auto</option>
            </select>
            <select name="table">
              <option value=""></option>
              <option value="p_all">s_brands_php</option>
              <option value="auto">auto</option>
            </select>
               <p><input type="submit" value="Отправить"></p>
              </form>
            </div>


            <div class="col-md-4">
            составить номенклатуру
            <form action="" method="get" style="border:1px solid #aa4322;padding:10px;">
              <select name="nomenklatura">
                <option value="0"></option>
              <option value="1">создать таблицу с номенклатурой</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="auto">auto</option>
            </select>
            <select name="table">
              <option value=""></option>
              <option value="p_all">s_brands_php</option>
              <option value="auto">auto</option>
            </select>
               <p><input type="submit" value="Отправить"></p>
              </form>
            </div>


</div>
<hr>
<div class="container" style="border:1px solid #aa4322;padding:10px;display:block">
<?php
$table=1;
$name=2;
$article=3;
$brand=4;
//get_all_tovar_parsing()


if (isset($_GET['do'])) {

    if ($_GET['do']==1) {


                              if (isset($_GET['table']) AND $_GET['table']=="auto") {
                                echo "автоматический режим колбасим все таблицы вставляем в них машины";


                                foreach ($all_magaz as $key => $magaz) {
                                  p($magaz['company']);
                                  if ($magaz['check_1']==1) {

                                    echo "все уже сделано если хотите прогнать еще раз, выберите конкретную таблицу или поставьте check_1=0 в таблице магазинов ";
                                  }else{
                                  $table=$magaz['table_name'];
                                    $all=get_all_from_table($table);
                                    echo "<br> пример строки:";
                                    p($all[0]);
                                    echo "<hr>";
                                    $sql1='UPDATE ' . $table . ' SET my_car=""';
                                    update_mycar($sql1);
                                      foreach ($my_car_info as $key => $mycar) {
                                        p($mycar);
                                        $arr_mycar=explode(',',$mycar['words']);

                                          if (isset($all[0]['name']) AND isset($all[0]['car'])) {
                                            $a='';
                                                foreach ($arr_mycar as $key => $words_mycar) {
                                                  $a .='name LIKE "%' . $words_mycar . '%" OR ';
                                                  $a .='car LIKE "%' . $words_mycar . '%" OR ';
                                                }
                                                $res_mycar=rtrim($a,' OR');
                                                $sql='UPDATE ' . $table . '
                                                SET my_car=IF(my_car="" , "' . $mycar['my_car'] . '" , CONCAT("' . $mycar['my_car'] . '",",",my_car))
                                                WHERE ' . $res_mycar;
                                                p($sql);
                                                update_mycar($sql);
                                          }elseif (isset($all[0]['name']) AND !isset($all[0]['car'])) {
                                            $a='';
                                                foreach ($arr_mycar as $key => $words_mycar) {
                                                  $a .='name LIKE "%' . $words_mycar . '%" OR ';
                                                }
                                                $res_mycar=rtrim($a,' OR');
                                                $sql='UPDATE ' . $table . '
                                                SET my_car=IF(my_car="" , "' . $mycar['my_car'] . '" , CONCAT("' . $mycar['my_car'] . '",",",my_car))
                                                WHERE ' . $res_mycar;
                                                p($sql);
                                                update_mycar($sql);
                                          }elseif (isset($all[0]['car']) AND !isset($all[0]['name'])) {
                                            $a='';
                                                foreach ($arr_mycar as $key => $words_mycar) {
                                                  $a .='car LIKE "%' . $words_mycar . '%" OR ';
                                                }
                                                $res_mycar=rtrim($a,' OR');
                                                $sql='UPDATE ' . $table . '
                                                SET my_car=IF(my_car="" , "' . $mycar['my_car'] . '" , CONCAT("' . $mycar['my_car'] . '",",",my_car))
                                                WHERE ' . $res_mycar;
                                                p($sql);
                                                update_mycar($sql);
                                          }


                                      }
                                      $sql='UPDATE ' . $my_table_magaz . ' SET check_1=1 WHERE id=' . $magaz['id'];
                                      update_mycar($sql);
                                    }
                                }


                              }elseif (isset($_GET['table']) AND !empty($_GET['table'] AND $_GET['table']!=="auto") ) {
                                echo "найти и вставить машины";
                                $table=$_GET['table'];
                                  $all=get_all_from_table($_GET['table']);
                                  echo "<br> пример строки:";
                                  p($all[0]);
                                  echo "<hr>";
                                  $sql1='UPDATE ' . $table . ' SET my_car=""';
                                  update_mycar($sql1);
                                    foreach ($my_car_info as $key => $mycar) {
                                      p($mycar);
                                      $arr_mycar=explode(',',$mycar['words']);

                                        if (isset($all[0]['name']) AND isset($all[0]['car'])) {
                                          $a='';
                                              foreach ($arr_mycar as $key => $words_mycar) {
                                                $a .='name LIKE "%' . $words_mycar . '%" OR ';
                                                $a .='car LIKE "%' . $words_mycar . '%" OR ';
                                              }
                                              $res_mycar=rtrim($a,' OR');
                                              $sql='UPDATE ' . $table . '
                                              SET my_car=IF(my_car="" , "' . $mycar['my_car'] . '" , CONCAT("' . $mycar['my_car'] . '",",",my_car))
                                              WHERE ' . $res_mycar;
                                              p($sql);
                                              update_mycar($sql);
                                        }elseif (isset($all[0]['name']) AND !isset($all[0]['car'])) {
                                          $a='';
                                              foreach ($arr_mycar as $key => $words_mycar) {
                                                $a .='name LIKE "%' . $words_mycar . '%" OR ';
                                              }
                                              $res_mycar=rtrim($a,' OR');
                                              $sql='UPDATE ' . $table . '
                                              SET my_car=IF(my_car="" , "' . $mycar['my_car'] . '" , CONCAT("' . $mycar['my_car'] . '",",",my_car))
                                              WHERE ' . $res_mycar;
                                              p($sql);
                                              update_mycar($sql);
                                        }elseif (isset($all[0]['car']) AND !isset($all[0]['name'])) {
                                          $a='';
                                              foreach ($arr_mycar as $key => $words_mycar) {
                                                $a .='car LIKE "%' . $words_mycar . '%" OR ';
                                              }
                                              $res_mycar=rtrim($a,' OR');
                                              $sql='UPDATE ' . $table . '
                                              SET my_car=IF(my_car="" , "' . $mycar['my_car'] . '" , CONCAT("' . $mycar['my_car'] . '",",",my_car))
                                              WHERE ' . $res_mycar;
                                              p($sql);
                                              update_mycar($sql);
                                        }


                                    }
                                    $sql='UPDATE ' . $my_table_magaz . ' SET check_1=1 WHERE table_name="' . $_GET['table'] . '"';
                                    update_mycar($sql);





                              }else {
                                echo "выберите таблицу для обработки";
                              }












    }elseif ($_GET['do']==2) {

                                if (isset($_GET['table']) AND $_GET['table']=="auto") {

                                    foreach ($all_magaz as $key => $magaz) {
                                      p($magaz['company']);
                                      $car="";
                              //сюда прописываем таблицу в которую все это пойдет
                                      $final_table="p_all";
                                        if ($magaz['check_2']==1) {
                                          echo "все уже сделано если хотите прогнать еще раз, выберите конкретную таблицу или поставьте check_2=0 в таблице магазинов";
                                        }else{


                                          $get_to_import=get_by_car($magaz['table_name'],$car);
                                          foreach ($get_to_import as $key => $r) {
                                             p($r);
                                            // update_before_check_to_car($_GET['table'])
                                            if (isset($r['name'])) {
                                            $rname=str_ireplace('"',' ',$r['name']);
                                            $rname2=str_ireplace(',',' ',$rname);
                                            $rname3=str_ireplace('  ',' ',$rname2);
                                            $rname3=str_ireplace('  ',' ',$rname3);
                                            $r['name']=$rname3;
                                            }
                                            if (isset($r['brand'])) {
                                              $rbrand=str_ireplace('"',' ',$r['brand']);
                                              $rbrand2=str_ireplace(',',' ',$rbrand);
                                              $rbrand2=str_ireplace('  ',' ',$rbrand2);
                                              $rbrand2=str_ireplace('  ',' ',$rbrand2);
                                              $r['brand']=$rbrand2;
                                            }
                                            if (isset($r['car'])) {
                                              $rcar=str_ireplace('"',' ',$r['car']);
                                              $rcar2=str_ireplace(',',' ',$rcar);
                                              $rcar2=str_ireplace('  ',' ',$rcar2);
                                              $rcar2=str_ireplace('  ',' ',$rcar2);
                                              $r['car']=$rcar2;
                                            }
                                            if (isset($r['number'])) {
                                              $rnum=str_ireplace('"','',$r['number']);
                                              $rnum2=str_ireplace('.','',$rnum);
                                              $rnum2=str_ireplace('-','',$rnum2);
                                              $rnum2=str_ireplace(' ','',$rnum2);
                                              $rnum2=str_ireplace('/','',$rnum2);
                                              p($rnum2);
                                              if ($r['seller']==1) {
                                                $rnum3=trim($rnum2,'	');
                                                $rnum4=trim($rnum3,' ');
                                                $r['number']=$rnum4;
                                              }else{
                                                $r['number']=$rnum2;
                                              }


                                              p($r['number']);
                                            }
                                            if (isset($r['price'])) {
                                              $rprice=preg_replace("/[.][0-9]*/",'',$r['price']);
                                              $rprice2=preg_replace("/[-][0-9]*/",'',$rprice);
                                              $rprice2=str_ireplace('рублей','',$rprice2);
                                              $rprice2=str_ireplace('rub','',$rprice2);
                                              $rprice2=str_ireplace('руб','',$rprice2);
                                              $rprice2=str_ireplace('р','',$rprice2);
                                              $rprice2=str_ireplace('r','',$rprice2);
                                              $rprice2=str_ireplace('/','',$rprice2);
                                              $rprice2=str_ireplace(' ','',$rprice2);
                                              $r['price']=$rprice2;
                                            }

                                            // p($r);
                                            $check=check_before_insert_to_car($r,$final_table);
                                            p($check);
                                            if(empty($check)){
                                              insert_to_car($r,$final_table);
                                            }

                                          }
                                          $sql='UPDATE ' . $my_table_magaz . ' SET check_2=1 WHERE id=' . $magaz['id'];
                                          update_mycar($sql);
                                        }
                                    }



                                }elseif (isset($_GET['table']) AND !empty($_GET['table'] AND $_GET['table']!=="auto") ) {
                                  echo "найти и вставить в общую таблицу";




                          //сюда прописываем модель что в my_model, для всех позиций вне зависимости от авто оставляем пустым
                                  $car="";
                          //сюда прописываем таблицу в которую все это пойдет
                                  $final_table="p_all";






                                  $get_to_import=get_by_car($_GET['table'],$car);
                                  foreach ($get_to_import as $key => $r) {
                                     p($r);
                                    // update_before_check_to_car($_GET['table'])
                                    if (isset($r['name'])) {
                                    $rname=str_ireplace('"',' ',$r['name']);
                                    $rname2=str_ireplace(',',' ',$rname);
                                    $rname3=str_ireplace('  ',' ',$rname2);
                                    $rname3=str_ireplace('  ',' ',$rname3);
                                    $r['name']=$rname3;
                                    }
                                    if (isset($r['brand'])) {
                                      $rbrand=str_ireplace('"',' ',$r['brand']);
                                      $rbrand2=str_ireplace(',',' ',$rbrand);
                                      $rbrand2=str_ireplace('  ',' ',$rbrand2);
                                      $rbrand2=str_ireplace('  ',' ',$rbrand2);
                                      $r['brand']=$rbrand2;
                                    }
                                    if (isset($r['car'])) {
                                      $rcar=str_ireplace('"',' ',$r['car']);
                                      $rcar2=str_ireplace(',',' ',$rcar);
                                      $rcar2=str_ireplace('  ',' ',$rcar2);
                                      $rcar2=str_ireplace('  ',' ',$rcar2);
                                      $r['car']=$rcar2;
                                    }
                                    if (isset($r['number'])) {
                                      $rnum=str_ireplace('"','',$r['number']);
                                      $rnum2=str_ireplace('.','',$rnum);
                                      $rnum2=str_ireplace('-','',$rnum2);
                                      $rnum2=str_ireplace(' ','',$rnum2);
                                      $rnum2=str_ireplace('/','',$rnum2);
                                      p($rnum2);
                                      if ($r['seller']==1) {
                                        $rnum3=trim($rnum2,'	');
                                        $rnum4=trim($rnum3,' ');
                                        $r['number']=$rnum4;
                                      }else{
                                        $r['number']=$rnum2;
                                      }


                                      p($r['number']);
                                    }
                                    if (isset($r['price'])) {
                                      $rprice=preg_replace("/[.][0-9]*/",'',$r['price']);
                                      $rprice2=preg_replace("/[-][0-9]*/",'',$rprice);
                                      $rprice2=str_ireplace('рублей','',$rprice2);
                                      $rprice2=str_ireplace('rub','',$rprice2);
                                      $rprice2=str_ireplace('руб','',$rprice2);
                                      $rprice2=str_ireplace('р','',$rprice2);
                                      $rprice2=str_ireplace('r','',$rprice2);
                                      $rprice2=str_ireplace('/','',$rprice2);
                                      $rprice2=str_ireplace(' ','',$rprice2);
                                      $r['price']=$rprice2;
                                    }

                                    // p($r);
                                    $check=check_before_insert_to_car($r,$final_table);
                                    p($check);
                                    if(empty($check)){
                                      insert_to_car($r,$final_table);
                                    }

                                  }



                                  $sql='UPDATE ' . $my_table_magaz . ' SET check_2=1 WHERE table_name="' . $_GET['table'] . '"';
                                  update_mycar($sql);










                                }else {
                                  echo "выберите таблицу для обработки";
                                }


    }elseif($_GET['do']==33){
                                                if (isset($_GET['table']) AND $_GET['table']=="auto") {
                                                  echo "вставить уникальные значения в таблицы уников";
                                                  $table_all="p_all";

                                                  foreach ($all_magaz as $key => $magaz) {
                                                    $seller_id=$magaz['seller'];
                                                    p($magaz['company']);
                                                      if ($magaz['check_33']==1) {
                                                        echo "уже все вставили, проставьте checked_33=0 если хотите прогнать еще раз";
                                                      }else{
                                                        foreach ($my_car_info as $key => $auto) {
                                                          //сначала берем список магазинов, из одного из них мы не добавляли номера то берем номера по нему и проверяем есть ли они в таблицах и добавляем есть есть.
                                                          //сначала вставить все уникальные номера для каждой машины
                                                          $uniqe_my_car_name=$auto['my_car'];
                                                          p($uniqe_my_car_name);
                                                                    $uniqe_table_car="p_uniq_" . $uniqe_my_car_name . "_magaz_angara";


                                                                    $sql='SELECT DISTINCT number FROM ' . $table_all . ' WHERE seller=' . $seller_id . ' AND my_car LIKE "%' . $auto['my_car'] . '%"';
                                                                    $uniqe_numbers_cars=select_sql($sql);
                                                                        foreach ($uniqe_numbers_cars as $key => $values) {
                                                                          $sql='SELECT number FROM ' . $uniqe_table_car . ' WHERE number="' . $values['number'] . '"';
                                                                          $check_havent=select_sql($sql);
                                                                          if (isset($check_havent[0]['number'])) {

                                                                          }else{
                                                                            if ($values['number']=="0" OR $values['number']=='') {

                                                                            }else{
                                                                            $sql='INSERT INTO ' . $uniqe_table_car . '(number) VALUES("' . $values['number'] . '")';
                                                                            insert_into($sql);
                                                                            p($sql);
                                                                          }
                                                                          }

                                                                        }


                                                        }
                                                      }
                                                      $sql='UPDATE ' . $my_table_magaz . ' SET check_33=1 WHERE id=' . $magaz['id'];
                                                      update_mycar($sql);

                                                  }







                                                }






    }elseif($_GET['do']==3){


                                    //тут вставляем кол-во предложений по номеру в магазинах, для этого берем id продавца и пробиваем в общей таблице сколько у него предложений с таким номером и записываем, делается по отдельности для каждого продавца
                                    if (isset($_GET['table']) AND $_GET['table']=="auto") {
                                      echo "вставить кол-во в магазинах АВТОМАТОМ ВО ВСЕ ТАБЛИЦЫ";
                                      $table_all="p_all";

                                                  foreach ($all_magaz as $key => $magaz) {
                                                    p($magaz['table_name']);
                                                    $seller_id=$magaz['seller'];
                                                    p($seller_id);
                                                    // p($key);
                                                    if ($key==0) {
                                                    $prev_seller_id="seller";
                                                    }else{
                                                    $prev_seller_id='sel' . $all_magaz[$key-1]['seller'];
                                                    }
                                                    p($prev_seller_id);

                                                      if ($magaz['check_3']==1) {
                                                        echo "уже все проставили, или поставьте check_3=0 для магазина который хотите прогнать";
                                                      }else {

                                                         foreach ($my_car_info as $key => $auto) {

                                                           //сначала вставить все уникальные номера для каждой машины
                                                           $uniqe_my_car_name=$auto['my_car'];
                                                           p($uniqe_my_car_name);
                                                                   $uniqe_table_car="p_uniq_" . $uniqe_my_car_name . "_magaz_angara";


                                                                  $sql='SELECT * FROM ' . $uniqe_table_car . ' LIMIT 2';
                                                                  p($sql);
                                                              $uniqe_number=select_sql($sql);

                                                              p($uniqe_number);
                                                           if (isset($uniqe_number[0]['sel' . $seller_id])) {

                                                           }else{
                                                             $sql_alter='ALTER TABLE `' . $uniqe_table_car . '` ADD `sel' . $seller_id . '` INT(11) NOT NULL DEFAULT "0" AFTER ' . $prev_seller_id;
                                                             p($sql_alter);
                                                             update_mycar($sql_alter);
                                                           }
                                                           $uniqe_number=get_uniqe_number($uniqe_table_car);
                                                           $count_tov=count($uniqe_number);
                                                           p($count_tov);
                                                             foreach ($uniqe_number as $key => $uni) {
                                                              $count_magaz=count(get_tovar_magaz($table_all,$seller_id,$uni['number']));
                                                              if ($uni['sel' . $seller_id]==$count_magaz) {

                                                              }else{
                                                              update_uniqe_magaz($uniqe_table_car,$seller_id,$count_magaz,$uni['id']);
                                                              }
                                                            }
                                                          if ($auto['count']==$count_tov) {
                                                            echo "товаров " . $auto['count'] . "/" . $count_tov;
                                                          }else{
                                                          $sql='UPDATE ' . $t_my_car . ' SET count=' . $count_tov . ' WHERE id=' . $auto['id'];
                                                          update_mycar($sql);
                                                          }
                                                        }
                                                      $sql='UPDATE ' . $my_table_magaz . ' SET check_3=1 WHERE id=' . $magaz['id'];
                                                      update_mycar($sql);


                                                    }
                                                  }






                                    }elseif (isset($_GET['table']) AND !empty($_GET['table']) AND $_GET['table']!=="auto" ) {
                                      echo "вставить кол-во в магазинах";
                                      $seller_id=get_one_tovar($_GET['table'])[0]['seller'];
                                      p($seller_id);
                                      foreach ($my_car_info as $key => $auto) {

                                        //сначала вставить все уникальные номера для каждой машины
                                        $uniqe_my_car_name=$auto['my_car'];
                                        p($uniqe_my_car_name);
                                                $uniqe_table_car="p_uniq_" . $uniqe_my_car_name . "_magaz_angara";



                                      $uniqe_number=get_uniqe_number($uniqe_table_car);
                                      $count_tov=count($uniqe_number);
                                      p($count_tov);
                                      foreach ($uniqe_number as $key => $uni) {
                                        $count_magaz=count(get_tovar_magaz($table_all,$seller_id,$uni['number']));

                                        update_uniqe_magaz($uniqe_table_car,$seller_id,$count_magaz,$uni['id']);
                                      }
                                      }
                                      $sql='UPDATE ' . $my_table_magaz . ' SET check_2=1 WHERE table_name="' . $_GET['table'] . '"';
                                      update_mycar($sql);


                                    }else {
                                      echo "выберите таблицу для обработки";
                                    }










}elseif($_GET['do']==4){
//названия
                                    if (isset($_GET['table']) AND $_GET['table']=="auto") {

                                      echo "вставить названия из магазинов АВТОМАТОМ";
                                      //порядок присваивания названий
                                      $arr="5,3,4,2,1,6";
                                      $ex=explode(',',$arr);
                                      $table_all="p_all";
                                      foreach ($ex as $key => $value) {

                                        $seller_id=$value;
                                        p($seller_id);

                                        foreach ($my_car_info as $key => $auto) {

                                          //сначала вставить все уникальные номера для каждой машины
                                          $uniqe_my_car_name=$auto['my_car'];
                                          p($uniqe_my_car_name);
                                          $uniqe_table_car="p_uniq_" . $uniqe_my_car_name . "_magaz_angara";




                                        $uniqe_number=get_uniqe_number($uniqe_table_car);

                                        foreach ($uniqe_number as $key => $uni) {

                                          $name_magaz=get_tovar_magaz($table_all,$seller_id,$uni['number']);
                                          if (isset($name_magaz[0]['name'])) {
                                            $name=$name_magaz[0]['name'];
                                            if ($name==$uni['name']) {

                                            }else{
                                            p($name);
                                            update_uniqe_name_magaz($uniqe_table_car,$name,$uni['id']);
                                            }
                                          }else{

                                          }


                                        }
                                      }
                                      $sql='UPDATE ' . $my_table_magaz . ' SET check_4=1 WHERE seller=' . $seller_id;
                                      update_mycar($sql);
                                    }



                                  }elseif (isset($_GET['table']) AND !empty($_GET['table'] AND $_GET['table']!=="auto") ) {
                                  echo "вставить названия из магазинов";
                                  $seller_id=get_one_tovar($_GET['table'])[0]['seller'];
                                  p($seller_id);

                                  foreach ($my_car_info as $key => $auto) {

                                    //сначала вставить все уникальные номера для каждой машины
                                    $uniqe_my_car_name=$auto['my_car'];
                                    p($uniqe_my_car_name);
                                    $uniqe_table_car="p_uniq_" . $uniqe_my_car_name . "_magaz_angara";




                                  $uniqe_number=get_uniqe_number($uniqe_table_car);

                                  foreach ($uniqe_number as $key => $uni) {

                                    $name_magaz=get_tovar_magaz($table_all,$seller_id,$uni['number']);
                                    if (isset($name_magaz[0]['name'])) {
                                      $name=$name_magaz[0]['name'];
                                      p($name);
                                      update_uniqe_name_magaz($uniqe_table_car,$name,$uni['id']);
                                    }else{

                                    }


                                  }
                                }

                                $sql='UPDATE ' . $my_table_magaz . ' SET check_4=1 WHERE seller=' . $seller_id;
                                update_mycar($sql);


                                }else {
                                  echo "выберите таблицу для обработки";
                                }




}elseif($_GET['do']==0){


                                if (isset($_GET['table']) AND !empty($_GET['table']) ) {
                                echo "вся таблица:";

                                  $all=get_all_from_table($_GET['table']);
                                  foreach ($all as $key => $row) {
                                    p($row);
                                  }
                                }else {
                                echo "выберите таблицу для обработки";
                                }


}else {
echo "выберите что делать с таблицей";
}






//работа с брендами
}elseif(isset($_GET['brands_do'])){


    if($_GET['brands_do']==0){
      echo "сейчас начнем делать бренды, только выберите что делать";
    }elseif ($_GET['brands_do']==1) {
      $table_get="p_all";
      // $_GET['table'];






                                      if ($_GET['table']=="auto") {
                                              echo "вставить в бренды все возможные бренды из " . $table_get;

                                              foreach ($my_car_info as $key => $auto) {


                                                  //сначала вставить все уникальные номера для каждой машины
                                                  $uniqe_my_car_name=$auto['my_car'];
                                                  p($uniqe_my_car_name);
                                                  $table_uniqe="p_uniq_" . $uniqe_my_car_name . "_magaz_angara";
                                                //взять таблицу уников взять уник
                                                $uniqe_number=get_uniqe_number($table_uniqe);
                                                  foreach ($uniqe_number as $key => $uniq) {
                                                    echo "<hr>";
                                                      p($uniq);
                                                      $brands=select_brands($table_get,$uniq['number']);




                                                        // p($brands);
                                                      $a='';

                                                        foreach ($brands as $key => $brand) {
                                                          if (isset($brand['brand']) AND !empty($brand['brand'])) {
                                                            $rbrand=str_ireplace('"',' ',$brand['brand']);
                                                            $rbrand2=str_ireplace(',',' ',$rbrand);
                                                            $rbrand2=str_ireplace('  ',' ',$rbrand2);
                                                            $rbrand2=str_ireplace('  ',' ',$rbrand2);
                                                            p($rbrand2);
                                                            $sql='SELECT DISTINCT brand FROM s_brands_final WHERE brand_name_2 LIKE "' . $rbrand2 . '"';
                                                            $selected=select_sql($sql);
                                                            p($selected);

                                                              if (isset($selected[0]['brand']) AND !empty($selected[0]['brand'])) {
                                                                foreach ($selected as $key => $value) {
                                                                  $brandd=$value['brand'];
                                                                  if (isset($brandd) AND !empty($brandd)) {
                                                                    $a .=$brandd . ',';
                                                                    echo "есть в словаре";
                                                                  }elseif(isset($rbrand2) AND !empty($rbrand2)){
                                                                    $a .=$rbrand2 . ',';
                                                                    echo "нет в словаре";
                                                                  }else{
                                                                      echo "нет бренда";
                                                                  }
                                                                }
                                                              }else {
                                                                echo "нет в словаре";
                                                                $sql='INSERT INTO s_word_lose(brand) VALUE("' . $rbrand2 . '")';
                                                                update_sql($sql);
                                                              }
                                                          }else{

                                                          }
                                                        }
                                                      $res=trim($a,',');
                                                      p($res);
                                                        if (empty($res)) {
                                                        $count_brands=0;
                                                        $res2='';

                                                        }else{
                                                          $arr_res=explode(',',$res);
                                                          $arr_uni=array_unique($arr_res);

                                                          $count_brands=count($arr_uni);
                                                          $ar='';
                                                          foreach ($arr_uni as $key => $branduni) {
                                                            $ar .=$branduni . ',';
                                                          }
                                                          $res2=trim($ar,',');

                                                        }
                                                        p($res2);

                                                          if($res2==$uniq['brand'] OR $count_brands>100){
                                                            echo "такое уже делали";
                                                          }else{
                                                            echo "res2=" . $res2;
                                                            p($count_brands);
                                                            update_brands_pole($table_uniqe,$uniq['id'],$count_brands,$res2);
                                                          }


                                                  }

                                            }

                                      }else{
                                        echo "тут проставляем бренды из того что спарсили из поля бренд в таблицу кников в поле `brand` через запятую и их кол-во выберите таблицу auto чтоб запустить";
                                      }




    }elseif ($_GET['brands_do']==2) {

                                            $table_procenka1="ang_prices_all_no_beginning";
                                            $table_procenka2="ang_prices_all";
                                            $table_procenka3="ang_prices_all2";
                                            $table_procenka=$table_procenka1;
                                            $pole_brands="brands_procenka";
                                            $pole_count="count_brands_procenka";

                                            if ($_GET['table']=="auto") {

                                              foreach ($my_car_info as $key => $auto) {

                                                      //сначала вставить все уникальные номера для каждой машины
                                                      $uniqe_my_car_name=$auto['my_car'];
                                                      p($uniqe_my_car_name);
                                                      $table_uniqe="p_uniq_" . $uniqe_my_car_name . "_magaz_angara";


                                                  echo "вставить в бренды все возможные бренды из проценки";
                                                  $uniqe_number=get_uniqe_number($table_uniqe);
                                                  foreach ($uniqe_number as $key => $uniq) {
                                                    p($uniq);
                                                    if ($uniq['number']=='' OR $uniq['number']=='0' OR $uniq['number']=='00' OR $uniq['number']=='1' OR $uniq['number']=='2' OR $uniq['number']=='3' OR $uniq['number']=='4' OR $uniq['number']=='5') {
                                                      echo "кривая позиция";
                                                    }else{
                                                          $brands_procenka=select_brands_procenka($table_procenka,$uniq['number']);
                                                          $a='';
                                                          foreach ($brands_procenka as $key => $brand) {
                                                            if (isset($brand['brand']) AND !empty($brand['brand'])) {
                                                              $rbrand=str_ireplace('"',' ',$brand['brand']);
                                                              $rbrand2=str_ireplace(',',' ',$rbrand);
                                                              $rbrand2=str_ireplace('  ',' ',$rbrand2);
                                                              $rbrand2=str_ireplace('  ',' ',$rbrand2);

                                                              $sql='SELECT DISTINCT brand FROM s_brands_final WHERE brand_name_2 LIKE "' . $rbrand2 . '"';
                                                              $selected=select_sql($sql);
                                                              foreach ($selected as $key => $value) {
                                                                $brandd=$value['brand'];
                                                                if (empty($brandd)) {
                                                                $a .=$rbrand2 . ',';
                                                                }else{
                                                                $a .=$brandd . ',';
                                                                }
                                                              }
                                                            }else{
                                                            }
                                                          }
                                                          $res=trim($a,',');
                                                          p($res);
                                                        if (empty($res)) {
                                                          $count_brands=0;
                                                        }else{
                                                          $arr_res=explode(',',$res);
                                                          $arr_uni=array_unique($arr_res);

                                                          $count_brands=count($arr_uni);
                                                          $ar='';
                                                          foreach ($arr_uni as $key => $branduni) {
                                                            $ar .=$branduni . ',';
                                                          }
                                                          $res2=trim($ar,',');
                                                          p($res2);

                                                        }


                                                            if (isset($res2) AND !empty($res2)) {
                                                              if($res2==$uniq['brands_zzap'] OR $count_brands>100){
                                                                echo "такое уже делали";
                                                              }else{
                                                                echo "res2";
                                                                p($count_brands);
                                                                update_brands_pole_procenka($table_uniqe,$uniq['id'],$count_brands,$res2,$pole_brands,$pole_count);
                                                              }
                                                            }else{
                                                              if($res==$uniq['brands_zzap'] OR $count_brands>100){
                                                                echo "такое уже делали";
                                                              }else{
                                                                echo "res1";
                                                                p($count_brands);
                                                                update_brands_pole_procenka($table_uniqe,$uniq['id'],$count_brands,$res2,$pole_brands,$pole_count);
                                                              }
                                                            }
                                                    }


                                                  }
                                              }
                                            }else{
                                                echo "тут проставляем бренды из того что в проценке из поля бренд в таблицу уников в поле `brands_procenka` через запятую и их кол-во выберите таблицу auto чтоб запустить";
                                            }



    }elseif ($_GET['brands_do']==3) {

                                            $table_procenka1="ang_prices_all_no_beginning";
                                            $table_procenka2="ang_prices_all";
                                            $table_procenka3="ang_prices_all2";
                                            $table_procenka=$table_procenka2;
                                            $pole_brands="brands_procenka_all";
                                            $pole_count="count_brands_procenka_all";

                                            if ($_GET['table']=="auto") {

                                                    foreach ($my_car_info as $key => $auto) {

                                                            //сначала вставить все уникальные номера для каждой машины
                                                            $uniqe_my_car_name=$auto['my_car'];
                                                            p($uniqe_my_car_name);
                                                            $table_uniqe="p_uniq_" . $uniqe_my_car_name . "_magaz_angara";


                                                    echo "вставить в бренды все возможные бренды из проценки";
                                                    $uniqe_number=get_uniqe_number($table_uniqe);
                                                    foreach ($uniqe_number as $key => $uniq) {
                                                      p($uniq);
                                                      if ($uniq['number']=='' OR $uniq['number']=='0' OR $uniq['number']=='00' OR $uniq['number']=='1' OR $uniq['number']=='2' OR $uniq['number']=='3' OR $uniq['number']=='4' OR $uniq['number']=='5') {
                                                        echo "кривая позиция";
                                                      }else{

                                                        $brands_procenka=select_brands_procenka($table_procenka,$uniq['number']);
                                                        $a='';
                                                        foreach ($brands_procenka as $key => $brand) {
                                                          if (isset($brand['brand']) AND !empty($brand['brand'])) {
                                                            $rbrand=str_ireplace('"',' ',$brand['brand']);
                                                            $rbrand2=str_ireplace(',',' ',$rbrand);
                                                            $rbrand2=str_ireplace('  ',' ',$rbrand2);
                                                            $rbrand2=str_ireplace('  ',' ',$rbrand2);
                                                            $sql='SELECT DISTINCT brand FROM s_brands_final WHERE brand_name_2 LIKE "' . $rbrand2 . '"';
                                                            $selected=select_sql($sql);
                                                            foreach ($selected as $key => $value) {
                                                              $brandd=$value['brand'];
                                                              if (empty($brandd)) {
                                                              $a .=$rbrand2 . ',';
                                                              }else{
                                                              $a .=$brandd . ',';
                                                              }
                                                            }
                                                          }else{
                                                          }
                                                        }
                                                        $res=trim($a,',');
                                                        p($res);
                                                        if (empty($res)) {
                                                        $count_brands=0;
                                                      }else{
                                                        $arr_res=explode(',',$res);
                                                        $arr_uni=array_unique($arr_res);

                                                        $count_brands=count($arr_uni);
                                                        $ar='';
                                                        foreach ($arr_uni as $key => $branduni) {
                                                          $ar .=$branduni . ',';
                                                        }
                                                        $res2=trim($ar,',');
                                                        p($res2);
                                                      }





                                                      if (isset($res2) AND !empty($res2)) {
                                                        if($res2==$uniq['brands_zzap'] OR $count_brands>100){
                                                          echo "такое уже делали";
                                                        }else{
                                                          echo "res2";
                                                          p($count_brands);
                                                          update_brands_pole_procenka($table_uniqe,$uniq['id'],$count_brands,$res2,$pole_brands,$pole_count);
                                                        }
                                                      }else{
                                                        if($res==$uniq['brands_zzap'] OR $count_brands>100){
                                                          echo "такое уже делали";
                                                        }else{
                                                          echo "res1";
                                                          p($count_brands);
                                                          update_brands_pole_procenka($table_uniqe,$uniq['id'],$count_brands,$res2,$pole_brands,$pole_count);
                                                        }
                                                      }


                                                      }




                                                    }

                                                  }
                                            }else{
                                              echo "тут проставляем бренды из того что в проценке из поля бренд в таблицу уников в поле `brands_procenka_all` через запятую и их кол-во выберите таблицу auto чтоб запустить";
                                            }



    }elseif ($_GET['brands_do']==4) {

                                              $table_to_zzap="1_to_zzap";
                                              $table_zzap="1_zzap_out";
                                              $pole_brands="brands_zzap";
                                              $pole_count="count_brands_zzap";
                                              $stop_brand1="KIA";
                                              $stop_brand2="HYUNDAI";

                                              if ($_GET['table']=="auto") {

                                                            foreach ($my_car_info as $key => $auto) {

                                                                    //сначала вставить все уникальные номера для каждой машины
                                                                    $uniqe_my_car_name=$auto['my_car'];
                                                                    p($uniqe_my_car_name);
                                                                    $table_uniqe="p_uniq_" . $uniqe_my_car_name . "_magaz_angara";


                                                      echo "вставить в бренды все возможные бренды из zzap";
                                                      $uniqe_number=get_uniqe_number_zzap($table_to_zzap);
                                                      foreach ($uniqe_number as $key => $uniq) {
                                                        p($uniq);
                                                        if ($uniq['number']=='' OR $uniq['number']=='0' OR $uniq['number']=='00' OR $uniq['number']=='1' OR $uniq['number']=='2' OR $uniq['number']=='3' OR $uniq['number']=='4' OR $uniq['number']=='5') {
                                                          echo "кривая позиция";
                                                        }else{
                                                            $brands_procenka=select_brands_zzap($table_zzap,$uniq['number'],$stop_brand2,$stop_brand1);
                                                            $a='';
                                                            foreach ($brands_procenka as $key => $brand) {
                                                              if (isset($brand['brand']) AND !empty($brand['brand']) AND $brand['brand']!=="НЕИЗВЕСТНЫЙ") {
                                                                $rbrand=str_ireplace('"',' ',$brand['brand']);
                                                                $rbrand2=str_ireplace(',',' ',$rbrand);
                                                                $rbrand2=str_ireplace('  ',' ',$rbrand2);
                                                                $rbrand2=str_ireplace('  ',' ',$rbrand2);
                                                                $sql='SELECT DISTINCT brand FROM s_brands_final WHERE brand_name_2 LIKE "' . $rbrand2 . '"';
                                                                $selected=select_sql($sql);
                                                                foreach ($selected as $key => $value) {
                                                                  $brandd=$value['brand'];
                                                                  if (empty($brandd)) {
                                                                  $a .=$rbrand2 . ',';
                                                                  }else{
                                                                  $a .=$brandd . ',';
                                                                  }
                                                                }
                                                              }else{
                                                              }
                                                            }

                                                            $res=trim($a,',');
                                                            p($res);
                                                          if (empty($res)) {
                                                            $count_brands=0;
                                                          }else{
                                                            $arr_res=explode(',',$res);
                                                            $arr_uni=array_unique($arr_res);

                                                            $count_brands=count($arr_uni);
                                                            $ar='';
                                                            foreach ($arr_uni as $key => $branduni) {
                                                              $ar .=$branduni . ',';
                                                            }
                                                            $res2=trim($ar,',');
                                                            p($res2);
                                                          }
                                                            if (isset($res2) AND !empty($res2)) {
                                                              if($res2==$uniq['brands_zzap'] OR $count_brands>100){
                                                                echo "такое уже делали";
                                                              }else{
                                                                echo "res2";
                                                                p($count_brands);
                                                                update_brands_pole_zzap($table_uniqe,$uniq['number'],$count_brands,$res2,$pole_brands,$pole_count);
                                                              }
                                                            }else{
                                                              if($res==$uniq['brands_zzap'] OR $count_brands>100){
                                                                echo "такое уже делали";
                                                              }else{
                                                                echo "res1";
                                                                p($count_brands);
                                                                update_brands_pole_zzap($table_uniqe,$uniq['number'],$count_brands,$res,$pole_brands,$pole_count);
                                                              }
                                                            }

                                                          }
                                                      }
                                              }
                                              }else{
                                                echo "тут проставляем бренды из того что в `" . $table_zzap . "` из поля бренд в таблицу уников в поле `" . $pole_brands . "` через запятую и их кол-во, выберите таблицу `auto` чтоб запустить";
                                              }


    }else {
      echo "выберите что делать с брендами";
    }










}elseif (isset($_GET['s_brands'])) {

      if ($_GET['s_brands']==1) {



                      //делаем словарь брендов из ззапа с уже сделаными униками
                      echo "делаем словарь брендов из ззапа с уже сделаными униками";
                      $table_uniq="s_brands";
                      $table_s_zzap="s_brands_zzap";
                      $table_result="s_brands_php";

                      //получаем массив уникальных брендов
                      $sql='SELECT * FROM ' . $table_uniq;
                      $brands_uniq=select_sql_db3($sql);
                        foreach ($brands_uniq as $key => $uniq_b) {
                          p($uniq_b);
                          //находим на каждый бренд вариации с ззап
                          $sql='SELECT DISTINCT class_man FROM ' . $table_s_zzap . ' WHERE real_brand="' . $uniq_b['brand'] . '" AND (type_search="10" OR type_search="13")';
                          $brands_zzap=select_sql_db3($sql);
                          p($brands_zzap);
                          $a='';
                          foreach ($brands_zzap as $key => $b_z) {
                            $brand_2_zz=str_ireplace(',',' ',$b_z['class_man']);
                            $a .=$brand_2_zz . ',';
                          }
                          $res=rtrim($a,',');
                          $sql_check='SELECT DISTINCT * FROM ' . $table_result . ' WHERE brand="' . $uniq_b['brand'] . '" AND brand_name_2="' . $res . '"';
                          $check=select_sql_db3($sql_check);
                          if (isset($check[0]['brand'])) {
                            echo "already exists";
                          }else{
                            $sql_ins='INSERT INTO ' . $table_result . '(brand,brand_name_2) VALUES("' . $uniq_b['brand'] . '","' . $res . '")';
                            p($sql_ins);
                            insert_sql_db3($sql_ins);
                          }

                        }





      }elseif ($_GET['s_brands']==2) {
        $table_result="s_brands_final";
        $table_take="s_brands_info";
        $sql='SELECT * from ' . $table_take;
        $taked=select_sql_db3($sql);
          foreach ($taked as $key => $value) {
            $brand=$value['brand'];
            $brands=$value['brand_done'];
              $brand2_arr=explode(',',$brands);
              foreach ($brand2_arr as $key => $arr_brand) {
                p($arr_brand);
                $sql_2='INSERT INTO ' . $table_result . '(brand,brand_name_2) VALUES("' . $brand . '","' . $arr_brand . '")';
                p($sql_2);
                insert_sql_db3($sql_2);


              }
          }

      }elseif ($_GET['s_brands']==3) {
        $table_result="p_all_brands";
        $table_s="s_brands_final";
        $pole_result="real_brand";
        $sql='SELECT * from ' . $table_result;
        $taked=select_sql($sql);
          foreach ($taked as $key => $value) {
            $brand=$value['brand'];
            $id=$value['id'];
            p($brand);
            $sql2='SELECT DISTINCT brand FROM ' . $table_s . ' WHERE brand_name_2 LIKE "' . $brand . '"';
            $get_real_brand=select_sql_db3($sql2);
              foreach ($get_real_brand as $key => $real_brand) {
                p($real_brand);
                $sql_2='UPDATE ' . $table_result . ' SET ' . $pole_result . '="' . $real_brand['brand'] . '" WHERE id=' . $id;
                p($sql_2);
                update_sql($sql_2);


              }
          }

      }else{}





}elseif (isset($_GET['nomenklatura'])){
  if ($_GET['nomenklatura']==1) {

          $db2='product';
          $sql_db='SHOW TABLES FROM `' . $db2 . '`';
          $db_select=select_sql($sql_db);
           // p($db_select);
          $sql_my_car='SELECT * FROM my_car';
          $select_all_car=select_sql($sql_my_car);


                foreach ($select_all_car as $key => $cars) {

                $car=$cars['my_car'];
                $table_result='n_' . $car;


                $table_input='p_uniq_' . $car . '_magaz_angara';
                $c=0;
                foreach ($db_select as $key => $tables_db) {
                  if ($table_result==$tables_db['Tables_in_product']) {
                    $c++;
                  }else{

                  }
                }
                p($c);
                if ($c>=1) {
                  echo "таблица " . $table_result . " уже есть в " . $db2 . "<br>";
                }else{
                  echo "создал " . $table_result . ' в ' . $db2 . "<br>";
                  $sql='CREATE TABLE ' . $table_result . ' (number VARCHAR(255), brand VARCHAR(255), name VARCHAR(500));';
                  update_sql($sql);
                  $sql2='ALTER TABLE `' . $table_result . '` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`);';
                  update_sql($sql2);
                }
                  $sql_t='TRUNCATE TABLE `' . $table_result . '`';
                  update_sql($sql_t);
                    $sql='SELECT * FROM ' . $table_input;
                    $select=select_sql($sql);
                      foreach ($select as $key => $value) {
                          p($value);
                          $number=$value['number'];
                          $name=$value['name'];

                          $brands=explode(',',$value['brand']);
                          if (empty($value['brand'])) {
                            $brand='';
                            // SELECT
                            $sql='INSERT INTO ' . $table_result . '(`number`,`brand`,`name`) VALUES("' . $number . '","' . $brand . '","' . $name . '")';
                            p($sql);
                            update_sql($sql);
                          }else{
                            foreach ($brands as $key => $brand) {
                              $sql='INSERT INTO ' . $table_result . '(`number`,`brand`,`name`) VALUES("' . $number . '","' . $brand . '","' . $name . '")';
                              p($sql);
                              update_sql($sql);
                            }
                          }

                      }
                      $sql='SHOW TABLES FROM `' . $db2 . '`';
                      $sql='CREATE TABLE ' . $table_result . ' (Id INT, number VARCHAR(255), brand VARCHAR(255), name VARCHAR(500));';
                    }
    }else{}





}else {
  echo "выберите тип обработки";
}




?>

</div>



<style>
.container{
  width: 80%;
  margin-left: auto;
  margin-right: auto;
  display: flex;
  align-items: stretch;

}
.col-md-4{
  width: calc(25% - 10px);
  min-width: 200px;
  margin: 0px 5px;
}
</style>
