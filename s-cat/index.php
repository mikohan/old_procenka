<?php
require_once '../config.php';
error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set('max_execution_time', 0);
require_once ANG_ROOT . "functions.php";
include ANG_ROOT . "s-cat/lib/func.php";

$t_my_car="my_car";
$parsing_info=get_parsing_info();
$my_car_info=get_my_car_info($t_my_car);
$my_table_magaz="parsing";
$all_magaz=get_all_from_table($my_table_magaz);
?>
<?php if (isset($_GET['do'])) {

}else{?>
<div class="container" style="border:1px solid #aa4322;padding:10px;">


            <div class="col-md-4">
            работа со словарем
            <form action="" method="get" style="border:1px solid #aa4322;padding:10px;">
              <select name="do">
                <option value="0"></option>
              <option value="1">показать номенклатуру</option>
              <option value="2">2</option>
              <option value="33">33</option>
              <option value="3">3</option>
              <option value="4">4</option>
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
              <option value="1"></option>
              <option value="2"></option>
              <option value="3"></option>
              <option value="4"></option>
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
<?php } ?>
<div class="flex_column" style="padding:10px">
  <?php

  $table_dict='dict_kernel';
  $table_car_kernel='kernel_porter';
  if (isset($_GET['do'])) {



   if ($_GET['do']==1) {
    $sql='SELECT DISTINCT category_id,category_name FROM ' . $table_dict;


    $categoryes=select_sql_db4($sql);
    foreach ($categoryes as $key => $category) {?>
      <div class=" flex_row category" style="width:calc(100%)">


        <div class="bord_grey " style="width:33%;">
          <h2><?=$category['category_name']?></h2>

                <ul >
                  <?php
                      $sql5='SELECT DISTINCT ang_name FROM ' . $table_car_kernel . ' WHERE category_id LIKE "' . $category['category_id'] . '" AND subcat_id LIKE "%void%"';
                      $cat_keys=select_sql_db4($sql5);
                      foreach ($cat_keys as $key => $cat_key) { ?>
                        <li><?=$cat_key['ang_name']?></li>

                      <?php }
                  ?>
                  </ul>
        </div>


        <div class="bord_grey flex_column" style="width:calc(66%)">
        <?php
          $sql2='SELECT DISTINCT subcat_id,subcat_name FROM ' . $table_dict . ' WHERE category_id=' . $category['category_id'];
          $subcats=select_sql_db4($sql2);

          foreach ($subcats as $key => $subcat) {?>
            <div class="flex_row subcat" style="width:calc(100%)">
              <div class="bord_grey" style="width: calc(50%)">
                <h2><?=$subcat['subcat_name']?></h2>
                <ul >
                  <?php
                  if ($subcat['subcat_id']==0 OR empty($subcat['subcat_id'])) {

                  }else{
                      $sql6='SELECT DISTINCT ang_name FROM ' . $table_car_kernel . ' WHERE subcat_id LIKE "%,' . $subcat['subcat_id'] . '%" AND group_id LIKE "%,void%"';
                      $subcat_keys=select_sql_db4($sql6);
                      foreach ($subcat_keys as $key => $subcat_key) { ?>
                        <li><?=$subcat_key['ang_name']?></li>

                      <?php   }
                    }
                  ?>
                </ul>
              </div>


              <div class="bord_grey flex_column" style="width:calc(50%)">
              <?php
                $sql3='SELECT DISTINCT group_id,group_name FROM ' . $table_dict . ' WHERE subcat_id="' . $subcat['subcat_id'] . '"';
                $groups=select_sql_db4($sql3);

                foreach ($groups as $key => $group) {?>
                    <div class="flex_row" style="width:calc(100%)">
                    <div class="bord_grey group" style="width:100%;">
                      <h2><?=$group['group_name']?></h2>
                      <ul >
                        <?php
                        if ($group['group_id']==0 OR empty($group['group_id'])) {

                        }else{
                            $sql7='SELECT DISTINCT ang_name FROM ' . $table_car_kernel . ' WHERE group_id LIKE "%,' . $group['group_id'] . '%"';
                            $group_keys=select_sql_db4($sql7);
                            foreach ($group_keys as $key => $group_key) { ?>
                              <li><?=$group_key['ang_name']?></li>

                            <?php   }
                          }
                        ?>
                      </ul>
                    </div>

                        <!-- <div class="bord_grey flex_column" style="width:calc(80% - 10px)">
                        <?php
                        if (!empty($group['group_id']) AND $group['group_id']!=''  AND $group['group_id']!='void') {

                          $sql4='SELECT DISTINCT id,ang_name FROM ' . $table_car_kernel . ' WHERE group_id LIKE "%,' . $group['group_id'] . '%"';
                          $keywords=select_sql_db4($sql4);

                          foreach ($keywords as $key => $keyword) {?>
                              <div class="bord_grey" style="width:30%">
                                <h2><?=$keyword['ang_name']?></h2>
                              </div>

                          <?php }

                        }
                        ?>
                        </div> -->
                        </div>
                <?php }
              ?>

              </div>


            </div>
          <?php }
        ?>

      </div>


      </div>
    <?php }











  }elseif ($_GET['do']==2) {
  ?>

             <div class="flex_column" style="width:calc(100%- 10px)">
             <?php
               $sql3='SELECT DISTINCT group_id,group_name FROM ' . $table_dict ;
               $groups=select_sql_db4($sql3);

               foreach ($groups as $key => $group) {?>
                 <?php
                 if ($group['group_id']==0 OR empty($group['group_id'])) {

                 }else{
                     $sql7='SELECT DISTINCT ang_name,frequency FROM ' . $table_car_kernel . ' WHERE group_id LIKE "%,' . $group['group_id'] . '%"';
                     $group_keys=select_sql_db4($sql7);
                     $c='';
                     foreach ($group_keys as $key => $group_key) {
                       $c .=$group_key['frequency'] . ',';
                     }
                       $res=trim($c,',');
                       $arr_c=explode(',',$c);
                       $as=array_sum($arr_c);
                       $max_c=max($arr_c);

                     }

                     if (isset($group_keys[0]['ang_name']) AND !empty($group_keys[0]['ang_name'])) {


                       $sql10='SELECT DISTINCT subcat_id,subcat_name FROM ' . $table_dict . ' WHERE group_id=' . $group['group_id'];
                       $group_subcat=select_sql_db4($sql10);




                     ?>

                   <div class="flex_row category" style="width:calc(100%)">
                   <div class="bord_grey " style="width:20%;">

                      <?php foreach ($group_subcat as $key => $subcat) {
                        $sql11='SELECT DISTINCT category_id,category_name FROM ' . $table_dict . ' WHERE subcat_id=' . $subcat['subcat_id'];
                        $subcat_category=select_sql_db4($sql11);
                        foreach ($subcat_category as $key => $category) {
                          // code...

                        ?>
                          <p style="font-size:12px;">[<?=$category['category_id']?>] <?=$category['category_name']?> → [<?=$subcat['subcat_id']?>] <?=$subcat['subcat_name']?>→</p>
                    <?php   }
                          }
                          ?>
                    <h2>[<?=$group['group_id']?>] <?=$group['group_name']?></h2>
                    <p>общая сумма по ключам:<b style="font-size:14px;color:black"> <?=$as?> [<?=$max_c?>]</b></p>
                   </div>
                   <div class="bord_grey subcat" style="width:80%;">

                     <ul style="display:flex;flex-flow:wrap column;max-height:550px;align-items:flex-start">
                       <?php
                       if ($group['group_id']==0 OR empty($group['group_id'])) {

                       }else{

                           $sql7='SELECT DISTINCT ang_name,frequency FROM ' . $table_car_kernel . ' WHERE group_id LIKE "%,' . $group['group_id'] . '%"';
                           $group_keys=select_sql_db4($sql7);
                           foreach ($group_keys as $key => $group_key) {

                             ?>
                             <li style=""><?=$group_key['ang_name']?> <b style="font-size:12px;color:black">[<?=$group_key['frequency']?>]</b></li>

                           <?php   }
                         }
                       ?>
                     </ul>
                   </div>

                       <!-- <div class="bord_grey flex_column" style="width:calc(80% - 10px)">
                       <?php
                       if (!empty($group['group_id']) AND $group['group_id']!=''  AND $group['group_id']!='void') {

                         $sql4='SELECT DISTINCT id,ang_name FROM ' . $table_car_kernel . ' WHERE group_id LIKE "%,' . $group['group_id'] . '%"';
                         $keywords=select_sql_db4($sql4);

                         foreach ($keywords as $key => $keyword) {?>
                             <div class="bord_grey" style="width:30%">
                               <h2><?=$keyword['ang_name']?></h2>
                             </div>

                         <?php }

                       }
                       ?>
                       </div> -->
                       </div>

               <?php


             }
            }
             ?>

             </div>


   <?php }elseif ($_GET['do']==33) {

                   $table_dict='dict_kernel_amo_porter';
                   $sql3='SELECT DISTINCT group_id,group_name FROM ' . $table_dict . ' WHERE group_id!=""';
                   $groups=select_sql_db4($sql3);
                   foreach ($groups as $key => $group) {
                     p($group);


                     $sql2='SELECT * FROM cat_amo_stage WHERE group_id LIKE "%,' . $group['group_id'] . '%" AND car_id=1';
                     $sdelki=select_sql_db4($sql2);
                           $summ='';
                           foreach ($sdelki as $key => $value) {
                             $summ .=$value['cost'] . ',';
                           }
                           $res_s_ar=array_sum(explode(',',trim($summ,',')));
                           $c=count($sdelki);
                           p($c);
                           if ($c==0) {
                             $sr_ar=0;
                           }else{
                           $sr_ar=$res_s_ar/$c;
                           }
                           $sql_update='UPDATE ' . $table_dict . ' SET sdelki=' . $c . ',sr_cost=' . $sr_ar . ' WHERE group_id=' . $group['group_id'];
                     update_sql_db4($sql_update);

                     $sql2='SELECT * FROM cat_amo_stage WHERE group_id LIKE "%,' . $group['group_id'] . '%" AND car_id=1 AND stage_id=1';
                     $sdelki=select_sql_db4($sql2);
                           $summ='';
                           foreach ($sdelki as $key => $value) {
                             $summ .=$value['cost'] . ',';
                           }
                           $res_s_ar=array_sum(explode(',',trim($summ,',')));
                           $c_good=count($sdelki);
                           p($c_good);
                           if ($c_good==0) {
                             $sr_ar=0;
                           }else{
                             $sr_ar=$res_s_ar/$c_good;
                           }
                           $sql_update='UPDATE ' . $table_dict . ' SET good=' . $c_good . ',sr_cost_good=' . $sr_ar . ' WHERE group_id=' . $group['group_id'];
                     update_sql_db4($sql_update);

                           if ($c_good!=0) {
                             $conversion=$c_good/$c*100;
                           }else{
                             $conversion=0;
                           }
                           $sql_update='UPDATE ' . $table_dict . ' SET conversion=' . $conversion . ' WHERE group_id=' . $group['group_id'];
                     update_sql_db4($sql_update);

                     $sql2='SELECT * FROM cat_amo_stage WHERE group_id LIKE "%,' . $group['group_id'] . '%" AND car_id=1 AND stage_id=0';
                     $sdelki=select_sql_db4($sql2);
                           $summ='';
                           foreach ($sdelki as $key => $value) {
                             $summ .=$value['cost'] . ',';
                           }
                           $res_s_ar=array_sum(explode(',',trim($summ,',')));
                           $c_bad=count($sdelki);
                           p($c_bad);
                           if ($c_bad==0) {
                             $sr_ar=0;
                           }else{
                             $sr_ar=$res_s_ar/$c_bad;
                           }

                           $sql_update='UPDATE ' . $table_dict . ' SET bad=' . $c_bad . ',sr_cost_bad=' . $sr_ar . ' WHERE group_id=' . $group['group_id'];
                     update_sql_db4($sql_update);





                   }
   }
 }
 // code...

?>
</div>



<style>
.flex_column{
  display:flex;
  flex-direction: column;

}
.category{
  color:#bd3f3f;
}
.subcat{
  color:#516cbd;
}
.group{
  color:#61ca74;
}
.text_center{
  display: flex;
  align-items: center;
  justify-content: center;
}
.flex_row{
  display:flex;
  flex-direction: row;
}
.flex_grow{
  display:flex;
  flex-flow: wrap;
}
.bord_grey{
  border:1px solid;
  padding:5px;
}
.bord_grey a {
  font-size: 150%;
}
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
