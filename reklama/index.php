<?php
require_once '../config.php';
error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set('max_execution_time', 0);
require_once ANG_ROOT . "functions.php";
include ANG_ROOT . "reklama/lib/func.php";

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
     $table_reklama='reklama';
     $table_kernel='kernel_porter_bez';
     $table_kernel_direct='porter_kernel_bez_direct_price';

//список групп из таблицы со всеми данными
     $sql='SELECT DISTINCT * FROM ' . $table_reklama . '';
     $reklama=select_sql_db4($sql);
     ?>
     <div class="flex_column" style="width:calc(100%- 10px)">
      <?php
       foreach ($reklama as $key => $reklam) {
      ?>
       <div class="flex_row category" style="width:calc(100%)">
         <div class="bord_grey " style="width:20%;">
           <p style="font-size:12px"><?=$reklam['category_name']?> .. <?=$reklam['subcat_name']?></p>
           <p><?=$reklam['group_name']?> [<?=$reklam['group_id']?>]</p>
           <p>плюс слова: <?=$reklam['key_plus']?></p>
           <p>минус слова: <?=$reklam['key_minus']?></p>
         </div>
         <div class="bord_grey subcat" style="width:80%;display:flex;flex-direction:row-reverse">
           <?php
           $clicks='';
           $summa_cpc='';
           $zaprosi='';
           if ($reklam['group_id']=='') {
             $reklam['group_id']='123123123123123';
           }
           if ($reklam['subcat_id']=="") {
             $reklam['subcat_id']="123123123123123";
           }
           if ($reklam['category_id']=="") {
             $reklam['category_id']="123123123123123";
           }
           if ($reklam['group_id']!=='123123123123123') {
             $sql1='SELECT * FROM ' . $table_kernel . ' WHERE group_id LIKE "%,' . $reklam['group_id'] . '%"';
           }elseif($reklam['subcat_id']!="123123123123123"){
             $sql1='SELECT * FROM ' . $table_kernel . ' WHERE subcat_id LIKE "%,' . $reklam['subcat_id'] . '%" AND group_id=",void"';
           }elseif ($reklam['category_id']!="123123123123123") {
             $sql1='SELECT * FROM ' . $table_kernel . ' WHERE category_id LIKE "' . $reklam['category_id'] . '" AND subcat_id=",void" AND group_id=",void"';
           }
            //получаем ключевые слова на группу
            $kernel=select_sql_db4($sql1);
            ?>
            <div style="width:80%">
            <?php
            foreach ($kernel as $key => $kern) {
              //получаем статистику ключевых слов из директа
              $sql2='SELECT * FROM ' . $table_kernel_direct . ' WHERE word_id="' . $kern['id'] . '"';
              $direct=select_sql_db4($sql2);
              ?>

                <p><?=$kern['ang_name']?></p>
                <?php

                foreach ($direct as $key => $dir) {
                  $stoimost=$dir['click_price_final']*$dir['pokaz_100'];
                  $clicks .=$dir['pokaz_100'] . ';';
                  $summa_cpc .=$stoimost . ';';
                  $zaprosi .=$dir['zaprosi'] . ';';
                  ?>
                  <!-- <p>запросов:<?=$dir['zaprosi']?> | показов:<?=$dir['pokaz_100']?> | кликов:<?=$dir['perehod_100']?> | стоимость клика:<?=$dir['click_price_final']?></p> -->
                  <?php
                 }

                ?>

            <?php
            if ($reklam['group_id']=='123123123123123') {
              $reklam['group_id']='';
            }
            if ($reklam['subcat_id']=="123123123123123") {
              $reklam['subcat_id']="";
            }
            if ($reklam['category_id']=="123123123123123") {
              $reklam['category_id']="";
            }


            $sql_check='SELECT category_id,category_name,subcat_id,subcat_name,group_id,group_name,keywords FROM groups_and_keywords
            WHERE category_id="' . $reklam['category_id'] . '" AND category_name="' . $reklam['category_name'] . '" AND subcat_id="' . $reklam['subcat_id'] . '"
             AND subcat_name="' . $reklam['subcat_name'] . '" AND group_id="' . $reklam['group_id'] . '" AND group_name="' . $reklam['group_name'] . '" AND keywords="' . $kern['ang_name'] . '"';
             $check=select_sql_db4($sql_check);
             if (isset($check[0]['keywords'])) {
               // code...
             }else{
               $sql_ins='INSERT INTO groups_and_keywords(category_id,category_name,subcat_id,subcat_name,group_id,group_name,keywords) VALUES("' . $reklam['category_id'] . '","' . $reklam['category_name'] . '","' . $reklam['subcat_id'] . '","' . $reklam['subcat_name'] . '","' . $reklam['group_id'] . '","' . $reklam['group_name'] . '","' . $kern['ang_name'] . '")';
                update_sql_db4($sql_ins);
             }

              }
              ?>
            </div>
            <div style="width:20%">
              <?php
              $res1=explode(';',rtrim($clicks,';'));
              // p($res1);
              $summ1=array_sum($res1);
              $res2=explode(';',rtrim($summa_cpc,';'));
              // p($res2);
              $summ2=array_sum($res2);
              $res3=explode(';',rtrim($zaprosi,';'));
              $summ3=array_sum($res3);
              $sr_cpc=$summ2/$summ1;
              $marja_100=100*($reklam['conversion']/100)*$reklam['sr_prib_good'];
              $zatrati_100=100/$reklam['conversion_sites']*$sr_cpc;

              $roi=$reklam['marja']/(100/$reklam['conversion_sites']*$sr_cpc)*100;
              $pribil_100=$reklam['marja']-(100/$reklam['conversion_sites']*$sr_cpc);
              $pribil=(($summ1*$reklam['conversion_sites']*($reklam['conversion']/100)*$reklam['sr_prib_good'])-($summ1*$sr_cpc))*12;
              $rinok=$summ3*$reklam['sr_cost_good']*12*$reklam['conversion_sites'];
              if ($summ1==0) {
                $pribil_100=0;
                $pribil=0;
                $roi=0;
                $sr_cpc=0;
                $rinok=0;
                $zatrati_100=0;
              }
              $sql='UPDATE ' . $table_reklama . ' SET cpc=' . $sr_cpc . ',pribil=' . $pribil_100 . ',zaprosi=' . $summ3 . ',pokazi_prib=' . $pribil . ',pokazi=' . $summ1 . ',obiem_rinka=' . $rinok . ',zatrati=' . $zatrati_100 .  ',roi=' . $roi . ' WHERE id=' . $reklam['id'];
              // p($sql);
              //update_sql_db4($sql);
            ?>
            <p>Средняя цена клика: <?=$sr_cpc?></p>
            <p>Кол-во показов: <?=$summ1?></p>
            <p>ROI: <?=$roi?></p>
            <p>marja_100: <?=$marja_100?></p>
            <p>$zatrati_100: <?=$zatrati_100?></p>
            <p>$pribil_100: <?=$pribil_100?></p>
            <p>$pribil: <?=$pribil?></p>
            <p>кол-во запросов: <?=$summ3?></p>
            <p>объем рынка: <?=$rinok?></p>


          </div>
          </div>
        </div>


        <?php

                  }

        ?>
     </div>














<?php
  }elseif ($_GET['do']==2) {
    $table_reklama='reklama';
    $table_kernel='kernel_porter_bez';
    $table_kernel_direct='porter_kernel_bez_direct_price';
    $table_keywords='groups_and_keywords';
    $table_reklama_dictionary='reklama_dictionary';
    $table_seo_angara='seo_position_angara77';
    $table_seo_zapchastiporter='seo_position_zapchastiporter';
    $table_zp_cat_progon='url_zapchastiporter_category_progon';
    $table_zp_tovar_progon='url_zapchastiporter_tovary_progon';
    $table_zp_cat_url_id='url_zp_category_id';
    $table_zp_tovar_url_id='url_zp_tovar_id';
    $table_angara_cat_progon='url_angara_category_progon';
    $table_angara_tovar_progon='url_angara_tovary_progon';
    $table_angara_cat_url_id='url_angara_category_id';
    $table_angara_tovar_url_id='url_angara_tovar_id';
    $title1_count=35;
    $title2_count=30;
    $text_count=81;
    $url_count=1017;
    $patch2_count=20;
    $all_fast_url_title_count=66;
    $fast_url_text_count=60;
//список групп из таблицы со всеми данными

    ?>
<table border="1">
  <tr>
    <th><a href="/reklama/?do=2&table=-">в начало</a> / категория</th>
    <th>субкатегория</th>
    <th>группа</th>
    <th>показатели / вид объявления</th>
    <th>+слова / ключевые слова</th>
    <td>-слова / метрики</td>
    <th>внести зменения / смежные группы для ключевого слова</th>
  </tr>
    <?php




    $id=$_GET['table'];

    if ($id!="-" AND $id<100) {
//категория
      $razdel='category_id';
      $rpe_razdel="subcat_id";
      $pre_razdel_sql=' AND ' . $rpe_razdel . '=""';
      $sql='SELECT DISTINCT * FROM ' . $table_reklama . ' WHERE ' . $razdel . '="' . $id . '" AND subcat_id!="" AND group_id=""';
      $categoryes=select_sql_db4($sql);
      $sql='SELECT DISTINCT * FROM ' . $table_reklama . ' WHERE ' . $razdel . '="' . $id . '" AND subcat_id=""  AND group_id=""';
      $categorys=select_sql_db4($sql);
      p($sql);
      $subcat_groups_sdelki='';
      $subcat_groups_good='';
      $subcat_groups_bad='';
      $subcat_groups_conversion='';
      $subcat_groups_sr_cost='';
      $subcat_groups_sr_cost_good='';
      $subcat_groups_sr_cost_bad='';
      $subcat_groups_summ='';
      $subcat_groups_summ_good='';
      $subcat_groups_summ_bad='';
      $subcat_groups_sr_prib_good='';
      $subcat_groups_abc_amo_good='';

      $subcat_groups_marja='';
      $subcat_groups_zatrati='';
      $subcat_groups_roi='';
      $subcat_groups_visits_angara='';
      $subcat_groups_visits_hp_zp='';
      $subcat_groups_visits_summ='';
      $subcat_groups_conversion_sites='';
      $subcat_groups_cpc='';
      $subcat_groups_pribil='';
      $subcat_groups_zaprosi='';
      $subcat_groups_zaprosi_prib='';
      $subcat_groups_pokazi='';
      $subcat_groups_pokazi_prib='';
      $subcat_groups_clicks='';
      $subcat_groups_clicks_prib='';
      $subcat_groups_obiem_rinka='';
      $subcat_groups_prib_rinka='';
      $subcat_groups_sr_seo_angara='';
      $subcat_groups_sr_seo_zp='';
      foreach ($categoryes as $key => $value) {
        $subcat_groups_sdelki .=$value['sdelki'] . ';';
        $subcat_groups_good .=$value['good'] . ';';
        $subcat_groups_bad .=$value['bad'] . ';';
        $subcat_groups_conversion .=$value['conversion'] . ';';
        $subcat_groups_sr_cost .=$value['sr_cost'] . ';';
        $subcat_groups_sr_cost_good .=$value['sr_cost_good'] . ';';
        $subcat_groups_sr_cost_bad .=$value['sr_cost_bad'] . ';';
        $subcat_groups_summ .=$value['summ'] . ';';
        $subcat_groups_summ_good .=$value['summ_good'] . ';';
        $subcat_groups_summ_bad .=$value['summ_bad'] . ';';
        $subcat_groups_sr_prib_good .=$value['sr_prib_good'] . ';';
        $subcat_groups_abc_amo_good .=$value['abc_amo_good'] . ';';

        $subcat_groups_marja .=$value['marja'] . ';';
        $subcat_groups_zatrati .=$value['zatrati'] . ';';
        $subcat_groups_roi .=$value['roi'] . ';';
        $subcat_groups_visits_angara .=$value['visits_angara'] . ';';
        $subcat_groups_visits_hp_zp .=$value['visits_hp_zp'] . ';';
        $subcat_groups_visits_summ .=$value['visits_summ'] . ';';
        $subcat_groups_conversion_sites .=$value['conversion_sites'] . ';';
        $subcat_groups_cpc .=$value['cpc'] . ';';
        $subcat_groups_pribil .=$value['pribil'] . ';';
        $subcat_groups_zaprosi .=$value['zaprosi'] . ';';
        $subcat_groups_zaprosi_prib .=$value['zaprosi_prib'] . ';';
        $subcat_groups_pokazi .=$value['pokazi'] . ';';
        $subcat_groups_pokazi_prib .=$value['pokazi_prib'] . ';';
        $subcat_groups_clicks .=$value['clicks'] . ';';
        $subcat_groups_clicks_prib .=$value['clicks_prib'] . ';';
        $subcat_groups_obiem_rinka .=$value['obiem_rinka'] . ';';
        $subcat_groups_prib_rinka .=$value['prib_rinka'] . ';';
        $subcat_groups_sr_seo_angara .=$value['sr_seo_angara'] . ';';
        $subcat_groups_sr_seo_zp .=$value['sr_seo_zp'] . ';';
      }
      $subcat_groups_sdelki_res=rtrim($subcat_groups_sdelki,';');
      $subcat_groups_good_res=rtrim($subcat_groups_good,';');
      $subcat_groups_bad_res=rtrim($subcat_groups_bad,';');
      $subcat_groups_conversion_res=rtrim($subcat_groups_conversion,';');
      $subcat_groups_sr_cost_res=rtrim($subcat_groups_sr_cost,';');
      $subcat_groups_sr_cost_good_res=rtrim($subcat_groups_sr_cost_good,';');
      $subcat_groups_sr_cost_bad_res=rtrim($subcat_groups_sr_cost_bad,';');
      $subcat_groups_summ_res=rtrim($subcat_groups_summ,';');
      $subcat_groups_summ_good_res=rtrim($subcat_groups_summ_good,';');
      $subcat_groups_summ_bad_res=rtrim($subcat_groups_summ_bad,';');
      $subcat_groups_sr_prib_good_res=rtrim($subcat_groups_sr_prib_good,';');
      $subcat_groups_abc_amo_good_res=rtrim($subcat_groups_abc_amo_good,';');

      $subcat_groups_marja_res=rtrim($subcat_groups_marja,';');
      $subcat_groups_zatrati_res=rtrim($subcat_groups_zatrati,';');
      $subcat_groups_roi_res=rtrim($subcat_groups_roi,';');
      $subcat_groups_visits_angara_res=rtrim($subcat_groups_visits_angara,';');
      $subcat_groups_visits_hp_zp_res=rtrim($subcat_groups_visits_hp_zp,';');
      $subcat_groups_visits_summ_res=rtrim($subcat_groups_visits_summ,';');
      $subcat_groups_conversion_sites_res=rtrim($subcat_groups_conversion_sites,';');
      $subcat_groups_cpc_res=rtrim($subcat_groups_cpc,';');
      $subcat_groups_pribil_res=rtrim($subcat_groups_pribil,';');
      $subcat_groups_zaprosi_res=rtrim($subcat_groups_zaprosi,';');
      $subcat_groups_zaprosi_prib_res=rtrim($subcat_groups_zaprosi_prib,';');
      $subcat_groups_pokazi_res=rtrim($subcat_groups_pokazi,';');
      $subcat_groups_pokazi_prib_res=rtrim($subcat_groups_pokazi_prib,';');
      $subcat_groups_clicks_res=rtrim($subcat_groups_clicks,';');
      $subcat_groups_clicks_prib_res=rtrim($subcat_groups_clicks_prib,';');
      $subcat_groups_obiem_rinka_res=rtrim($subcat_groups_obiem_rinka,';');
      $subcat_groups_prib_rinka_res=rtrim($subcat_groups_prib_rinka,';');
      $subcat_groups_sr_seo_angara_res=rtrim($subcat_groups_sr_seo_angara,';');
      $subcat_groups_sr_seo_zp_res=rtrim($subcat_groups_sr_seo_zp,';');

        $subcat_groups_sdelki_summ=number_format(array_sum(explode(';',$subcat_groups_sdelki_res)),0,'.','');
        $subcat_groups_good_summ=number_format(array_sum(explode(';',$subcat_groups_good_res)),0,'.','');
        $subcat_groups_bad_summ=number_format(array_sum(explode(';',$subcat_groups_bad_res)),0,'.','');
        $subcat_groups_conversion_summ=array_sum(explode(';',$subcat_groups_conversion_res));
        $subcat_groups_sr_cost_summ=array_sum(explode(';',$subcat_groups_sr_cost_res));
        $subcat_groups_sr_cost_good_summ=array_sum(explode(';',$subcat_groups_sr_cost_good_res));
        $subcat_groups_sr_cost_bad_summ=array_sum(explode(';',$subcat_groups_sr_cost_bad_res));
        $subcat_groups_summ_summ=number_format(array_sum(explode(';',$subcat_groups_summ_res)),0,'.','');
        $subcat_groups_summ_good_summ=number_format(array_sum(explode(';',$subcat_groups_summ_good_res)),0,'.','');
        $subcat_groups_summ_bad_summ=number_format(array_sum(explode(';',$subcat_groups_summ_bad_res)),0,'.','');
        $subcat_groups_sr_prib_good_summ=array_sum(explode(';',$subcat_groups_sr_prib_good_res));
        $subcat_groups_marja_summ=number_format(array_sum(explode(';',$subcat_groups_marja_res)),0,'.','');
        $subcat_groups_zatrati_summ=number_format(array_sum(explode(';',$subcat_groups_zatrati_res)),0,'.','');
        $subcat_groups_roi_summ=array_sum(explode(';',$subcat_groups_roi_res));
        $subcat_groups_visits_angara_summ=number_format(array_sum(explode(';',$subcat_groups_visits_angara_res)),0,'.','');
        $subcat_groups_visits_hp_zp_summ=number_format(array_sum(explode(';',$subcat_groups_visits_hp_zp_res)),0,'.','');
        $subcat_groups_visits_summ_summ=number_format(array_sum(explode(';',$subcat_groups_visits_summ_res)),0,'.','');
        $subcat_groups_conversion_sites_summ=array_sum(explode(';',$subcat_groups_conversion_sites_res));
        $subcat_groups_cpc_summ=array_sum(explode(';',$subcat_groups_cpc_res));
        $subcat_groups_pribil_summ=number_format(array_sum(explode(';',$subcat_groups_pribil_res)),0,'.','');
        $subcat_groups_zaprosi_summ=number_format(array_sum(explode(';',$subcat_groups_zaprosi_res)),0,'.','');
        $subcat_groups_zaprosi_prib_summ=number_format(array_sum(explode(';',$subcat_groups_zaprosi_prib_res)),0,'.','');
        $subcat_groups_pokazi_summ=number_format(array_sum(explode(';',$subcat_groups_pokazi_res)),0,'.','');
        $subcat_groups_pokazi_prib_summ=number_format(array_sum(explode(';',$subcat_groups_pokazi_prib_res)),0,'.','');
        $subcat_groups_clicks_summ=number_format(array_sum(explode(';',$subcat_groups_clicks_res)),0,'.','');
        $subcat_groups_clicks_prib_summ=number_format(array_sum(explode(';',$subcat_groups_clicks_prib_res)),0,'.','');
        $subcat_groups_obiem_rinka_summ=number_format(array_sum(explode(';',$subcat_groups_obiem_rinka_res)),0,'.','');
        $subcat_groups_prib_rinka_summ=number_format(array_sum(explode(';',$subcat_groups_prib_rinka_res)),0,'.','');
        $subcat_groups_sr_seo_angara_summ=array_sum(explode(';',$subcat_groups_sr_seo_angara_res));
        $subcat_groups_sr_seo_zp_summ=array_sum(explode(';',$subcat_groups_sr_seo_zp_res));

          $subcat_groups_conversion_count=count(explode(';',$subcat_groups_conversion_res));
          $subcat_groups_sr_cost_count=count(explode(';',$subcat_groups_sr_cost_res));
          $subcat_groups_sr_cost_good_count=count(explode(';',$subcat_groups_sr_cost_good_res));
          $subcat_groups_sr_cost_bad_count=count(explode(';',$subcat_groups_sr_cost_bad_res));
          $subcat_groups_sr_prib_good_count=count(explode(';',$subcat_groups_sr_prib_good_res));
          $subcat_groups_roi_count=count(explode(';',$subcat_groups_roi_res));
          $subcat_groups_conversion_sites_count=count(explode(';',$subcat_groups_conversion_sites_res));
          $subcat_groups_cpc_count=count(explode(';',$subcat_groups_cpc_res));
          $subcat_groups_sr_seo_angara_count=count(explode(';',$subcat_groups_sr_seo_angara_res));
          $subcat_groups_sr_seo_zp_count=count(explode(';',$subcat_groups_sr_seo_zp_res));




            $subcat_groups_conversion_sr=number_format($subcat_groups_conversion_summ/$subcat_groups_conversion_count,2,'.','');
            $subcat_groups_sr_cost_sr=number_format($subcat_groups_sr_cost_summ/$subcat_groups_sr_cost_count,2,'.','');
            $subcat_groups_sr_cost_good_sr=number_format($subcat_groups_sr_cost_good_summ/$subcat_groups_sr_cost_good_count,2,'.','');
            $subcat_groups_sr_cost_bad_sr=number_format($subcat_groups_sr_cost_bad_summ/$subcat_groups_sr_cost_bad_count,2,'.','');
            $subcat_groups_sr_prib_good_sr=number_format($subcat_groups_sr_prib_good_summ/$subcat_groups_sr_prib_good_count,2,'.','');
            $subcat_groups_roi_sr=number_format($subcat_groups_roi_summ/$subcat_groups_roi_count,2,'.','');
            $subcat_groups_conversion_sites_sr=number_format($subcat_groups_conversion_sites_summ/$subcat_groups_conversion_sites_count,2,'.','');
            $subcat_groups_cpc_sr=number_format($subcat_groups_cpc_summ/$subcat_groups_cpc_count,2,'.','');
            $subcat_groups_sr_seo_angara_sr=number_format($subcat_groups_sr_seo_angara_summ/$subcat_groups_sr_seo_angara_count,2,'.','');
            $subcat_groups_sr_seo_zp_sr=number_format($subcat_groups_sr_seo_zp_summ/$subcat_groups_sr_seo_zp_count,2,'.','');
            p($subcat_groups_sr_seo_zp_sr);


              if ($subcat_groups_sdelki_summ==$categorys[0]['sdelki']
              AND $subcat_groups_good_summ==$categorys[0]['good']
              AND $subcat_groups_bad_summ==$categorys[0]['bad']
              AND $subcat_groups_conversion_sr==$categorys[0]['conversion']
              AND $subcat_groups_sr_cost_sr==$categorys[0]['sr_cost']
              AND $subcat_groups_sr_cost_good_sr==$categorys[0]['sr_cost_good']
              AND $subcat_groups_sr_cost_bad_sr==$categorys[0]['sr_cost_bad']
              AND $subcat_groups_summ_summ==$categorys[0]['summ']
              AND $subcat_groups_summ_good_summ==$categorys[0]['summ_good']
              AND $subcat_groups_summ_bad_summ==$categorys[0]['summ_bad']
              AND $subcat_groups_sr_prib_good_sr==$categorys[0]['sr_prib_good']
              AND $subcat_groups_marja_summ==$categorys[0]['marja']
              AND $subcat_groups_zatrati_summ==$categorys[0]['zatrati']
              AND $subcat_groups_roi_sr==$categorys[0]['roi']
              AND $subcat_groups_visits_angara_summ==$categorys[0]['visits_angara']
              AND $subcat_groups_visits_hp_zp_summ==$categorys[0]['visits_hp_zp']
              AND $subcat_groups_visits_summ_summ==$categorys[0]['visits_summ']
              AND $subcat_groups_conversion_sites_sr==$categorys[0]['conversion_sites']
              AND $subcat_groups_cpc_sr==$categorys[0]['cpc']
              AND $subcat_groups_pribil_summ==$categorys[0]['pribil']
              AND $subcat_groups_zaprosi_summ==$categorys[0]['zaprosi']
              AND $subcat_groups_zaprosi_prib_summ==$categorys[0]['zaprosi_prib']
              AND $subcat_groups_pokazi_summ==$categorys[0]['pokazi']
              AND $subcat_groups_pokazi_prib_summ==$categorys[0]['pokazi_prib']
              AND $subcat_groups_clicks_summ==$categorys[0]['clicks']
              AND $subcat_groups_clicks_prib_summ==$categorys[0]['clicks_prib']
              AND $subcat_groups_obiem_rinka_summ==$categorys[0]['obiem_rinka']
              AND $subcat_groups_prib_rinka_summ==$categorys[0]['prib_rinka']
              AND $subcat_groups_sr_seo_angara_sr==$categorys[0]['sr_seo_angara']
              AND $subcat_groups_sr_seo_zp_sr==$categorys[0]['sr_seo_zp']
              ){

              }else{
                $sql='UPDATE ' . $table_reklama . ' SET
                sdelki=' . $subcat_groups_sdelki_summ . ',
                good=' . $subcat_groups_good_summ . ',
                bad=' . $subcat_groups_bad_summ . ',
                conversion=' . $subcat_groups_conversion_sr . ',
                sr_cost=' . $subcat_groups_sr_cost_sr . ',
                sr_cost_good=' . $subcat_groups_sr_cost_good_sr . ',
                sr_cost_bad=' . $subcat_groups_sr_cost_bad_sr . ',
                summ=' . $subcat_groups_summ_summ . ',
                summ_good=' . $subcat_groups_summ_good_summ . ',
                summ_bad=' . $subcat_groups_summ_bad_summ . ',
                sr_prib_good=' . $subcat_groups_sr_prib_good_sr . ',
                marja=' . $subcat_groups_marja_summ . ',
                zatrati=' . $subcat_groups_zatrati_summ . ',
                roi=' . $subcat_groups_roi_sr . ',
                visits_angara=' . $subcat_groups_visits_angara_summ . ',
                visits_hp_zp=' . $subcat_groups_visits_hp_zp_summ . ',
                visits_summ=' . $subcat_groups_visits_summ_summ . ',
                conversion_sites=' . $subcat_groups_conversion_sites_sr . ',
                cpc=' . $subcat_groups_cpc_sr . ',
                pribil=' . $subcat_groups_pribil_summ . ',
                zaprosi=' . $subcat_groups_zaprosi_summ . ',
                zaprosi_prib=' . $subcat_groups_zaprosi_prib_summ . ',
                pokazi=' . $subcat_groups_pokazi_summ . ',
                pokazi_prib=' . $subcat_groups_pokazi_prib_summ . ',
                clicks=' . $subcat_groups_clicks_summ . ',
                clicks_prib=' . $subcat_groups_clicks_prib_summ . ',
                obiem_rinka=' . $subcat_groups_obiem_rinka_summ . ',
                prib_rinka=' . $subcat_groups_prib_rinka_summ . ',
                sr_seo_angara=' . $subcat_groups_sr_seo_angara_sr . ',
                sr_seo_zp=' . $subcat_groups_sr_seo_zp_sr . '
                WHERE ' . $razdel . '="' . $id . '" AND subcat_id=""';
                //p($sql);
                update_sql_db4($sql);
              }
      ?>

      <tr>
        <td><a href="/reklama/?do=2&table=<?=$categoryes[0]['category_id']?>"><?=$categorys[0]['category_name']?></a></td>
        <td><?php foreach ($categoryes as $key => $category){ ?>
          <a href="/reklama/?do=2&table=<?=$category['subcat_id']?>"><?=$category['subcat_name']?></a><hr>
        <?php } ?></td>
        <?php if (isset($categorys[0])) {?>

        <td>запросов: <?=$categorys[0]['zaprosi']?><br>конверсия: <?=$categorys[0]['conversion']?>%<br>абц группа: <?=$categorys[0]['abc_amo_good']?> <br>рой: <?=$categorys[0]['roi']?>% <br>цена клика: <?=$categorys[0]['cpc']?> <br>прибыль: <?=$categorys[0]['pribil']?><br>ср. прибыль: <?=$categorys[0]['sr_prib_good']?></td>
        <!--ПЛЮС СЛОВА-->
                <td>
                  <div style="display:flex;flex-direction:column;justify-content:space-between;height:100%">
                  <div style="display:flex;flex-flow:row wrap">
                  <?php $plus=explode(',',$categorys[0]['key_plus']);
                            foreach ($plus as $key => $pl) {?>
                              <div style="min-width:50%;padding-left:2px;color:#256d20;"><?=$pl?></div>
                          <?php  }
                          ?>
                          </div>
                          <div>
                          <form action="" method="get" style="border:1px solid #aa4322;padding:10px;margin:0">
                            добавить плюс слово
                            <input hidden name="do" value="<?=$_GET['do']?>"></input>
                            <input hidden name="table" value="<?=$_GET['table']?>"></input>
                            <input name="key_plus"></input>
                             <p><input type="submit" value="Отправить"></p>
                            </form>
                            <?php if (isset($_GET['key_plus']) AND !empty($_GET['key_plus'])) {
                              // $sql='SELECT DISTINCT key_plus FROM ' . $table_reklama . ' WHERE key_plus LIKE "%,' . $_GET['key_plus'] . '%" OR key_plus LIKE "%' . $_GET['key_plus'] . ',%" AND ' . $razdel . '=' . $id;
                              // $keys=select_sql_db4($sql);
                              if (in_array($_GET['key_plus'],$plus)) {
                                echo "плюс слово `" . $_GET['key_plus'] . "` уже есть в плюс словах";

                              }else{?>
                                <form action="" method="get" style="border:1px solid #aa4322;padding:10px;background-color:#ffa8a9;margin:0">
                                  Добавить к плюс-словам в таблицу `<?=$table_reklama?>` слово:  [,<?=$_GET['key_plus']?>]?<br>
                                  <input hidden name="do" value="<?=$_GET['do']?>"></input>
                                  <input hidden name="table" value="<?=$_GET['table']?>"></input>
                                  <input hidden name="key_plus" value="<?=$_GET['key_plus']?>"></input>
                                  <input type="radio" name="answer" value="yes">да</input><input type="radio" name="answer" value="no">нет</input>
                                  <input type="submit" value="вперед">
                                  </form>
                                <?php
                                if (isset($_GET['answer']) AND $_GET['answer']=="yes") {
                                  $sql_upd='UPDATE ' . $table_reklama . ' SET  key_plus=CONCAT(key_plus,",","' . $_GET['key_plus'] . '"),is_checked=1 WHERE ' . $razdel . '=' . $id;
                                  p($sql_upd);
                                  update_sql_db4($sql_upd);
                                }else{}
                              }

                            }?>
                          </div>
                          </div>
                </td>

        <!--МИНУС СЛОВА-->
                <td>
                  <div style="display:flex;flex-direction:column;justify-content:space-between">
                  <div style="display:flex;flex-flow:row wrap;max-width:200px">
                  <?php $minus=explode(',',$categorys[0]['key_minus']);
                      foreach ($minus as $key => $min) {?>
                        <div style="min-width:49%;padding-left:2px;color:#dc2020">-<?=$min?></div>
                      <?php  }
                      ?>
                    </div>
                    <div>
                    <form action="" method="get" style="border:1px solid #aa4322;padding:10px;margin:0">
                      добавить минус слово
                      <input hidden name="do" value="<?=$_GET['do']?>"></input>
                      <input hidden name="table" value="<?=$_GET['table']?>"></input>
                      <input name="key_minus"></input>
                       <p><input type="submit" value="Отправить"></p>
                      </form>

                      <?php if (isset($_GET['key_minus']) AND !empty($_GET['key_minus'])) {
                        if (in_array($_GET['key_minus'],$minus)) {
                          echo "минус слово `" . $_GET['key_minus'] . "` уже есть в минус словах";

                        }else{?>
                          <form action="" method="get" style="border:1px solid #aa4322;padding:10px;background-color:#ffa8a9;margin:0">
                            Добавить к минус-словам в таблицу `<?=$table_reklama?>` слово:  [,<?=$_GET['key_minus']?>]?<br>
                            <input hidden name="do" value="<?=$_GET['do']?>"></input>
                            <input hidden name="table" value="<?=$_GET['table']?>"></input>
                            <input hidden name="key_minus" value="<?=$_GET['key_minus']?>"></input>
                            <input type="radio" name="answer" value="yes">да</input><input type="radio" name="answer" value="no">нет</input>
                            <input type="submit" value="вперед">
                            </form>
                          <?php
                          if (isset($_GET['answer']) AND $_GET['answer']=="yes") {
                            $sql_upd='UPDATE ' . $table_reklama . ' SET  key_minus=CONCAT(key_minus,",","' . $_GET['key_minus'] . '"),is_checked=1 WHERE ' . $razdel . '=' . $id;
                            p($sql_upd);
                            update_sql_db4($sql_upd);
                          }else{}
                        }


                      }?>
                    </div>
                      </div>
                  </td>
                  <?php
                }
                ?>

                <td>внести изменения</td>

              </tr>
    <?php





}elseif($id!="-" AND $id<1000){
//субкат
      $razdel='subcat_id';
      $rpe_razdel='group_id';
      $pre_razdel_sql=' AND ' . $rpe_razdel . '=""';
      $sql='SELECT DISTINCT * FROM ' . $table_reklama . ' WHERE ' . $razdel . '="' . $id . '" AND group_id!=""';
      $categoryes=select_sql_db4($sql);
      $sql='SELECT DISTINCT * FROM ' . $table_reklama . ' WHERE ' . $razdel . '="' . $id . '" AND group_id=""';
      $categorys=select_sql_db4($sql);
      // p($categorys);

      $subcat_groups_sdelki='';
      $subcat_groups_good='';
      $subcat_groups_bad='';
      $subcat_groups_conversion='';
      $subcat_groups_sr_cost='';
      $subcat_groups_sr_cost_good='';
      $subcat_groups_sr_cost_bad='';
      $subcat_groups_summ='';
      $subcat_groups_summ_good='';
      $subcat_groups_summ_bad='';
      $subcat_groups_sr_prib_good='';
      $subcat_groups_abc_amo_good='';

      $subcat_groups_marja='';
      $subcat_groups_zatrati='';
      $subcat_groups_roi='';
      $subcat_groups_visits_angara='';
      $subcat_groups_visits_hp_zp='';
      $subcat_groups_visits_summ='';
      $subcat_groups_conversion_sites='';
      $subcat_groups_cpc='';
      $subcat_groups_pribil='';
      $subcat_groups_zaprosi='';
      $subcat_groups_zaprosi_prib='';
      $subcat_groups_pokazi='';
      $subcat_groups_pokazi_prib='';
      $subcat_groups_clicks='';
      $subcat_groups_clicks_prib='';
      $subcat_groups_obiem_rinka='';
      $subcat_groups_prib_rinka='';
      $subcat_groups_sr_seo_angara='';
      $subcat_groups_sr_seo_zp='';
      foreach ($categoryes as $key => $value) {
        $subcat_groups_sdelki .=$value['sdelki'] . ';';
        $subcat_groups_good .=$value['good'] . ';';
        $subcat_groups_bad .=$value['bad'] . ';';
        $subcat_groups_conversion .=$value['conversion'] . ';';
        $subcat_groups_sr_cost .=$value['sr_cost'] . ';';
        $subcat_groups_sr_cost_good .=$value['sr_cost_good'] . ';';
        $subcat_groups_sr_cost_bad .=$value['sr_cost_bad'] . ';';
        $subcat_groups_summ .=$value['summ'] . ';';
        $subcat_groups_summ_good .=$value['summ_good'] . ';';
        $subcat_groups_summ_bad .=$value['summ_bad'] . ';';
        $subcat_groups_sr_prib_good .=$value['sr_prib_good'] . ';';
        $subcat_groups_abc_amo_good .=$value['abc_amo_good'] . ';';

        $subcat_groups_marja .=$value['marja'] . ';';
        $subcat_groups_zatrati .=$value['zatrati'] . ';';
        $subcat_groups_roi .=$value['roi'] . ';';
        $subcat_groups_visits_angara .=$value['visits_angara'] . ';';
        $subcat_groups_visits_hp_zp .=$value['visits_hp_zp'] . ';';
        $subcat_groups_visits_summ .=$value['visits_summ'] . ';';
        $subcat_groups_conversion_sites .=$value['conversion_sites'] . ';';
        $subcat_groups_cpc .=$value['cpc'] . ';';
        $subcat_groups_pribil .=$value['pribil'] . ';';
        $subcat_groups_zaprosi .=$value['zaprosi'] . ';';
        $subcat_groups_zaprosi_prib .=$value['zaprosi_prib'] . ';';
        $subcat_groups_pokazi .=$value['pokazi'] . ';';
        $subcat_groups_pokazi_prib .=$value['pokazi_prib'] . ';';
        $subcat_groups_clicks .=$value['clicks'] . ';';
        $subcat_groups_clicks_prib .=$value['clicks_prib'] . ';';
        $subcat_groups_obiem_rinka .=$value['obiem_rinka'] . ';';
        $subcat_groups_prib_rinka .=$value['prib_rinka'] . ';';
        $subcat_groups_sr_seo_angara .=$value['sr_seo_angara'] . ';';
        $subcat_groups_sr_seo_zp .=$value['sr_seo_zp'] . ';';
      }
      $subcat_groups_sdelki_res=rtrim($subcat_groups_sdelki,';');
      $subcat_groups_good_res=rtrim($subcat_groups_good,';');
      $subcat_groups_bad_res=rtrim($subcat_groups_bad,';');
      $subcat_groups_conversion_res=rtrim($subcat_groups_conversion,';');
      $subcat_groups_sr_cost_res=rtrim($subcat_groups_sr_cost,';');
      $subcat_groups_sr_cost_good_res=rtrim($subcat_groups_sr_cost_good,';');
      $subcat_groups_sr_cost_bad_res=rtrim($subcat_groups_sr_cost_bad,';');
      $subcat_groups_summ_res=rtrim($subcat_groups_summ,';');
      $subcat_groups_summ_good_res=rtrim($subcat_groups_summ_good,';');
      $subcat_groups_summ_bad_res=rtrim($subcat_groups_summ_bad,';');
      $subcat_groups_sr_prib_good_res=rtrim($subcat_groups_sr_prib_good,';');
      $subcat_groups_abc_amo_good_res=rtrim($subcat_groups_abc_amo_good,';');

      $subcat_groups_marja_res=rtrim($subcat_groups_marja,';');
      $subcat_groups_zatrati_res=rtrim($subcat_groups_zatrati,';');
      $subcat_groups_roi_res=rtrim($subcat_groups_roi,';');
      $subcat_groups_visits_angara_res=rtrim($subcat_groups_visits_angara,';');
      $subcat_groups_visits_hp_zp_res=rtrim($subcat_groups_visits_hp_zp,';');
      $subcat_groups_visits_summ_res=rtrim($subcat_groups_visits_summ,';');
      $subcat_groups_conversion_sites_res=rtrim($subcat_groups_conversion_sites,';');
      $subcat_groups_cpc_res=rtrim($subcat_groups_cpc,';');
      $subcat_groups_pribil_res=rtrim($subcat_groups_pribil,';');
      $subcat_groups_zaprosi_res=rtrim($subcat_groups_zaprosi,';');
      $subcat_groups_zaprosi_prib_res=rtrim($subcat_groups_zaprosi_prib,';');
      $subcat_groups_pokazi_res=rtrim($subcat_groups_pokazi,';');
      $subcat_groups_pokazi_prib_res=rtrim($subcat_groups_pokazi_prib,';');
      $subcat_groups_clicks_res=rtrim($subcat_groups_clicks,';');
      $subcat_groups_clicks_prib_res=rtrim($subcat_groups_clicks_prib,';');
      $subcat_groups_obiem_rinka_res=rtrim($subcat_groups_obiem_rinka,';');
      $subcat_groups_prib_rinka_res=rtrim($subcat_groups_prib_rinka,';');
      $subcat_groups_sr_seo_angara_res=rtrim($subcat_groups_sr_seo_angara,';');
      $subcat_groups_sr_seo_zp_res=rtrim($subcat_groups_sr_seo_zp,';');

        $subcat_groups_sdelki_summ=number_format(array_sum(explode(';',$subcat_groups_sdelki_res)),0,'.','');
        $subcat_groups_good_summ=number_format(array_sum(explode(';',$subcat_groups_good_res)),0,'.','');
        $subcat_groups_bad_summ=number_format(array_sum(explode(';',$subcat_groups_bad_res)),0,'.','');
        $subcat_groups_conversion_summ=array_sum(explode(';',$subcat_groups_conversion_res));
        $subcat_groups_sr_cost_summ=array_sum(explode(';',$subcat_groups_sr_cost_res));
        $subcat_groups_sr_cost_good_summ=array_sum(explode(';',$subcat_groups_sr_cost_good_res));
        $subcat_groups_sr_cost_bad_summ=array_sum(explode(';',$subcat_groups_sr_cost_bad_res));
        $subcat_groups_summ_summ=number_format(array_sum(explode(';',$subcat_groups_summ_res)),0,'.','');
        $subcat_groups_summ_good_summ=number_format(array_sum(explode(';',$subcat_groups_summ_good_res)),0,'.','');
        $subcat_groups_summ_bad_summ=number_format(array_sum(explode(';',$subcat_groups_summ_bad_res)),0,'.','');
        $subcat_groups_sr_prib_good_summ=array_sum(explode(';',$subcat_groups_sr_prib_good_res));
        $subcat_groups_marja_summ=number_format(array_sum(explode(';',$subcat_groups_marja_res)),0,'.','');
        $subcat_groups_zatrati_summ=number_format(array_sum(explode(';',$subcat_groups_zatrati_res)),0,'.','');
        $subcat_groups_roi_summ=array_sum(explode(';',$subcat_groups_roi_res));
        $subcat_groups_visits_angara_summ=number_format(array_sum(explode(';',$subcat_groups_visits_angara_res)),0,'.','');
        $subcat_groups_visits_hp_zp_summ=number_format(array_sum(explode(';',$subcat_groups_visits_hp_zp_res)),0,'.','');
        $subcat_groups_visits_summ_summ=number_format(array_sum(explode(';',$subcat_groups_visits_summ_res)),0,'.','');
        $subcat_groups_conversion_sites_summ=array_sum(explode(';',$subcat_groups_conversion_sites_res));
        $subcat_groups_cpc_summ=array_sum(explode(';',$subcat_groups_cpc_res));
        $subcat_groups_pribil_summ=number_format(array_sum(explode(';',$subcat_groups_pribil_res)),0,'.','');
        $subcat_groups_zaprosi_summ=number_format(array_sum(explode(';',$subcat_groups_zaprosi_res)),0,'.','');
        $subcat_groups_zaprosi_prib_summ=number_format(array_sum(explode(';',$subcat_groups_zaprosi_prib_res)),0,'.','');
        $subcat_groups_pokazi_summ=number_format(array_sum(explode(';',$subcat_groups_pokazi_res)),0,'.','');
        $subcat_groups_pokazi_prib_summ=number_format(array_sum(explode(';',$subcat_groups_pokazi_prib_res)),0,'.','');
        $subcat_groups_clicks_summ=number_format(array_sum(explode(';',$subcat_groups_clicks_res)),0,'.','');
        $subcat_groups_clicks_prib_summ=number_format(array_sum(explode(';',$subcat_groups_clicks_prib_res)),0,'.','');
        $subcat_groups_obiem_rinka_summ=number_format(array_sum(explode(';',$subcat_groups_obiem_rinka_res)),0,'.','');
        $subcat_groups_prib_rinka_summ=number_format(array_sum(explode(';',$subcat_groups_prib_rinka_res)),0,'.','');
        $subcat_groups_sr_seo_angara_summ=array_sum(explode(';',$subcat_groups_sr_seo_angara_res));
        $subcat_groups_sr_seo_zp_summ=array_sum(explode(';',$subcat_groups_sr_seo_zp_res));

          $subcat_groups_conversion_count=count(explode(';',$subcat_groups_conversion_res));
          $subcat_groups_sr_cost_count=count(explode(';',$subcat_groups_sr_cost_res));
          $subcat_groups_sr_cost_good_count=count(explode(';',$subcat_groups_sr_cost_good_res));
          $subcat_groups_sr_cost_bad_count=count(explode(';',$subcat_groups_sr_cost_bad_res));
          $subcat_groups_sr_prib_good_count=count(explode(';',$subcat_groups_sr_prib_good_res));
          $subcat_groups_roi_count=count(explode(';',$subcat_groups_roi_res));
          $subcat_groups_conversion_sites_count=count(explode(';',$subcat_groups_conversion_sites_res));
          $subcat_groups_cpc_count=count(explode(';',$subcat_groups_cpc_res));
          $subcat_groups_sr_seo_angara_count=count(explode(';',$subcat_groups_sr_seo_angara_res));
          $subcat_groups_sr_seo_zp_count=count(explode(';',$subcat_groups_sr_seo_zp_res));




            $subcat_groups_conversion_sr=number_format($subcat_groups_conversion_summ/$subcat_groups_conversion_count,0,'.','');
            $subcat_groups_sr_cost_sr=number_format($subcat_groups_sr_cost_summ/$subcat_groups_sr_cost_count,0,'.','');
            $subcat_groups_sr_cost_good_sr=number_format($subcat_groups_sr_cost_good_summ/$subcat_groups_sr_cost_good_count,0,'.','');
            $subcat_groups_sr_cost_bad_sr=number_format($subcat_groups_sr_cost_bad_summ/$subcat_groups_sr_cost_bad_count,0,'.','');
            $subcat_groups_sr_prib_good_sr=number_format($subcat_groups_sr_prib_good_summ/$subcat_groups_sr_prib_good_count,0,'.','');
            $subcat_groups_roi_sr=number_format($subcat_groups_roi_summ/$subcat_groups_roi_count,0,'.','');
            $subcat_groups_conversion_sites_sr=number_format($subcat_groups_conversion_sites_summ/$subcat_groups_conversion_sites_count,2,'.','');
            $subcat_groups_cpc_sr=number_format($subcat_groups_cpc_summ/$subcat_groups_cpc_count,2,'.','');
            $subcat_groups_sr_seo_angara_sr=number_format($subcat_groups_sr_seo_angara_summ/$subcat_groups_sr_seo_angara_count,2,'.','');
            $subcat_groups_sr_seo_zp_sr=number_format($subcat_groups_sr_seo_zp_summ/$subcat_groups_sr_seo_zp_count,2,'.','');
            p($subcat_groups_sr_seo_zp_sr);


              if ($subcat_groups_sdelki_summ==$categorys[0]['sdelki']
              AND $subcat_groups_good_summ==$categorys[0]['good']
              AND $subcat_groups_bad_summ==$categorys[0]['bad']
              AND $subcat_groups_conversion_sr==$categorys[0]['conversion']
              AND $subcat_groups_sr_cost_sr==$categorys[0]['sr_cost']
              AND $subcat_groups_sr_cost_good_sr==$categorys[0]['sr_cost_good']
              AND $subcat_groups_sr_cost_bad_sr==$categorys[0]['sr_cost_bad']
              AND $subcat_groups_summ_summ==$categorys[0]['summ']
              AND $subcat_groups_summ_good_summ==$categorys[0]['summ_good']
              AND $subcat_groups_summ_bad_summ==$categorys[0]['summ_bad']
              AND $subcat_groups_sr_prib_good_sr==$categorys[0]['sr_prib_good']
              AND $subcat_groups_marja_summ==$categorys[0]['marja']
              AND $subcat_groups_zatrati_summ==$categorys[0]['zatrati']
              AND $subcat_groups_roi_sr==$categorys[0]['roi']
              AND $subcat_groups_visits_angara_summ==$categorys[0]['visits_angara']
              AND $subcat_groups_visits_hp_zp_summ==$categorys[0]['visits_hp_zp']
              AND $subcat_groups_visits_summ_summ==$categorys[0]['visits_summ']
              AND $subcat_groups_conversion_sites_sr==$categorys[0]['conversion_sites']
              AND $subcat_groups_cpc_sr==$categorys[0]['cpc']
              AND $subcat_groups_pribil_summ==$categorys[0]['pribil']
              AND $subcat_groups_zaprosi_summ==$categorys[0]['zaprosi']
              AND $subcat_groups_zaprosi_prib_summ==$categorys[0]['zaprosi_prib']
              AND $subcat_groups_pokazi_summ==$categorys[0]['pokazi']
              AND $subcat_groups_pokazi_prib_summ==$categorys[0]['pokazi_prib']
              AND $subcat_groups_clicks_summ==$categorys[0]['clicks']
              AND $subcat_groups_clicks_prib_summ==$categorys[0]['clicks_prib']
              AND $subcat_groups_obiem_rinka_summ==$categorys[0]['obiem_rinka']
              AND $subcat_groups_prib_rinka_summ==$categorys[0]['prib_rinka']
              AND $subcat_groups_sr_seo_angara_sr==$categorys[0]['sr_seo_angara']
              AND $subcat_groups_sr_seo_zp_sr==$categorys[0]['sr_seo_zp']
              ){

              }else{
                $sql='UPDATE ' . $table_reklama . ' SET
                sdelki=' . $subcat_groups_sdelki_summ . ',
                good=' . $subcat_groups_good_summ . ',
                bad=' . $subcat_groups_bad_summ . ',
                conversion=' . $subcat_groups_conversion_sr . ',
                sr_cost=' . $subcat_groups_sr_cost_sr . ',
                sr_cost_good=' . $subcat_groups_sr_cost_good_sr . ',
                sr_cost_bad=' . $subcat_groups_sr_cost_bad_sr . ',
                summ=' . $subcat_groups_summ_summ . ',
                summ_good=' . $subcat_groups_summ_good_summ . ',
                summ_bad=' . $subcat_groups_summ_bad_summ . ',
                sr_prib_good=' . $subcat_groups_sr_prib_good_sr . ',
                marja=' . $subcat_groups_marja_summ . ',
                zatrati=' . $subcat_groups_zatrati_summ . ',
                roi=' . $subcat_groups_roi_sr . ',
                visits_angara=' . $subcat_groups_visits_angara_summ . ',
                visits_hp_zp=' . $subcat_groups_visits_hp_zp_summ . ',
                visits_summ=' . $subcat_groups_visits_summ_summ . ',
                conversion_sites=' . $subcat_groups_conversion_sites_sr . ',
                cpc=' . $subcat_groups_cpc_sr . ',
                pribil=' . $subcat_groups_pribil_summ . ',
                zaprosi=' . $subcat_groups_zaprosi_summ . ',
                zaprosi_prib=' . $subcat_groups_zaprosi_prib_summ . ',
                pokazi=' . $subcat_groups_pokazi_summ . ',
                pokazi_prib=' . $subcat_groups_pokazi_prib_summ . ',
                clicks=' . $subcat_groups_clicks_summ . ',
                clicks_prib=' . $subcat_groups_clicks_prib_summ . ',
                obiem_rinka=' . $subcat_groups_obiem_rinka_summ . ',
                prib_rinka=' . $subcat_groups_prib_rinka_summ . ',
                sr_seo_angara=' . $subcat_groups_sr_seo_angara_sr . ',
                sr_seo_zp=' . $subcat_groups_sr_seo_zp_sr . '
                WHERE ' . $razdel . '="' . $id . '" AND group_id=""';
                // p($sql);
                update_sql_db4($sql);
              }
      ?>

      <tr>
        <td><a href="/reklama/?do=2&table=<?=$categoryes[0]['category_id']?>"><?=$categoryes[0]['category_name']?></a></td>
        <td><a href="/reklama/?do=2&table=<?=$categoryes[0]['subcat_id']?>"><?=$categoryes[0]['subcat_name']?></a></td>
        <td><?php
              foreach ($categoryes as $key => $category){
                // p($category);
                ?>
          <a href="/reklama/?do=2&table=<?=$category['group_id']?>"><?=$category['group_name']?></a><br>
          <?php } ?></td>
        <?php if (isset($categorys[0])) {?>

        <td>запросов: <?=$categorys[0]['zaprosi']?><br>конверсия: <?=$categorys[0]['conversion']?>%<br>абц группа: <?=$categorys[0]['abc_amo_good']?> <br>рой: <?=$categorys[0]['roi']?>% <br>цена клика: <?=$categorys[0]['cpc']?> <br>прибыль: <?=$categorys[0]['pribil']?><br>ср. прибыль: <?=$categorys[0]['sr_prib_good']?></td>
        <!--ПЛЮС СЛОВА-->
                <td>
                  <div style="display:flex;flex-direction:column;justify-content:space-between;height:100%">
                  <div style="display:flex;flex-flow:row wrap">
                  <?php $plus=explode(',',$categorys[0]['key_plus']);
                            foreach ($plus as $key => $pl) {?>
                              <div style="min-width:50%;padding-left:2px;color:#256d20;"><?=$pl?></div>
                          <?php  }
                          ?>
                          </div>
                          <div>
                          <form action="" method="get" style="border:1px solid #aa4322;padding:10px;margin:0">
                            добавить плюс слово
                            <input hidden name="do" value="<?=$_GET['do']?>"></input>
                            <input hidden name="table" value="<?=$_GET['table']?>"></input>
                            <input name="key_plus"></input>
                             <p><input type="submit" value="Отправить"></p>
                            </form>
                            <?php if (isset($_GET['key_plus']) AND !empty($_GET['key_plus'])) {
                              // $sql='SELECT DISTINCT key_plus FROM ' . $table_reklama . ' WHERE key_plus LIKE "%,' . $_GET['key_plus'] . '%" OR key_plus LIKE "%' . $_GET['key_plus'] . ',%" AND ' . $razdel . '=' . $id;
                              // $keys=select_sql_db4($sql);
                              if (in_array($_GET['key_plus'],$plus)) {
                                echo "плюс слово `" . $_GET['key_plus'] . "` уже есть в плюс словах";

                              }else{?>
                                <form action="" method="get" style="border:1px solid #aa4322;padding:10px;background-color:#ffa8a9;margin:0">
                                  Добавить к плюс-словам в таблицу `<?=$table_reklama?>` слово:  [,<?=$_GET['key_plus']?>]?<br>
                                  <input hidden name="do" value="<?=$_GET['do']?>"></input>
                                  <input hidden name="table" value="<?=$_GET['table']?>"></input>
                                  <input hidden name="key_plus" value="<?=$_GET['key_plus']?>"></input>
                                  <input type="radio" name="answer" value="yes">да</input><input type="radio" name="answer" value="no">нет</input>
                                  <input type="submit" value="вперед">
                                  </form>
                                <?php
                                if (isset($_GET['answer']) AND $_GET['answer']=="yes") {
                                  $sql_upd='UPDATE ' . $table_reklama . ' SET  key_plus=CONCAT(key_plus,",","' . $_GET['key_plus'] . '"),is_checked=1 WHERE ' . $razdel . '=' . $id;
                                  p($sql_upd);
                                  update_sql_db4($sql_upd);
                                }else{}
                              }

                            }?>
                          </div>
                          </div>
                </td>

        <!--МИНУС СЛОВА-->
                <td>
                  <div style="display:flex;flex-direction:column;justify-content:space-between">
                  <div style="display:flex;flex-flow:row wrap;max-width:200px">
                  <?php $minus=explode(',',$categorys[0]['key_minus']);
                      foreach ($minus as $key => $min) {?>
                        <div style="min-width:49%;padding-left:2px;color:#dc2020">-<?=$min?></div>
                      <?php  }
                      ?>
                    </div>
                    <div>
                    <form action="" method="get" style="border:1px solid #aa4322;padding:10px;margin:0">
                      добавить минус слово
                      <input hidden name="do" value="<?=$_GET['do']?>"></input>
                      <input hidden name="table" value="<?=$_GET['table']?>"></input>
                      <input name="key_minus"></input>
                       <p><input type="submit" value="Отправить"></p>
                      </form>

                      <?php if (isset($_GET['key_minus']) AND !empty($_GET['key_minus'])) {
                        if (in_array($_GET['key_minus'],$minus)) {
                          echo "минус слово `" . $_GET['key_minus'] . "` уже есть в минус словах";

                        }else{?>
                          <form action="" method="get" style="border:1px solid #aa4322;padding:10px;background-color:#ffa8a9;margin:0">
                            Добавить к минус-словам в таблицу `<?=$table_reklama?>` слово:  [,<?=$_GET['key_minus']?>]?<br>
                            <input hidden name="do" value="<?=$_GET['do']?>"></input>
                            <input hidden name="table" value="<?=$_GET['table']?>"></input>
                            <input hidden name="key_minus" value="<?=$_GET['key_minus']?>"></input>
                            <input type="radio" name="answer" value="yes">да</input><input type="radio" name="answer" value="no">нет</input>
                            <input type="submit" value="вперед">
                            </form>
                          <?php
                          if (isset($_GET['answer']) AND $_GET['answer']=="yes") {
                            $sql_upd='UPDATE ' . $table_reklama . ' SET  key_minus=CONCAT(key_minus,",","' . $_GET['key_minus'] . '"),is_checked=1 WHERE ' . $razdel . '=' . $id;
                            p($sql_upd);
                            update_sql_db4($sql_upd);
                          }else{}
                        }


                      }?>
                    </div>
                      </div>
                  </td>
                  <?php
                }
                ?>

                <td>внести изменения</td>

              </tr>





<?php }elseif($id!="-" AND $id>1000){
//группа
      $razdel='group_id';
      $rpe_razdel="";
      $pre_razdel_sql='';
      $sql='SELECT DISTINCT * FROM ' . $table_reklama . ' WHERE ' . $razdel . '="' . $id . '"';
      // p($sql)
      $categorys=select_sql_db4($sql);?>
      <tr>
        <td><a href="/reklama/?do=2&table=<?=$categorys[0]['category_id']?>"><?=$categorys[0]['category_name']?></a></td>
        <td><a href="/reklama/?do=2&table=<?=$categorys[0]['subcat_id']?>"><?=$categorys[0]['subcat_name']?></a></td>
        <td><a href="/reklama/?do=2&table=<?=$categorys[0]['group_id']?>"><?=$categorys[0]['group_name']?></a></td>
        <td>запросов: <?=$categorys[0]['zaprosi']?><br>конверсия: <?=$categorys[0]['conversion']?>%<br>абц группа: <?=$categorys[0]['abc_amo_good']?> <br>рой: <?=$categorys[0]['roi']?>% <br>цена клика: <?=$categorys[0]['cpc']?> <br>прибыль: <?=$categorys[0]['pribil']?><br>ср. прибыль: <?=$categorys[0]['sr_prib_good']?></td>
<!--ПЛЮС СЛОВА-->
        <td>
          <div style="display:flex;flex-direction:column;justify-content:space-between;height:100%">
          <div style="display:flex;flex-flow:row wrap">
          <?php $plus=explode(',',$categorys[0]['key_plus']);
                    foreach ($plus as $key => $pl) {?>
                      <div style="min-width:50%;padding-left:2px;color:#256d20;"><?=$pl?></div>
                  <?php  }
                  ?>
                  </div>
                  <div>
                  <form action="" method="get" style="border:1px solid #aa4322;padding:10px;margin:0">
                    добавить плюс слово
                    <input hidden name="do" value="<?=$_GET['do']?>"></input>
                    <input hidden name="table" value="<?=$_GET['table']?>"></input>
                    <input name="key_plus"></input>
                     <p><input type="submit" value="Отправить"></p>
                    </form>
                    <?php if (isset($_GET['key_plus']) AND !empty($_GET['key_plus'])) {
                      if (in_array($_GET['key_plus'],$plus)) {
                        echo "плюс слово `" . $_GET['key_plus'] . "` уже есть в плюс словах";

                      }else{?>
                        <form action="" method="get" style="border:1px solid #aa4322;padding:10px;background-color:#ffa8a9;margin:0">
                          Добавить к плюс-словам в таблицу `<?=$table_reklama?>` слово:  [,<?=$_GET['key_plus']?>]?<br>
                          <input hidden name="do" value="<?=$_GET['do']?>"></input>
                          <input hidden name="table" value="<?=$_GET['table']?>"></input>
                          <input hidden name="key_plus" value="<?=$_GET['key_plus']?>"></input>
                          <input type="radio" name="answer" value="yes">да</input><input type="radio" name="answer" value="no">нет</input>
                          <input type="submit" value="вперед">
                          </form>
                        <?php
                        if (isset($_GET['answer']) AND $_GET['answer']=="yes") {
                          $sql_upd='UPDATE ' . $table_reklama . ' SET  key_plus=CONCAT(key_plus,",","' . $_GET['key_plus'] . '"),is_checked=1 WHERE ' . $razdel . '=' . $id;
                          p($sql_upd);
                          update_sql_db4($sql_upd);
                        }else{}
                      }

                    }?>
                  </div>
                  </div>
        </td>

<!--МИНУС СЛОВА-->
        <td>
          <div style="display:flex;flex-direction:column;justify-content:space-between">
          <div style="display:flex;flex-flow:row wrap;max-width:200px">
          <?php $minus=explode(',',$categorys[0]['key_minus']);
              foreach ($minus as $key => $min) {?>
                <div style="min-width:49%;padding-left:2px;color:#dc2020">-<?=$min?></div>
              <?php  }
              ?>
            </div>
            <div>
            <form action="" method="get" style="border:1px solid #aa4322;padding:10px;margin:0">
              добавить минус слово
              <input hidden name="do" value="<?=$_GET['do']?>"></input>
              <input hidden name="table" value="<?=$_GET['table']?>"></input>
              <input name="key_minus"></input>
               <p><input type="submit" value="Отправить"></p>
              </form>

              <?php if (isset($_GET['key_minus']) AND !empty($_GET['key_minus'])) {
                if (in_array($_GET['key_minus'],$minus)) {
                  echo "минус слово `" . $_GET['key_minus'] . "` уже есть в минус словах";

                }else{?>
                  <form action="" method="get" style="border:1px solid #aa4322;padding:10px;background-color:#ffa8a9;margin:0">
                    Добавить к минус-словам в таблицу `<?=$table_reklama?>` слово:  [,<?=$_GET['key_minus']?>]?<br>
                    <input hidden name="do" value="<?=$_GET['do']?>"></input>
                    <input hidden name="table" value="<?=$_GET['table']?>"></input>
                    <input hidden name="key_minus" value="<?=$_GET['key_minus']?>"></input>
                    <input type="radio" name="answer" value="yes">да</input><input type="radio" name="answer" value="no">нет</input>
                    <input type="submit" value="вперед">
                    </form>
                  <?php
                  if (isset($_GET['answer']) AND $_GET['answer']=="yes") {
                    $sql_upd='UPDATE ' . $table_reklama . ' SET  key_minus=CONCAT(key_minus,",","' . $_GET['key_minus'] . '"),is_checked=1 WHERE ' . $razdel . '=' . $id;
                    p($sql_upd);
                    update_sql_db4($sql_upd);
                  }else{}
                }


              }?>
            </div>
              </div>
          </td>
        <td>внести изменения</td>
      </tr>
    <?php
    }else{
      $sql='SELECT DISTINCT category_id,category_name FROM ' . $table_reklama . '';
      $categorys=select_sql_db4($sql);
    }


//ПОШЛИ ДАННЫЕ

                      foreach ($categorys as $key => $category) {

if ($id!="-" AND $id<100) {
//ЕСЛИ ВЫБРАНА категория

                            p($razdel);
                            $sql_url_zp_cat_category='SELECT DISTINCT b.Address,b.ang_name FROM ' . $table_zp_cat_progon . ' a JOIN ' . $table_zp_cat_url_id . ' b WHERE a.id=b.id AND a.category_id LIKE "%,' . $category['category_id'] . '%"';
                            $arr_url_zp_cat_category=select_sql_db4($sql_url_zp_cat_category);
                            p($sql_url_zp_cat_category);
                            $sql_url_zp_cat_subcat='SELECT DISTINCT b.Address,b.ang_name FROM ' . $table_zp_cat_progon . ' a JOIN ' . $table_zp_cat_url_id . ' b WHERE a.id=b.id AND a.subcat_id LIKE "%,' . $category['subcat_id'] . '%"';
                            $arr_url_zp_cat_subcat=select_sql_db4($sql_url_zp_cat_subcat);

                            $sql_url_zp_cat='SELECT DISTINCT b.Address,b.ang_name FROM ' . $table_zp_cat_progon . ' a JOIN ' . $table_zp_cat_url_id . ' b WHERE a.id=b.id AND a.' . $razdel . ' LIKE "%,' . $id . '%" AND a.group_id="" AND a.subcat_id=""';
                            $arr_url_zp_cat=select_sql_db4($sql_url_zp_cat);

                            $sql_url_zp_tov='SELECT DISTINCT b.Address,b.ang_name FROM ' . $table_zp_tovar_progon . ' a JOIN ' . $table_zp_tovar_url_id . ' b WHERE a.id=b.id AND a.' . $razdel . ' LIKE "%,' . $id . '%" AND a.group_id="" AND a.subcat_id=""';
                            $arr_url_zp_tov=select_sql_db4($sql_url_zp_tov);

                            ?>
                            <tr>
                            <td><?php foreach ($arr_url_zp_cat_category as $key => $value) {?>
                              <div>
                                <a style="font-size:13px;" href="<?=$value['Address']?>"><?=$value['ang_name']?></a>
                              </div>
                              <?php }?>
                            </td>
                            <td><?php foreach ($arr_url_zp_cat_subcat as $key => $value) {?>
                              <div>
                                <a style="font-size:13px;" href="<?=$value['Address']?>"><?=$value['ang_name']?></a>
                              </div>
                              <?php }?>
                            </td>
                            <td><?php foreach ($arr_url_zp_cat as $key => $value) {?>
                              <div>
                                <a style="font-size:13px;" href="<?=$value['Address']?>"><?=$value['ang_name']?></a>
                              </div>
                              <?php }?>
                            </td>



                            <td><?php
                              $arr_url_cat='';
                              foreach ($arr_url_zp_tov as $key => $value) {
                              $preg=preg_replace("/([a-z].*)[\/](part)[\/]([0-9]*)[\/]([a-z].*)[\/]/i",'$3',$value['Address']);
                              // p($preg);
                              $sql='SELECT parent FROM angara WHERE 1c_id="' . $preg . '"';
                              $id_1c=select_sql_db4($sql);

                              $id_1c='/porterparts/' . $id_1c[0]['parent'] . '/';
                              $sql2='SELECT * FROM ' . $table_zp_cat_url_id . ' WHERE Address LIKE "%' . $id_1c . '%"';
                              $res_sql2=select_sql_db4($sql2);
                              $url_zp_tov_cat=$res_sql2[0]['Address'];
                              $name_zp_tov_cat=$res_sql2[0]['ang_name'];
                                $arr_url_cat .='`'.$url_zp_tov_cat . '`;`' . $name_zp_tov_cat . '`,';
                                ?>
                                <div>
                                  <a style="font-size:13px;" href="<?=$value['Address']?>"><?=$value['ang_name']?></a>
                                </div>
                                <?php
                              }
                              $res=rtrim($arr_url_cat,',');
                              $ex=explode(',',$res);
                              $ar_count_val=array_count_values($ex);
                              $summ=array_sum($ar_count_val);
                              $max=max($ar_count_val);
                              $percent=$max/$summ*100 . "%";
                              foreach ($ar_count_val as $key => $value) {

                                if ($value==$max) {
                                  $name_cat_zp=preg_replace("/[`](.*)[`][;][`](.*)[`]/i",'$2',$key);
                                  $url_cat_zp=preg_replace("/[`](.*)[`][;][`](.*)[`]/i",'$1',$key);
                                }
                              }
                              ?>


                            <td>
                              <div>
                              <a style="font-size:13px;" href="<?=$url_cat_zp?>"><?=$name_cat_zp?> [<?=$percent?>]</a>
                            </div>
                            </td>
                            </td>
                            <td><?php if(isset($url_cat_zp) AND !empty($url_cat_zp)){
                              $url_obyavlenia=$url_cat_zp;
                            }elseif (isset($arr_url_zp_cat[0]['Address']) AND !empty($arr_url_zp_cat[0]['Address'])) {
                              $url_obyavlenia=$arr_url_zp_cat[0]['Address'];
                            }elseif (isset($arr_url_zp_cat_subcat[0]['Address']) AND !empty($arr_url_zp_cat_subcat[0]['Address'])) {
                              $url_obyavlenia=$arr_url_zp_cat_subcat[0]['Address'];
                            }elseif (isset($arr_url_zp_cat_category[0]['Address']) AND !empty($arr_url_zp_cat_category[0]['Address'])) {
                              $url_obyavlenia=$arr_url_zp_cat_category[0]['Address'];
                            }?>
                            URL объявления: <br>
                            <?=$url_obyavlenia?></td>
                            <td></td>
                            </tr>
                            <!--ключи -->
                            <?php
                            $pribil_all='';
                            $zaprosi_all='';
                            $pokazi_all='';
                            $cpc_all='';
                            $marja_all='';
                            $zatrati_all='';
                            $pribil_all1='';
                            $zaprosi_all1='';
                            $pokazi_all1='';
                            $cpc_all1='';
                            $marja_all1='';
                            $zatrati_all1='';
                            $sr_seo_angara_prib='';
                            $sr_seo_zp_prib='';
                            $sr_seo_angara='';
                            $sr_seo_zp='';
                              $sql='SELECT DISTINCT keywords FROM ' . $table_keywords . ' WHERE ' . $razdel . '=' . $id . ' AND danet=0 ' . $pre_razdel_sql . '';
                              $keywords=select_sql_db4($sql);
                              foreach ($keywords as $key => $keyword) {
                            //ключевое слово

                                $or_predlogi="на|с|для|в|и";

                                $ex_keywords=explode(" ",$keyword['keywords']);
                                // p($ex_keywords);
                                $a='';
                                $sql_where='';
                                $if_preg='';
                                  foreach ($ex_keywords as $key => $ex_key) {
                                    $a .=$ex_key . ',';
                                    $sql_where .='AND keyword LIKE "%' . $ex_key . '%" ';

                                    if (iconv_strlen($ex_key)<=4) {
                                      $tochk="";
                                    }elseif (iconv_strlen($ex_key)<=6){
                                      $tochk="..";
                                    }elseif (iconv_strlen($ex_key)<=9){
                                      $tochk="..";
                                    }else{
                                      $tochk="..";
                                    }
                                    $preg_repl=preg_replace("/(.*)(" . $tochk . ")$/i","$1",$ex_key);
                                    $if_preg .=$preg_repl . '|';
                                    // p($if_preg);
                                    // p($ex_key);
                                    // p(iconv_strlen($ex_key));

                                  }
                                  $res_if_preg=rtrim($if_preg,'|');
                                  $sql='SELECT keyword FROM reklama_done WHERE ' . ltrim($sql_where,'AND') . ' AND keyword!="' . $keyword['keywords'] . '" AND id_rk!=10';
                                  $perececheniya=select_sql_db4($sql);
                                  // echo " ---------------------------------" . $keyword['keywords'] . " ---------------------------------";
                                  // p($res_if_preg);
                                  $b='';
                                  foreach ($perececheniya as $key => $per) {
                                    $b .=$per['keyword'] . ' ';
                                  }

                                  $ex_b=explode(' ',rtrim($b,' '));
                                  // p($ex_b);
                                  $if_preg2='';
                                    foreach ($ex_b as $key => $ex_bb) {
                                      $if_preg2 .=$ex_bb . '|';
                                    }
                                    $res_if_preg2=rtrim($if_preg2,'|');

                                  $ccc='';
                                  foreach ($ex_b as $key => $exb) {
                                      $preg_repl=preg_replace("/(.*)(" . $res_if_preg . ")(.*)/i","qwertyuiop",$exb);

                                      if ($preg_repl=="qwertyuiop" OR $exb=="на" OR $exb=="с" OR $exb=="для" OR $exb=="в" OR $exb=="и") {
                                        // echo "`" . $res_if_preg . "` содержиться в `" . $exb . "` или предлог";
                                        // p($exb);
                                      }else{
                                        $ccc .=$exb . ' ';
                                      }
                                  }
                                  $res_ccc=rtrim($ccc,' ');
                                  $ex_res_ccc=array_unique(explode(' ',$res_ccc));
                                  // p($ex_res_ccc);
                                  // echo "--------------------------------------------!!!!!!!!!!1---------------------";


                                   $cccc='';
                                     foreach ($ex_res_ccc as $key => $exresccc) {
                                       // echo "минус слово: ";
                                       // echo "`" . $exresccc . "`";
                                       if (iconv_strlen($ex_key)<=4) {
                                         $tochk="";
                                       }elseif (iconv_strlen($ex_key)<=6){
                                         $tochk="..";
                                       }elseif (iconv_strlen($ex_key)<=9){
                                         $tochk="..";
                                       }else{
                                         $tochk="..";
                                       }
                                       $minus_tochk=preg_replace("/(.*)(" . $tochk . ")$/i","$1",$exresccc);

                                       $minus_res_if_preg=$res_if_preg . '|' . $minus_tochk;
                                       // echo "________КОМБИНАЦИЯ : `" . $minus_res_if_preg . "`";
                                       $ect6=0;
                                       foreach ($perececheniya as $key => $per) {
                                         $ex_per=explode(" ",$per['keyword']);
                                         $ex_per_count=count($ex_per);
                                         $c=0;
                                         foreach ($ex_per as $key => $ex_per_word) {
                                           // p($ex_per_word);
                                           $minus_all_preg_repl=preg_replace("/(.*)(" . $minus_res_if_preg . ")(.*)/i","qwertyuiop",$ex_per_word);
                                           $minus_all_preg_repl=preg_replace("/(" . $or_predlogi . ")$/i","qwertyuiop",$minus_all_preg_repl);

                                           // p($minus_all_preg_repl);
                                           if ($minus_all_preg_repl=="qwertyuiop") {
                                             $c++;
                                           }else {}
                                         }
                                         if ($c==$ex_per_count) {
                                           $ect6++;
                                         }else{}
                                           // p($c);
                                           // p($ex_per_count);
                                           // echo "-------++++++++++++==========";
                                       }
                                       if ($ect6>0) {
                                          $cccc .='-' . $exresccc . ' ';
                                       }else{
                                         // echo "!!!!!!----------------------------нет такой комбинации слов `" . $minus_res_if_preg . "` ----------------------------!!!!!!";
                                       }

                                     }




                                     $res_cccc1=rtrim($cccc,'- ');
                                   $res_cccc=rtrim($res_cccc1,' ');
                                   $keys=$res_cccc;
                                   //не трогать без МЕНЯ ЭТО РАБОТАЕТ!!!!!!

                                  // p($keys);
                                  // p($perececheniya);
                            //позиции
                                $sql_seo_angara='SELECT DISTINCT * FROM ' . $table_seo_angara . ' WHERE keyword="' . $keyword['keywords'] . '"';
                                $seo_angara_arr=select_sql_db4($sql_seo_angara);
                                $seo_angara=$seo_angara_arr[0]['position'];
                                $seo_url_angara=$seo_angara_arr[0]['url'];
                                if ($seo_angara>=5 OR $seo_angara=='-' OR empty($seo_angara)) {
                                  $color_seo_angara="#dc2020";

                                }else{
                                  $color_seo_angara="#256d20";

                                }
                                $sql_seo_zp='SELECT DISTINCT * FROM ' . $table_seo_zapchastiporter . ' WHERE keyword="' . $keyword['keywords'] . '"';
                                $seo_zp_arr=select_sql_db4($sql_seo_zp);
                                $seo_zp=$seo_zp_arr[0]['position'];
                                $seo_url_zp=$seo_zp_arr[0]['url'];
                                if ($seo_zp>5 OR $seo_zp=='-' OR empty($seo_zp)) {
                                  $color_seo_zp="#dc2020";
                                }else{
                                  $color_seo_zp="#256d20";
                                }
                                ?>

                                <tr>
                                  <td></td>
                                  <td></td>

                                  <?php
                            //ОБЬЯВЛЕНИЕ
                                  $str_repl_keyw=str_ireplace('hyundai porter','Porter',$keyword['keywords']);
                                  $str_repl_keyw=str_ireplace('porter hyundai','Porter',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace('хендай портер','Портер',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace('портер хендай','Портер',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace('хундай портер','Портер',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace('портер хундай','Портер',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace('hyundai','Hyundai',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace('porter','Porter',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace('хендай','Хендай',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace('портер','Портер',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace('гбц ','ГБЦ ',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace('тнвд ','ТНВД ',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace('тагаз ','Тагаз ',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace('егр ','ЕГР ',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace(' гбц',' ГБЦ',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace(' тнвд',' ТНВД',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace(' тагаз',' Тагаз',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace(' егр',' ЕГР',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace(' москв',' Москв',$str_repl_keyw);

                                  $title_obyavlenia1=ucfirst($str_repl_keyw);
                                            $title_words=explode(' ',$title_obyavlenia1);
                                            $max=count($title_words)-1;
                                            $words_ar='';
                                            foreach ($title_words as $key => $word1) {
                                              if($key==$max){

                                              }else{
                                                $words_ar .=$word1 . ' ';
                                              }
                                            }
                                          $title_obyavlenia2=ucfirst(rtrim($words_ar,' '));
                                          $title_obyavlenia2=preg_replace("/(.*)([ ])(на|для|и)$/i","$1",$title_obyavlenia2);
                                            $title_words2=explode(' ',$title_obyavlenia2);

                                            $max2=count($title_words2)-1;

                                            $words_ar2='';
                                            foreach ($title_words2 as $key => $word2) {
                                              if($key==$max2){

                                              }else{
                                                $words_ar2 .=$word2 . ' ';
                                              }
                                            }
                                          $title_obyavlenia3=ucfirst(rtrim($words_ar2,' '));
                                          $title_obyavlenia3=preg_replace("/(.*)([ ])(на|для|и)$/i","$1",$title_obyavlenia3);
                                            $title_words3=explode(' ',$title_obyavlenia3);
                                            $max3=count($title_words3)-1;
                                            $words_ar3='';
                                            foreach ($title_words3 as $key => $word3) {
                                              if($key==$max3){

                                              }else{
                                                $words_ar3 .=$word3 . ' ';
                                              }
                                            }
                                          $title_obyavlenia4=ucfirst(rtrim($words_ar3,' '));
                                          $title_obyavlenia4=preg_replace("/(.*)([ ])(на|для|и)$/i","$1",$title_obyavlenia4);
                                            if (iconv_strlen($title_obyavlenia1)<=$title1_count) {
                                              $title_obyavlenia=$title_obyavlenia1;
                                            }elseif(iconv_strlen($title_obyavlenia2)<=$title1_count){
                                              $title_obyavlenia=$title_obyavlenia2;
                                            }elseif(iconv_strlen($title_obyavlenia3)<=$title1_count){
                                              $title_obyavlenia=$title_obyavlenia3;
                                            }elseif(iconv_strlen($title_obyavlenia4)<=$title1_count){
                                              $title_obyavlenia=$title_obyavlenia4;
                                            }
                                            $title_obyavlenia=preg_replace("/(.*)([ ])(цена|цены)$/i","$1",$title_obyavlenia);
                                            $title_obyavlenia=preg_replace("/(цена|цены)([ ])(.*)/i","$3",$title_obyavlenia);
                                            $first=mb_strtoupper(mb_substr($title_obyavlenia, 0, 1));
                                            $all=mb_substr($title_obyavlenia, 1);
                                            $title_obyavlenia=$first . $all;


                                  $title2_obyavlenia="В наличии!";
                                  $str_repl_keyw=preg_replace("/(.*)([ ])(цена)$/i","$1",$str_repl_keyw);
                                  $str_repl_keyw=preg_replace("/(цена)([ ])(.*)/i","$3",$str_repl_keyw);
                                  $text_obyavlenia1=ucfirst($str_repl_keyw . " – Недорого. Доставка 290р + 100% гарантия возврата!");
                                  $text_obyavlenia2=ucfirst($str_repl_keyw . " – Недорого. Доставка 290р + 100% гарантия возврата");
                                  $text_obyavlenia3=ucfirst($str_repl_keyw . " – Доставка 290р + 100% гарантия возврата");
                                  $text_obyavlenia4=ucfirst("97% запчастей на Портер в наличии – Доставка 290р + 100% гарантия возврата!");
                                          if (iconv_strlen($text_obyavlenia1)<=$text_count) {
                                            $text_obyavlenia=ucfirst($text_obyavlenia1);
                                          }elseif(iconv_strlen($text_obyavlenia2)<=$text_count){
                                            $text_obyavlenia=ucfirst($text_obyavlenia2);
                                          }elseif(iconv_strlen($text_obyavlenia3)<=$text_count){
                                            $text_obyavlenia=ucfirst($text_obyavlenia3);
                                          }elseif(iconv_strlen($text_obyavlenia4)<=$text_count){
                                            $text_obyavlenia=ucfirst($text_obyavlenia4);
                                          }
                                          $first=mb_strtoupper(mb_substr($text_obyavlenia, 0, 1));
                                          $all=mb_substr($text_obyavlenia, 1);
                                          $text_obyavlenia=$first . $all;
                                  $patch1="https://zapchastiporter.ru";
                                  $patch21=ucfirst($category['category_name']);
                                          $patch21_words=explode(' ',$patch21);
                                          $max=count($patch21_words)-1;
                                          $words_ar='';
                                          foreach ($patch21_words as $key => $word) {
                                            if($key==$max){

                                            }else{
                                              $words_ar .=$word . ' ';
                                            }
                                          }
                                          $patch22=ucfirst(rtrim($words_ar,' '));
                                            $patch22_words=explode(' ',$patch22);
                                            $max2=count($patch22_words)-1;
                                            $words_ar2='';
                                            foreach ($patch22_words as $key => $word2) {
                                              if($key==$max2){

                                              }else{
                                                $words_ar2 .=$word2 . ' ';
                                              }
                                            }
                                          $patch23=ucfirst(rtrim($words_ar2,' '));
                                          $name_cat_zp=str_ireplace(" для "," ",$name_cat_zp);
                                          $name_cat_zp=str_ireplace(" на "," ",$name_cat_zp);
                                          $name_cat_zp=str_ireplace(" Хендай "," ",$name_cat_zp);
                                          $name_cat_zp=str_ireplace("hyundai"," ",$name_cat_zp);
                                          $name_cat_zp=str_ireplace("porter"," ",$name_cat_zp);
                                          $name_cat_zp2=str_ireplace(" портер "," ",$name_cat_zp);
                                          $name_cat_zp2=preg_replace("/(.*)([ ])(портер|Портер)$/i","$1",$name_cat_zp2);
                                          $name_cat_zp3=preg_replace("/(.*)([ ])(.*)$/i","$1",$name_cat_zp2);
                                          if (iconv_strlen($patch21)<=$patch2_count) {
                                            $patch2=ucfirst($patch21);
                                          }elseif (iconv_strlen($patch22)<=$patch2_count) {
                                            $patch2=ucfirst($patch22);
                                          }elseif (iconv_strlen($patch23)<=$patch2_count) {
                                            $patch2=ucfirst($patch23);
                                          }elseif(iconv_strlen($name_cat_zp)<=$patch2_count){
                                            $patch2=$name_cat_zp;
                                          }elseif(iconv_strlen($name_cat_zp2)<=$patch2_count){
                                            $patch2=$name_cat_zp2;
                                          }else{
                                            $patch2=$name_cat_zp3;
                                          }
                                          $patch2=preg_replace("/(.*)([ ])(портер|Портер|на|для|и|.)$/i","$1",trim($patch2,' '));
                                          $patch2=preg_replace("/(.*)([ ])(портер|Портер)$/i","$1",$patch2);

                                          $first=mb_strtoupper(mb_substr($patch2, 0, 1));
                                          $all=mb_substr($patch2, 1);
                                          $patch2=$first . $all;
                                          $patch2=str_ireplace(" ","-",$patch2);
                                          $patch2=str_ireplace(",","-",$patch2);
                                          $patch2=str_ireplace("--","-",$patch2);
                                          $patch2=str_ireplace("--","-",$patch2);
                                          $patch2=str_ireplace("--","-",$patch2);
                                          $patch2=trim($patch2,"-");
                                  $title_obyavlenia_simv=iconv_strlen($title_obyavlenia);
                                  $title2_obyavlenia_simv=iconv_strlen($title2_obyavlenia);
                                  $text_obyavlenia_simv=iconv_strlen($text_obyavlenia);
                                  $patch2_simv=iconv_strlen($patch2);
                                  if (isset($title_obyavlenia_simv)) {
                                  if ($title_obyavlenia_simv>$title1_count) {
                                    $title_simv_color="red";
                                  }else{
                                    $title_simv_color="green";
                                  }
                                  }
                                  if (isset($title2_obyavlenia_simv)) {
                                  if ($title2_obyavlenia_simv>$title2_count) {
                                    $title2_simv_color="red";
                                  }else{
                                    $title2_simv_color="green";
                                  }
                                  }
                                  if (isset($text_obyavlenia_simv)) {
                                  if ($text_obyavlenia_simv>$text_count) {
                                    $text_simv_color="red";
                                  }else{
                                    $text_simv_color="green";
                                  }
                                  }
                                  if (isset($text_obyavlenia_simv)) {
                                  if ($patch2_simv>$patch2_count) {
                                    $patch2_simv_color="red";
                                  }else{
                                    $patch2_simv_color="green";
                                  }
                                  }


                                  ?>
                                  <td>
                                    <h2 class="zagolovok"><b style="color:<?=$title_simv_color?>">[<?=$title_obyavlenia_simv?>]</b> – <b style="color:<?=$title2_simv_color?>">[<?=$title2_obyavlenia_simv?>]</b></h2>
                                    <p style=" margin: 0;    padding: 0;font-size: 14px;font-family: Arial,Helvetica,sans-serif;white-space: nowrap;color:<?=$patch2_simv_color?>">[<?=$patch2_simv?>]</p>
                                    <p class="description" style="color:<?=$text_simv_color?>">[<?=$text_obyavlenia_simv?>]</p>
                                  </td>
                                  <td style="width:536px;overflow:break-word;line-height: 17px">
                                    <h2 class="zagolovok"><a><?=$title_obyavlenia?> – <?=$title2_obyavlenia?></a></h2>
                                    <p style=" margin: 0;    padding: 0;font-size: 14px;font-family: Arial,Helvetica,sans-serif;color:#070;    white-space: nowrap;">
                                      <a style="color:#070;    list-style: none;text-decoration: none" href="<?=$url_obyavlenia?>"><b><?=$patch1?></b> › <text class="url2"><?=$patch2?></text></a>
                                    </p>
                                    <p class="description"><?=$text_obyavlenia?></p>
                                    <!-- <p class="description"><?=$str_repl_keyw?> – Недорого, оригинал и аналог. Доставка по Москве и России +100% гарантия возврата!</p> -->
                                  </td>

                                  <td>


                                    <?=$keyword['keywords']?>
                                    <?=$keys?>


                                    <form action="" method="get">
                                      <input hidden name="do" value="<?=$_GET['do']?>"></input>
                                      <input hidden name="table" value="<?=$_GET['table']?>"></input>
                                      <input type="radio" name="danet_f" value="1"></input>
                                      <input hidden name="keyword" value="<?=$keyword['keywords']?>"></input>
                                      <input hidden name="gr_id" value="<?=$id?>"></input>


                                      <input type="submit" value="удалить категорию!">
                                    </form>
                                    <?php if (isset($_GET['danet_f']) AND $_GET['danet_f']==1 AND $_GET['keyword']==$keyword['keywords'] AND $_GET['gr_id']==$id) {
                                      $sql_danet='UPDATE ' . $table_keywords . ' SET danet=1 WHERE keywords="' . $keyword['keywords'] . '" AND group_id=' . $id;
                                      echo '<p style="background-color:#ffa8a9">удалено</p>';
                                      update_sql_db4($sql_danet);
                                    }?>








                                    <?php
                                    $sql='SELECT DISTINCT * FROM ' . $table_kernel_direct . ' WHERE keyword="' . $keyword['keywords'] . '"';
                                    $metriki=select_sql_db4($sql);

                                    ?>

                                  </td>
                            <!--метрики -->
                                  <td>
                                    <div style="display:flex;justify-content:space-between">
                                      <?php if (isset($metriki[0]['zaprosi']) AND $metriki[0]['zaprosi']!=0) {
                                        $cpc=str_ireplace(',','.',$metriki[0]['click_price_final']);
                                        $cpc1=str_ireplace(',','.',$metriki[0]['click_price_final']);
                                        $cpc9=str_ireplace(',','.',$metriki[0]['click_price_62']);
                                        $zaprosi=$metriki[0]['zaprosi'];
                                        $zaprosi1=$metriki[0]['zaprosi'];
                                        $zaprosi2=$metriki[0]['zaprosi'];
                                        $pokazi=$metriki[0]['pokaz_100'];
                                        $pokazi1=$metriki[0]['pokaz_100'];
                                        $pokazi2=$metriki[0]['pokaz_100'];
                                        $marja=$zaprosi*$categorys[0]['conversion_sites']*$categorys[0]['conversion']/100*$categorys[0]['sr_prib_good'];
                                        $marja1=$zaprosi*$categorys[0]['conversion_sites']*$categorys[0]['conversion']/100*$categorys[0]['sr_prib_good'];
                                        $marja2=$zaprosi2*$categorys[0]['conversion_sites']*$categorys[0]['conversion']/100*$categorys[0]['sr_prib_good'];
                                        $zatrati=$zaprosi*$cpc;
                                        $zatrati1=$zaprosi*$cpc;
                                        $zatrati2=$zaprosi2*$cpc9;
                                        // p($metriki[0]['zaprosi']*$categorys[0]['conversion_sites']);
                                        $pribil=number_format($marja-$zatrati,2,'.','');
                                        $pribil1=number_format($marja-$zatrati,2,'.','');
                                        $pribil2=number_format($marja2-$zatrati2,2,'.','');
                                        $max_cpc=number_format($marja/$zaprosi,0,'.','');

                                        if ($zatrati<=0) {
                                          $roi=0;
                                          $roi2=0;
                                        }else{
                                        $roi=number_format($marja/$zatrati*100,2,'.','');
                                        $roi2=number_format($marja/$zatrati2*100,2,'.','');
                                        }
                                        $obiem=75;
                                          if ($pribil<=0) {
                                            // p($cpc);
                                            $cpc=$cpc9;
                                            $zatrati=$zatrati2;
                                            $pribil=$pribil2;
                                            $roi=$roi2;
                                            $marja=$marja2;
                                            $zaprosi=$zaprosi2;
                                            $pokazi=$pokazi2;
                                            $obiem=9;
                                          }
                                        $pribil_all1 .=$pribil1 . ';';
                                        $marja_all1 .=$marja1 . ';';
                                        $zaprosi_all1 .=$zaprosi1 . ';';
                                        $pokazi_all1 .=$pokazi1 . ';';
                                        $cpc_all1 .=$cpc1*$zaprosi1 . ';';
                                        $zatrati_all1 .=$zatrati1 . ';';
                                                if ($seo_angara=='-' OR empty($seo_angara)) {

                                                }else{
                                                    $sr_seo_angara .=$seo_angara . ';';
                                                }
                                                if ($seo_zp=='-' OR empty($seo_zp)) {

                                                }else{
                                                    $sr_seo_zp .=$seo_zp . ';';
                                                }
                                        if ($pribil<=0) {

                                          $color='#dc2020';
                                        }else{
                                                if ($seo_angara=='-' OR empty($seo_angara)) {

                                                }else{
                                                    $sr_seo_angara_prib .=$seo_angara . ';';
                                                }
                                                if ($seo_zp=='-' OR empty($seo_zp)) {

                                                }else{
                                                    $sr_seo_zp_prib .=$seo_zp . ';';
                                                }
                                          $pribil_all .=$pribil . ';';
                                          $marja_all .=$marja . ';';
                                          $zaprosi_all .=$metriki[0]['zaprosi'] . ';';
                                          $pokazi_all .=$metriki[0]['pokaz_100'] . ';';
                                          $cpc_all .=$cpc*$metriki[0]['zaprosi'] . ';';
                                          $zatrati_all .=$zatrati . ';';
                                          $color='#256d20';

                                        }
                                        ?>

                                        <div style="min-width:120px">Зап: <?=$metriki[0]['zaprosi']?>|<?=$zaprosi?><br>Пок: <?=$metriki[0]['pokaz_100']?> | <?=$pokazi?></div>
                                        <div style="min-width:150px">CPC: <?=$cpc?>р(<?=$metriki[0]['click_price_final']?>р)</div>
                                        <div style="min-width:150px">max-CPC: <?=$max_cpc?>р</div>
                                        <div style="color:<?=$color?>;min-width:120px">Приб: <?=$pribil?>р</div>
                                        <div style="min-width:120px">ROI: <?=$roi?>%</div>
                                        <div style="min-width:75px"><?=$obiem?>%</div>
                                      <?php }else{
                                        $cpc=0;
                                        $zatrati=0;
                                        $pribil=0;
                                        $roi=0;
                                        $marja=0;
                                        $zaprosi=0;
                                        $pokazi=0;
                                        $obiem=9;
                                        $max_cpc=0;

                                        $cpc1=0;
                                        $zatrati1=0;
                                        $pribil1=0;
                                        $roi1=0;
                                        $marja1=0;
                                        $zaprosi1=0;
                                        $pokazi1=0;
                                        $obiem1=75;

                                    $pribil_all1 .=$pribil1 . ';';
                                    $marja_all1 .=$marja1 . ';';
                                    $zaprosi_all1 .=$zaprosi1 . ';';
                                    $pokazi_all1 .=$pokazi1 . ';';
                                    $cpc_all1 .=$cpc1*$zaprosi1 . ';';
                                    $zatrati_all1 .=$zatrati1 . ';';
                                      $pribil_all .=$pribil . ';';
                                      $marja_all .=$marja . ';';
                                      $zaprosi_all .=$zaprosi . ';';
                                      $pokazi_all .=$pokazi . ';';
                                      $cpc_all .=$cpc*$zaprosi . ';';
                                      $zatrati_all .=$zatrati . ';';
                                        ?>
                                        <div style="min-width:120px">Зап: 0 || Пок: ~~ </div><div>CPC: ~~ р</div>
                                        <div style="min-width:150px">CPC: -р)</div>
                                        <div style="min-width:150px">max-CPC: -р</div>
                                        <div style="color:<?=$color?>;min-width:120px">Приб: -р</div>
                                        <div style="min-width:120px">ROI: -%</div>
                                        <div style="min-width:75px"><?=$obiem?>%</div>

                                      <?php }?>
                                    </div>
                            <!--SEO показатели-->
                                    <?php
                                    if ($seo_zp=="-" OR $seo_zp=="" OR $seo_zp==0) {
                                      $seo_zp="100";
                                    }
                                    if ($seo_angara=="-" OR $seo_angara=="" OR $seo_angara==0) {
                                      $seo_angara="100";
                                    }
                                    ?>
                                    <div>
                                      <div style="display:flex;justify-content:space-between;border-top:1px #ccc solid;margin-top:2px">
                                        <div style="width:75px">
                                          Angara77
                                        </div>
                                        <div style="width:300px;font-size:12px;overflow:hidden">
                                          <?php if (isset($seo_url_angara) AND $seo_url_angara!='-') {
                                          ?>
                                          <a href="http://angara77.com<?=$seo_url_angara?>" target="_blank"><?=$seo_url_angara?></a>
                                        <?php }else{?>
                                          <b style="color:#dc2020">НЕТ URL</b>
                                        <?php } ?>
                                        </div>
                                        <div>
                                          <div style="width:75px">SEO: <b style="color:<?=$color_seo_angara?>"><?=$seo_angara?></b></div>
                                        </div>
                                      </div>
                                      <div style="display:flex;justify-content:space-between;border-top:1px #ccc solid;margin-top:2px">
                                        <div style="width:75px">
                                          zapporter
                                        </div>
                                        <div style="width:300px;font-size:12px;overflow:hidden">
                                          <?php if (isset($seo_url_zp) AND $seo_url_zp!='-') {
                                          ?>
                                          <a href="https://zapchastiporter.ru<?=$seo_url_zp?>" target="_blank"><?=$seo_url_zp?></a>
                                        <?php }else{?>
                                          <b style="color:#dc2020">НЕТ URL</b>
                                        <?php } ?>
                                        </div>
                                        <div>
                                          <div  style="width:75px">SEO: <b style="color:<?=$color_seo_zp?>"><?=$seo_zp?></b></div>
                                        </div>
                                      </div>
                                    </div>
                                  </td>
                                  <?php
                                  $cpc=number_format($cpc,2,'.','');
                                  if ($roi=="inf" OR $roi=="") {
                                    $roi=0.00;
                                  }
                                  $roi=number_format($roi,2,'.','');
                                  if ($zaprosi=="" OR $zaprosi=="-" OR $zaprosi=="~") {
                                    $zaprosi=0;
                                  }
                                  if ($pokazi=="" OR $pokazi=="-" OR $pokazi=="~") {
                                    $pokazi=0;
                                  }


                                  if ($zaprosi<10) {
                                    $rk=10;
                                  }else{
                                    if ($seo_zp<=5 OR $seo_angara<=5) {
                                      $cpc=$cpc9;
                                      $zatrati=$zatrati2;
                                      $pribil=$pribil2;
                                      $roi=$roi2;
                                      $marja=$marja2;
                                      $zaprosi=$zaprosi2;
                                      $pokazi=$pokazi2;
                                      $obiem=9;
                                      if ($pribil>0) {
                                        $rk=1;
                                      }else{
                                        $rk=2;
                                      }

                                    }else{
                                      if ($obiem==9) {
                                        if ($pribil>0) {
                                          $rk=4;
                                        }else{
                                          $rk=5;
                                        }
                                      }else{
                                        $rk=3;
                                      }
                                    }
                                  }
                                  ?>
                            <!--группы где пересекаются слова -->
                                    <?php
                                    $sql='SELECT DISTINCT category_id,category_name,subcat_id,subcat_name,group_id,group_name FROM ' . $table_keywords . ' WHERE keywords="' . $keyword['keywords'] . '" AND ' . $razdel . '!=' . $id . ' AND danet=0';
                                    $no_group=select_sql_db4($sql);
                                    if (isset($no_group[0]['category_id'])) {
                                    ?>
                                    <td style="background-color:#ffa8a9">
                                    <?php foreach ($no_group as $key => $no_gr){?>

                                      <form action="" method="get">
                                        <a href="/reklama/?do=2&table=<?=$no_gr['category_id']?>"><?=$no_gr['category_name']?></a> / <a href="/reklama/?do=2&table=<?=$no_gr['subcat_id']?>"><?=$no_gr['subcat_name']?></a> / <a href="/reklama/?do=2&table=<?=$no_gr['group_id']?>"><?=$no_gr['group_name']?></a>
                                        <input hidden name="do" value="<?=$_GET['do']?>"></input>
                                        <input hidden name="table" value="<?=$_GET['table']?>"></input>
                                        <input type="radio" checked name="danet" value="1"></input>
                                        <input hidden name="keyword" value="<?=$keyword['keywords']?>"></input>
                                        <input hidden name="no_gr" value="<?=$no_gr['group_id']?>"></input>


                                        <input type="submit" value="удалить категорию!">
                                      </form>
                                      <?php if (isset($_GET['danet']) AND $_GET['danet']==1 AND $_GET['keyword']==$keyword['keywords'] AND $_GET['no_gr']==$no_gr['group_id']) {
                                        $sql_danet='UPDATE ' . $table_keywords . ' SET danet=1 WHERE keywords="' . $keyword['keywords'] . '" AND group_id=' . $no_gr['group_id'];
                                        echo "удалено";
                                        update_sql_db4($sql_danet);
                                      }?>
                                    <?php } ?>
                                    <p>RK = <?=$rk?></p>
                                    </td>
                                  <?php }else{?>
                                    <td><p>RK = <?=$rk?></p></td>
                                  <?php }


                            //НОВАЯ ВСТАВКА





                                  if(isset($metriki[0]['zaprosi']) AND $metriki[0]['zaprosi']==""){
                                    $metriki[0]['zaprosi']=0;
                                  }else{
                                    $metriki[0]['zaprosi']=0;
                                  }

                                  if (isset($metriki[0]['pokaz_100']) AND $metriki[0]['pokaz_100']=="") {
                                    $metriki[0]['pokaz_100']=0;
                                  }else{
                                    $metriki[0]['pokaz_100']=0;
                                  }

                                  $sql_checkeee='SELECT * FROM reklama_blocked_ids WHERE keyword IS NOT NULL';
                                  $checkeee=select_sql_db4($sql_checkeee);
                                  $arr_checkeee="";
                                    foreach ($checkeee as $key => $che) {
                                      $arr_checkeee .=$che['keyword'] . ';';
                                    }

                                    $arr_res_checkeee=explode(';',rtrim($arr_checkeee,';'));
                                  if (in_array($keyword['keywords'],$arr_res_checkeee)) {

                                  }else{
                                    //если вставляем то заменить точки в цифрах на запятые
                                    $cpc=str_ireplace(".",",",$cpc);
                                    $roi=str_ireplace(".",",",$roi);

                                  $table_reklama_done='reklama_done';
                                  $sql_check_done='SELECT * FROM ' . $table_reklama_done . '
                                  WHERE cat_id="' . $id . '" AND keyword="' . $keyword['keywords'] . '"';
                                  $check_reklama_done=select_sql_db4($sql_check_done);
                                  // p($sql_check_done);
                                  if (isset($check_reklama_done[0]['keyword'])) {
                                    if ($url_obyavlenia==$check_reklama_done[0]['url']
                                    AND $check_reklama_done[0]['minus_keyword']==$keys
                                     AND $title_obyavlenia==$check_reklama_done[0]['title']
                                       AND $title_obyavlenia_simv==$check_reklama_done[0]['title_count']
                                        AND $title2_obyavlenia==$check_reklama_done[0]['title2']
                                          AND $check_reklama_done[0]['title2_count']==$title2_obyavlenia_simv
                                           AND $check_reklama_done[0]['text']==$text_obyavlenia
                                             AND $check_reklama_done[0]['text_count']==$text_obyavlenia_simv
                                              AND $check_reklama_done[0]['patch']==$patch2
                                               AND $check_reklama_done[0]['patch_count']==$patch2_simv
                                                 AND $check_reklama_done[0]['zapros']==$zaprosi
                                                   AND $check_reklama_done[0]['pokaz']==$pokazi
                                                    AND $check_reklama_done[0]['cpc']==$cpc
                                                     AND $check_reklama_done[0]['max_cpc']==$max_cpc
                                                      AND $check_reklama_done[0]['roi']==$roi
                                                       AND $check_reklama_done[0]['id_rk']==$rk) {
                                      echo "уже есть";
                                    }else{
                                      $sql_upd='UPDATE ' . $table_reklama_done . '
                                      SET cat_id="' . $id . '" ,
                                      keyword="' . $keyword['keywords'] . '",
                                      minus_keyword="' . $keys . '",
                                      url="' . $url_obyavlenia . '",
                                      title="' . $title_obyavlenia . '",
                                      title_count="' . $title_obyavlenia_simv . '",
                                      title2="' . $title2_obyavlenia . '",
                                      title2_count="' . $title2_obyavlenia_simv . '",
                                      text="' . $text_obyavlenia . '",
                                      text_count="' . $text_obyavlenia_simv . '",
                                      patch="' . $patch2 . '",
                                      patch_count="' . $patch2_simv . '",
                                      zapros="' . $zaprosi . '",
                                      pokaz="' . $pokazi . '",
                                      cpc="' . $cpc . '",
                                      max_cpc="' . $max_cpc . '",
                                      roi="' . $roi . '",
                                      seo_angara="' . $seo_angara . '",
                                      seo_zp="' . $seo_zp . '",
                                      url_seo_angara="' . $seo_url_angara . '",
                                      url_seo_zp="' . $seo_url_zp . '",
                                      id_rk="' . $rk . '"
                                      WHERE cat_id="' . $check_reklama_done[0]['cat_id'] . '" AND keyword="' . $check_reklama_done[0]['keyword'] . '"';
                                      p($sql_upd);
                                      update_sql_db4($sql_upd);
                                    }
                                  }else{
                                    $sql='INSERT INTO ' . $table_reklama_done . '(cat_id,
                                    keyword,
                                    minus_keyword,
                                    url,
                                    title,
                                    title_count,
                                    title2,
                                    title2_count,
                                    text,
                                    text_count,
                                    patch,
                                    patch_count,
                                    zapros,
                                    pokaz,
                                    cpc,
                                    max_cpc,
                                    roi,
                                    seo_angara,
                                    seo_zp,
                                    url_seo_angara,
                                    url_seo_zp,
                                    id_rk)
                                     VALUES ("' . $id . '",
                                     "' . $keyword['keywords'] . '",
                                     "' . $keys . '",
                                     "' . $url_obyavlenia . '",
                                     "' . $title_obyavlenia . '",
                                     "' . $title_obyavlenia_simv . '",
                                     "' . $title2_obyavlenia . '",
                                     "' . $title2_obyavlenia_simv . '",
                                     "' . $text_obyavlenia . '",
                                     "' . $text_obyavlenia_simv . '",
                                     "' . $patch2 . '",
                                     "' . $patch2_simv . '",
                                     "' . $zaprosi . '",
                                     "' . $pokazi . '",
                                     "' . $cpc . '",
                                     "' . $max_cpc . '",
                                     "' . $roi . '",
                                     "' . $seo_angara . '",
                                     "' . $seo_zp . '",
                                     "' . $seo_url_angara . '",
                                     "' . $seo_url_zp . '",
                                     "' . $rk . '")';
                                     p($sql);
                                     update_sql_db4($sql);
                                  }
                                  }



                                }
                                if (isset($title_obyavlenia_simv)) {
                                if ($title_obyavlenia_simv>$title1_count) {
                                  $title_simv_color="red";
                                }else{
                                  $title_simv_color="green";
                                }
                                }
                                if (isset($title2_obyavlenia_simv)) {
                                if ($title2_obyavlenia_simv>$title2_count) {
                                  $title2_simv_color="red";
                                }else{
                                  $title2_simv_color="green";
                                }
                                }
                                if (isset($text_obyavlenia_simv)) {
                                if ($text_obyavlenia_simv>$text_count) {
                                  $text_simv_color="red";
                                }else{
                                  $text_simv_color="green";
                                }
                                }
                                if (isset($text_obyavlenia_simv)) {
                                if ($patch2_simv>$patch2_count) {
                                  $patch2_simv_color="red";
                                }else{
                                  $patch2_simv_color="green";
                                }
                                }




                            //по плюсовым
                                $res1=rtrim($pribil_all,';');
                              $summ_prib=number_format(array_sum(explode(';',$res1)),0,'.','');
                                $res2=rtrim($zaprosi_all,';');
                              $summ_zapros=array_sum(explode(';',$res2));
                                $res3=rtrim($pokazi_all,';');
                              $summ_pokaz=array_sum(explode(';',$res3));
                                $res5=rtrim($marja_all,';');
                              $summ_marja=array_sum(explode(';',$res5));
                                $res6=rtrim($zatrati_all,';');
                              $summ_zatrati=array_sum(explode(';',$res6));
                                $res4=rtrim($cpc_all,';');
                                $arr_cpc=explode(';',$res4);
                                $count_cpc=count($arr_cpc);
                                $summ_cpc=array_sum($arr_cpc);

                              if ($summ_zatrati==0 OR $summ_marja==0) {
                                $roi_all=0;
                                $sredn_cpc=0;
                              }else{
                                $sredn_cpc=number_format($summ_cpc/$summ_zapros,2);
                                $roi_all=number_format($summ_marja/$summ_zatrati*100,2,'.','');
                              }
                              if ($summ_prib<0) {
                                $color_summ_prib='#dc2020';
                              }else{
                                $color_summ_prib='#256d20';
                              }


                            //общий подсчет
                              $res11=rtrim($pribil_all1,';');
                            $summ_prib1=number_format(array_sum(explode(';',$res11)),0,'.','');
                              $res21=rtrim($zaprosi_all1,';');
                            $summ_zapros1=array_sum(explode(';',$res21));
                              $res31=rtrim($pokazi_all1,';');
                            $summ_pokaz1=array_sum(explode(';',$res31));
                              $res51=rtrim($marja_all1,';');
                            $summ_marja1=array_sum(explode(';',$res51));
                              $res61=rtrim($zatrati_all1,';');
                            $summ_zatrati1=array_sum(explode(';',$res61));
                              $res41=rtrim($cpc_all1,';');
                              $arr_cpc1=explode(';',$res41);
                              $count_cpc1=count($arr_cpc1);
                              $summ_cpc1=array_sum($arr_cpc1);

                            //SEO подсчет
                              $arr_seo_angara_prib=explode(';',rtrim($sr_seo_angara_prib,';'));
                              $srz_seo_ang_prib=number_format(array_sum($arr_seo_angara_prib)/count($arr_seo_angara_prib),2,'.','');
                              if ($srz_seo_ang_prib>5) {
                                $color_sr_seo_ang_prib='red';
                              }else{
                                $color_sr_seo_ang_prib='green';
                              }
                              $arr_seo_angara_pr10=0;
                              $arr_seo_angara_pr5=0;
                              $arr_seo_angara_pr3=0;
                              $arr_seo_angara_pr50=0;
                              foreach ($arr_seo_angara_prib as $key => $arr_seo_angara_pr) {
                                if ($arr_seo_angara_pr<=10) {
                                  $arr_seo_angara_pr10++;
                                }else{
                                  $arr_seo_angara_pr50++;
                                }
                                if ($arr_seo_angara_pr<=5) {
                                  $arr_seo_angara_pr5++;
                                }
                                if ($arr_seo_angara_pr<=3) {
                                  $arr_seo_angara_pr3++;
                                }
                              }

                              $arr_seo_zp_prib=explode(';',rtrim($sr_seo_zp_prib,';'));
                              $srz_seo_zp_prib=number_format(array_sum($arr_seo_zp_prib)/count($arr_seo_zp_prib),2,'.','');
                              if ($srz_seo_zp_prib>5) {
                                $color_sr_seo_zp_prib='red';
                              }else{
                                $color_sr_seo_zp_prib='green';
                              }
                              $arr_seo_zp_pr50=0;
                              $arr_seo_zp_pr10=0;
                              $arr_seo_zp_pr5=0;
                              $arr_seo_zp_pr3=0;
                              foreach ($arr_seo_zp_prib as $key => $arr_seo_zp_pr) {
                                if ($arr_seo_zp_pr<=10) {
                                  $arr_seo_zp_pr10++;
                                }else{
                                  $arr_seo_zp_pr50++;
                                }
                                if ($arr_seo_zp_pr<=5) {
                                  $arr_seo_zp_pr5++;
                                }
                                if ($arr_seo_zp_pr<=3) {
                                  $arr_seo_zp_pr3++;
                                }
                              }

                              $arr_seo_angara=explode(';',rtrim($sr_seo_angara,';'));
                              $srz_seo_ang=number_format(array_sum($arr_seo_angara)/count($arr_seo_angara),2,'.','');
                              if ($srz_seo_ang>5) {
                                $color_sr_seo_ang='red';
                              }else{
                                $color_sr_seo_ang='green';
                              }
                              $arr_seo_angara_50=0;
                              $arr_seo_angara_10=0;
                              $arr_seo_angara_5=0;
                              $arr_seo_angara_3=0;
                              foreach ($arr_seo_angara as $key => $arr_seo_angara_) {
                                if ($arr_seo_angara_<=10) {
                                  $arr_seo_angara_10++;
                                }else{
                                  $arr_seo_angara_50++;
                                }
                                if ($arr_seo_angara_<=5) {
                                  $arr_seo_angara_5++;
                                }
                                if ($arr_seo_angara_<=3) {
                                  $arr_seo_angara_3++;
                                }
                              }

                              $arr_seo_zp=explode(';',rtrim($sr_seo_zp,';'));
                              $srz_seo_zp=number_format(array_sum($arr_seo_zp)/count($arr_seo_zp),2,'.','');
                              if ($srz_seo_zp>5) {
                                $color_sr_seo_zp='red';
                              }else{
                                $color_sr_seo_zp='green';
                              }
                              $arr_seo_zp_50=0;
                              $arr_seo_zp_10=0;
                              $arr_seo_zp_5=0;
                              $arr_seo_zp_3=0;
                              foreach ($arr_seo_zp as $key => $arr_seo_zp_) {
                                if ($arr_seo_zp_<=10) {
                                  $arr_seo_zp_10++;
                                }else{
                                  $arr_seo_zp_50++;
                                }
                                if ($arr_seo_zp_<=5) {
                                  $arr_seo_zp_5++;
                                }
                                if ($arr_seo_zp_<=3) {
                                  $arr_seo_zp_3++;
                                }
                              }
                            // if ($seo_angara=='-' OR empty($seo_angara)) {
                            //
                            // }else{
                            //     $sr_seo_angara_prib .=$seo_angara . ';';
                            // }
                            // if ($seo_zp=='-' OR empty($seo_zp)) {
                            //
                            // }else{
                            //     $sr_seo_zp_prib .=$seo_zp . ';';
                            // }

                            if ($summ_zatrati1==0 OR $summ_marja1==0) {
                              $roi_all1=0;
                              $sredn_cpc1=0;
                            }else{
                              $roi_all1=number_format($summ_marja1/$summ_zatrati1*100,2,'.','');
                              $sredn_cpc1=number_format($summ_cpc1/$summ_zapros1,2,'.','');
                            }
                            if ($summ_prib1<0) {
                              $color_summ_prib1='#dc2020';
                            }else{
                              $color_summ_prib1='#256d20';
                            }
                            ?>
                            <tr>
                              <td></td>
                              <td></td>
                              <td></td>
                            </tr>
                            <tr>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td>суммарно по плюсовым</td>

                              <td>

                                <div style="color:#256d20">Маржа: <?=$summ_marja?>р</div>
                                <div style="color:#dc2020">Затраты: <?=$summ_zatrati?>р</div></td>
                              <td>
                                <div style="display:flex;justify-content:space-between">
                                  <div>Зап: <?=$summ_zapros?> || Пок: <?=$summ_pokaz?></div>
                                  <div>CPC: <?=$sredn_cpc?>р</div>
                                  <div style="color:<?=$color_summ_prib?>">Приб: <?=$summ_prib?>р</div>
                                  <div>ROI: <?=$roi_all?>%</div>
                                </div>
                              <!--SEO показатели-->
                                                <div>
                                                  <div style="display:flex;justify-content:space-between;border-top:1px #ccc solid;margin-top:2px">
                                                    <div style="width:75px">
                                                      Angara77
                                                    </div>
                                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                                      топ 3: <?=$arr_seo_angara_pr3?>
                                                    </div>
                                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                                      топ 5: <?=$arr_seo_angara_pr5?>
                                                    </div>
                                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                                      топ 10: <?=$arr_seo_angara_pr10?>
                                                    </div>
                                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                                      50+: <?=$arr_seo_angara_pr50?>
                                                    </div>
                                                    <div>
                                                      <div style="width:75px">SEO: <b style="color:<?=$color_sr_seo_ang_prib?>"><?=$srz_seo_ang_prib?></b></div>
                                                    </div>
                                                  </div>
                                                  <div style="display:flex;justify-content:space-between;border-top:1px #ccc solid;margin-top:2px">
                                                    <div style="width:75px">
                                                      zapporter
                                                    </div>
                                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                                      топ 3: <?=$arr_seo_zp_pr3?>
                                                    </div>
                                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                                      топ 5: <?=$arr_seo_zp_pr5?>
                                                    </div>
                                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                                      топ 10: <?=$arr_seo_zp_pr10?>
                                                    </div>
                                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                                      50+: <?=$arr_seo_zp_pr50?>
                                                    </div>
                                                    <div>
                                                      <div  style="width:75px">SEO: <b style="color:<?=$color_sr_seo_zp_prib?>"><?=$srz_seo_zp_prib?></b></div>
                                                    </div>
                                                  </div>
                                                </div>
                            </td>
                            </tr>
                            <tr>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td>суммарно по группе</td>

                              <td>
                                <div style="color:#256d20">Маржа: <?=$summ_marja1?>р</div>
                                <div style="color:#dc2020">Затраты: <?=$summ_zatrati1?>р</div></td>
                              <td>
                                <div  style="display:flex;justify-content:space-between">
                              <div>Зап: <?=$summ_zapros1?> || Пок: <?=$summ_pokaz1?></div>
                              <div>CPC: <?=$sredn_cpc1?>р</div>
                              <div style="color:<?=$color_summ_prib1?>">Приб: <?=$summ_prib1?>р</div>
                              <div>ROI: <?=$roi_all1?>%</div>
                            </div>
                                <!--SEO показатели-->
                                <div>
                                  <div style="display:flex;justify-content:space-between;border-top:1px #ccc solid;margin-top:2px">
                                    <div style="width:75px">
                                      Angara77
                                    </div>
                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                      топ 3: <?=$arr_seo_angara_3?>
                                    </div>
                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                      топ 5: <?=$arr_seo_angara_5?>
                                    </div>
                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                      топ 10: <?=$arr_seo_angara_10?>
                                    </div>
                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                      50+: <?=$arr_seo_angara_50?>
                                    </div>
                                    <div>
                                      <div style="width:75px">SEO: <b style="color:<?=$color_sr_seo_ang?>"><?=$srz_seo_ang?></b></div>
                                    </div>
                                  </div>
                                  <div style="display:flex;justify-content:space-between;border-top:1px #ccc solid;margin-top:2px">
                                    <div style="width:75px">
                                      zapporter
                                    </div>
                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                      топ 3: <?=$arr_seo_zp_3?>
                                    </div>
                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                      топ 5: <?=$arr_seo_zp_5?>
                                    </div>
                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                      топ 10: <?=$arr_seo_zp_10?>
                                    </div>
                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                      50+: <?=$arr_seo_zp_50?>
                                    </div>
                                    <div>
                                      <div  style="width:75px">SEO: <b style="color:<?=$color_sr_seo_zp?>"><?=$srz_seo_zp?></b></div>
                                    </div>
                                  </div>
                                </div>
                            </td>
                            </tr>
                            <?php
                            $sql='UPDATE ' . $table_reklama . ' SET
                            sr_seo_angara="' . $srz_seo_ang . '",
                            sr_seo_zp="' . $srz_seo_zp . '",
                            cpc="' . $sredn_cpc1 . '",
                            zaprosi="' . $summ_zapros1 . '",
                            zaprosi_prib="' . $summ_prib1 . '",
                            pokazi="' . $summ_pokaz1 . '",
                            roi="' . $roi_all1 . '"
                            WHERE group_id="' . $id . '" ';
                            // update_sql_db4($sql);
                            ?>



<?php }elseif($id!="-" AND $id<1000){
//ЕСЛИ ВЫБРАНА субкат
                                p($razdel);
                                $sql_url_zp_cat_category='SELECT DISTINCT b.Address,b.ang_name FROM ' . $table_zp_cat_progon . ' a JOIN ' . $table_zp_cat_url_id . ' b WHERE a.id=b.id AND a.category_id LIKE "%,' . $category['category_id'] . '%"';
                                $arr_url_zp_cat_category=select_sql_db4($sql_url_zp_cat_category);
                                p($sql_url_zp_cat_category);
                                $sql_url_zp_cat='SELECT DISTINCT b.Address,b.ang_name FROM ' . $table_zp_cat_progon . ' a JOIN ' . $table_zp_cat_url_id . ' b WHERE a.id=b.id AND a.' . $razdel . ' LIKE "%,' . $id . '%"';
                                $arr_url_zp_cat=select_sql_db4($sql_url_zp_cat);
                                p($sql_url_zp_cat);
                                $sql_url_zp_tov='SELECT DISTINCT b.Address,b.ang_name FROM ' . $table_zp_tovar_progon . ' a JOIN ' . $table_zp_tovar_url_id . ' b WHERE a.id=b.id AND a.' . $razdel . ' LIKE "%,' . $id . '%"';
                                $arr_url_zp_tov=select_sql_db4($sql_url_zp_tov);

                                ?>
                                <tr>
                                <td><?php foreach ($arr_url_zp_cat_category as $key => $value) {?>
                                  <div>
                                    <a style="font-size:13px;" href="<?=$value['Address']?>"><?=$value['ang_name']?></a>
                                  </div>
                                  <?php }?>
                                </td>
                                <td><?php foreach ($arr_url_zp_cat_subcat as $key => $value) {?>
                                  <div>
                                    <a style="font-size:13px;" href="<?=$value['Address']?>"><?=$value['ang_name']?></a>
                                  </div>
                                  <?php }?>
                                </td>
                                <td><?php foreach ($arr_url_zp_cat as $key => $value) {?>
                                  <div>
                                    <a style="font-size:13px;" href="<?=$value['Address']?>"><?=$value['ang_name']?></a>
                                  </div>
                                  <?php }?>
                                </td>



                                <td><?php
                                  $arr_url_cat='';
                                  foreach ($arr_url_zp_tov as $key => $value) {
                                  $preg=preg_replace("/([a-z].*)[\/](part)[\/]([0-9]*)[\/]([a-z].*)[\/]/i",'$3',$value['Address']);
                                  // p($preg);
                                  $sql='SELECT parent FROM angara WHERE 1c_id="' . $preg . '"';
                                  $id_1c=select_sql_db4($sql);

                                  $id_1c='/porterparts/' . $id_1c[0]['parent'] . '/';
                                  $sql2='SELECT * FROM ' . $table_zp_cat_url_id . ' WHERE Address LIKE "%' . $id_1c . '%"';
                                  $res_sql2=select_sql_db4($sql2);
                                  $url_zp_tov_cat=$res_sql2[0]['Address'];
                                  $name_zp_tov_cat=$res_sql2[0]['ang_name'];
                                    $arr_url_cat .='`'.$url_zp_tov_cat . '`;`' . $name_zp_tov_cat . '`,';
                                    ?>
                                    <div>
                                      <a style="font-size:13px;" href="<?=$value['Address']?>"><?=$value['ang_name']?></a>
                                    </div>
                                    <?php
                                  }
                                  $res=rtrim($arr_url_cat,',');
                                  $ex=explode(',',$res);
                                  $ar_count_val=array_count_values($ex);
                                  $summ=array_sum($ar_count_val);
                                  $max=max($ar_count_val);
                                  $percent=$max/$summ*100 . "%";
                                  foreach ($ar_count_val as $key => $value) {

                                    if ($value==$max) {
                                      $name_cat_zp=preg_replace("/[`](.*)[`][;][`](.*)[`]/i",'$2',$key);
                                      $url_cat_zp=preg_replace("/[`](.*)[`][;][`](.*)[`]/i",'$1',$key);
                                    }
                                  }
                                  ?>


                                <td>
                                  <div>
                                  <a style="font-size:13px;" href="<?=$url_cat_zp?>"><?=$name_cat_zp?> [<?=$percent?>]</a>
                                </div>
                                </td>
                                </td>
                                <td><?php if(isset($url_cat_zp) AND !empty($url_cat_zp)){
                                  $url_obyavlenia=$url_cat_zp;
                                }elseif (isset($arr_url_zp_cat[0]['Address']) AND !empty($arr_url_zp_cat[0]['Address'])) {
                                  $url_obyavlenia=$arr_url_zp_cat[0]['Address'];
                                }elseif (isset($arr_url_zp_cat_category[0]['Address']) AND !empty($arr_url_zp_cat_category[0]['Address'])) {
                                  $url_obyavlenia=$arr_url_zp_cat_category[0]['Address'];
                                }?>
                                URL объявления: <br>
                                <?=$url_obyavlenia?></td>
                                <td></td>
                                </tr>
                                <!--ключи -->
                                <?php
                                $pribil_all='';
                                $zaprosi_all='';
                                $pokazi_all='';
                                $cpc_all='';
                                $marja_all='';
                                $zatrati_all='';
                                $pribil_all1='';
                                $zaprosi_all1='';
                                $pokazi_all1='';
                                $cpc_all1='';
                                $marja_all1='';
                                $zatrati_all1='';
                                $sr_seo_angara_prib='';
                                $sr_seo_zp_prib='';
                                $sr_seo_angara='';
                                $sr_seo_zp='';
                                  $sql='SELECT DISTINCT keywords FROM ' . $table_keywords . ' WHERE ' . $razdel . '=' . $id . ' AND danet=0 ' . $pre_razdel_sql . '';
                                  $keywords=select_sql_db4($sql);
                                  foreach ($keywords as $key => $keyword) {
                                //ключевое слово

                                    $or_predlogi="на|с|для|в|и";

                                    $ex_keywords=explode(" ",$keyword['keywords']);
                                    // p($ex_keywords);
                                    $a='';
                                    $sql_where='';
                                    $if_preg='';
                                      foreach ($ex_keywords as $key => $ex_key) {
                                        $a .=$ex_key . ',';
                                        $sql_where .='AND keyword LIKE "%' . $ex_key . '%" ';

                                        if (iconv_strlen($ex_key)<=4) {
                                          $tochk="";
                                        }elseif (iconv_strlen($ex_key)<=6){
                                          $tochk="..";
                                        }elseif (iconv_strlen($ex_key)<=9){
                                          $tochk="..";
                                        }else{
                                          $tochk="..";
                                        }
                                        $preg_repl=preg_replace("/(.*)(" . $tochk . ")$/i","$1",$ex_key);
                                        $if_preg .=$preg_repl . '|';
                                        // p($if_preg);
                                        // p($ex_key);
                                        // p(iconv_strlen($ex_key));

                                      }
                                      $res_if_preg=rtrim($if_preg,'|');
                                      $sql='SELECT keyword FROM reklama_done WHERE ' . ltrim($sql_where,'AND') . ' AND keyword!="' . $keyword['keywords'] . '" AND id_rk!=10';
                                      $perececheniya=select_sql_db4($sql);
                                      // echo " ---------------------------------" . $keyword['keywords'] . " ---------------------------------";
                                      // p($res_if_preg);
                                      $b='';
                                      foreach ($perececheniya as $key => $per) {
                                        $b .=$per['keyword'] . ' ';
                                      }

                                      $ex_b=explode(' ',rtrim($b,' '));
                                      // p($ex_b);
                                      $if_preg2='';
                                        foreach ($ex_b as $key => $ex_bb) {
                                          $if_preg2 .=$ex_bb . '|';
                                        }
                                        $res_if_preg2=rtrim($if_preg2,'|');

                                      $ccc='';
                                      foreach ($ex_b as $key => $exb) {
                                          $preg_repl=preg_replace("/(.*)(" . $res_if_preg . ")(.*)/i","qwertyuiop",$exb);

                                          if ($preg_repl=="qwertyuiop" OR $exb=="на" OR $exb=="с" OR $exb=="для" OR $exb=="в" OR $exb=="и") {
                                            // echo "`" . $res_if_preg . "` содержиться в `" . $exb . "` или предлог";
                                            // p($exb);
                                          }else{
                                            $ccc .=$exb . ' ';
                                          }
                                      }
                                      $res_ccc=rtrim($ccc,' ');
                                      $ex_res_ccc=array_unique(explode(' ',$res_ccc));
                                      // p($ex_res_ccc);
                                      // echo "--------------------------------------------!!!!!!!!!!1---------------------";


                                       $cccc='';
                                         foreach ($ex_res_ccc as $key => $exresccc) {
                                           // echo "минус слово: ";
                                           // echo "`" . $exresccc . "`";
                                           if (iconv_strlen($ex_key)<=4) {
                                             $tochk="";
                                           }elseif (iconv_strlen($ex_key)<=6){
                                             $tochk="..";
                                           }elseif (iconv_strlen($ex_key)<=9){
                                             $tochk="..";
                                           }else{
                                             $tochk="..";
                                           }
                                           $minus_tochk=preg_replace("/(.*)(" . $tochk . ")$/i","$1",$exresccc);

                                           $minus_res_if_preg=$res_if_preg . '|' . $minus_tochk;
                                           // echo "________КОМБИНАЦИЯ : `" . $minus_res_if_preg . "`";
                                           $ect6=0;
                                           foreach ($perececheniya as $key => $per) {
                                             $ex_per=explode(" ",$per['keyword']);
                                             $ex_per_count=count($ex_per);
                                             $c=0;
                                             foreach ($ex_per as $key => $ex_per_word) {
                                               // p($ex_per_word);
                                               $minus_all_preg_repl=preg_replace("/(.*)(" . $minus_res_if_preg . ")(.*)/i","qwertyuiop",$ex_per_word);
                                               $minus_all_preg_repl=preg_replace("/(" . $or_predlogi . ")$/i","qwertyuiop",$minus_all_preg_repl);

                                               // p($minus_all_preg_repl);
                                               if ($minus_all_preg_repl=="qwertyuiop") {
                                                 $c++;
                                               }else {}
                                             }
                                             if ($c==$ex_per_count) {
                                               $ect6++;
                                             }else{}
                                               // p($c);
                                               // p($ex_per_count);
                                               // echo "-------++++++++++++==========";
                                           }
                                           if ($ect6>0) {
                                              $cccc .='-' . $exresccc . ' ';
                                           }else{
                                             // echo "!!!!!!----------------------------нет такой комбинации слов `" . $minus_res_if_preg . "` ----------------------------!!!!!!";
                                           }

                                         }




                                         $res_cccc1=rtrim($cccc,'- ');
                                       $res_cccc=rtrim($res_cccc1,' ');
                                       $keys=$res_cccc;
                                       //не трогать без МЕНЯ ЭТО РАБОТАЕТ!!!!!!

                                      // p($keys);
                                      // p($perececheniya);
                                //позиции
                                    $sql_seo_angara='SELECT DISTINCT * FROM ' . $table_seo_angara . ' WHERE keyword="' . $keyword['keywords'] . '"';
                                    $seo_angara_arr=select_sql_db4($sql_seo_angara);
                                    $seo_angara=$seo_angara_arr[0]['position'];
                                    $seo_url_angara=$seo_angara_arr[0]['url'];
                                    if ($seo_angara>=5 OR $seo_angara=='-' OR empty($seo_angara)) {
                                      $color_seo_angara="#dc2020";

                                    }else{
                                      $color_seo_angara="#256d20";

                                    }
                                    $sql_seo_zp='SELECT DISTINCT * FROM ' . $table_seo_zapchastiporter . ' WHERE keyword="' . $keyword['keywords'] . '"';
                                    $seo_zp_arr=select_sql_db4($sql_seo_zp);
                                    $seo_zp=$seo_zp_arr[0]['position'];
                                    $seo_url_zp=$seo_zp_arr[0]['url'];
                                    if ($seo_zp>5 OR $seo_zp=='-' OR empty($seo_zp)) {
                                      $color_seo_zp="#dc2020";
                                    }else{
                                      $color_seo_zp="#256d20";
                                    }
                                    ?>

                                    <tr>
                                      <td></td>
                                      <td></td>

                                      <?php
                                //ОБЬЯВЛЕНИЕ
                                      $str_repl_keyw=str_ireplace('hyundai porter','Porter',$keyword['keywords']);
                                      $str_repl_keyw=str_ireplace('porter hyundai','Porter',$str_repl_keyw);
                                      $str_repl_keyw=str_ireplace('хендай портер','Портер',$str_repl_keyw);
                                      $str_repl_keyw=str_ireplace('портер хендай','Портер',$str_repl_keyw);
                                      $str_repl_keyw=str_ireplace('хундай портер','Портер',$str_repl_keyw);
                                      $str_repl_keyw=str_ireplace('портер хундай','Портер',$str_repl_keyw);
                                      $str_repl_keyw=str_ireplace('hyundai','Hyundai',$str_repl_keyw);
                                      $str_repl_keyw=str_ireplace('porter','Porter',$str_repl_keyw);
                                      $str_repl_keyw=str_ireplace('хендай','Хендай',$str_repl_keyw);
                                      $str_repl_keyw=str_ireplace('портер','Портер',$str_repl_keyw);
                                      $str_repl_keyw=str_ireplace('гбц ','ГБЦ ',$str_repl_keyw);
                                      $str_repl_keyw=str_ireplace('тнвд ','ТНВД ',$str_repl_keyw);
                                      $str_repl_keyw=str_ireplace('тагаз ','Тагаз ',$str_repl_keyw);
                                      $str_repl_keyw=str_ireplace('егр ','ЕГР ',$str_repl_keyw);
                                      $str_repl_keyw=str_ireplace(' гбц',' ГБЦ',$str_repl_keyw);
                                      $str_repl_keyw=str_ireplace(' тнвд',' ТНВД',$str_repl_keyw);
                                      $str_repl_keyw=str_ireplace(' тагаз',' Тагаз',$str_repl_keyw);
                                      $str_repl_keyw=str_ireplace(' егр',' ЕГР',$str_repl_keyw);
                                      $str_repl_keyw=str_ireplace(' москв',' Москв',$str_repl_keyw);

                                      $title_obyavlenia1=ucfirst($str_repl_keyw);
                                                $title_words=explode(' ',$title_obyavlenia1);
                                                $max=count($title_words)-1;
                                                $words_ar='';
                                                foreach ($title_words as $key => $word1) {
                                                  if($key==$max){

                                                  }else{
                                                    $words_ar .=$word1 . ' ';
                                                  }
                                                }
                                              $title_obyavlenia2=ucfirst(rtrim($words_ar,' '));
                                              $title_obyavlenia2=preg_replace("/(.*)([ ])(на|для|и)$/i","$1",$title_obyavlenia2);
                                                $title_words2=explode(' ',$title_obyavlenia2);

                                                $max2=count($title_words2)-1;

                                                $words_ar2='';
                                                foreach ($title_words2 as $key => $word2) {
                                                  if($key==$max2){

                                                  }else{
                                                    $words_ar2 .=$word2 . ' ';
                                                  }
                                                }
                                              $title_obyavlenia3=ucfirst(rtrim($words_ar2,' '));
                                              $title_obyavlenia3=preg_replace("/(.*)([ ])(на|для|и)$/i","$1",$title_obyavlenia3);
                                                $title_words3=explode(' ',$title_obyavlenia3);
                                                $max3=count($title_words3)-1;
                                                $words_ar3='';
                                                foreach ($title_words3 as $key => $word3) {
                                                  if($key==$max3){

                                                  }else{
                                                    $words_ar3 .=$word3 . ' ';
                                                  }
                                                }
                                              $title_obyavlenia4=ucfirst(rtrim($words_ar3,' '));
                                              $title_obyavlenia4=preg_replace("/(.*)([ ])(на|для|и)$/i","$1",$title_obyavlenia4);
                                                if (iconv_strlen($title_obyavlenia1)<=$title1_count) {
                                                  $title_obyavlenia=$title_obyavlenia1;
                                                }elseif(iconv_strlen($title_obyavlenia2)<=$title1_count){
                                                  $title_obyavlenia=$title_obyavlenia2;
                                                }elseif(iconv_strlen($title_obyavlenia3)<=$title1_count){
                                                  $title_obyavlenia=$title_obyavlenia3;
                                                }elseif(iconv_strlen($title_obyavlenia4)<=$title1_count){
                                                  $title_obyavlenia=$title_obyavlenia4;
                                                }
                                                $title_obyavlenia=preg_replace("/(.*)([ ])(цена|цены)$/i","$1",$title_obyavlenia);
                                                $title_obyavlenia=preg_replace("/(цена|цены)([ ])(.*)/i","$3",$title_obyavlenia);
                                                $first=mb_strtoupper(mb_substr($title_obyavlenia, 0, 1));
                                                $all=mb_substr($title_obyavlenia, 1);
                                                $title_obyavlenia=$first . $all;


                                      $title2_obyavlenia="В наличии!";
                                      $str_repl_keyw=preg_replace("/(.*)([ ])(цена)$/i","$1",$str_repl_keyw);
                                      $str_repl_keyw=preg_replace("/(цена)([ ])(.*)/i","$3",$str_repl_keyw);
                                      $text_obyavlenia1=ucfirst($str_repl_keyw . " – Недорого. Доставка 290р + 100% гарантия возврата!");
                                      $text_obyavlenia2=ucfirst($str_repl_keyw . " – Недорого. Доставка 290р + 100% гарантия возврата");
                                      $text_obyavlenia3=ucfirst($str_repl_keyw . " – Доставка 290р + 100% гарантия возврата");
                                      $text_obyavlenia4=ucfirst("97% запчастей на Портер в наличии – Доставка 290р + 100% гарантия возврата!");
                                              if (iconv_strlen($text_obyavlenia1)<=$text_count) {
                                                $text_obyavlenia=ucfirst($text_obyavlenia1);
                                              }elseif(iconv_strlen($text_obyavlenia2)<=$text_count){
                                                $text_obyavlenia=ucfirst($text_obyavlenia2);
                                              }elseif(iconv_strlen($text_obyavlenia3)<=$text_count){
                                                $text_obyavlenia=ucfirst($text_obyavlenia3);
                                              }elseif(iconv_strlen($text_obyavlenia4)<=$text_count){
                                                $text_obyavlenia=ucfirst($text_obyavlenia4);
                                              }
                                              $first=mb_strtoupper(mb_substr($text_obyavlenia, 0, 1));
                                              $all=mb_substr($text_obyavlenia, 1);
                                              $text_obyavlenia=$first . $all;
                                      $patch1="https://zapchastiporter.ru";
                                      $patch21=ucfirst($category['subcat_name']);
                                              $patch21_words=explode(' ',$patch21);
                                              $max=count($patch21_words)-1;
                                              $words_ar='';
                                              foreach ($patch21_words as $key => $word) {
                                                if($key==$max){

                                                }else{
                                                  $words_ar .=$word . ' ';
                                                }
                                              }
                                              $patch22=ucfirst(rtrim($words_ar,' '));
                                                $patch22_words=explode(' ',$patch22);
                                                $max2=count($patch22_words)-1;
                                                $words_ar2='';
                                                foreach ($patch22_words as $key => $word2) {
                                                  if($key==$max2){

                                                  }else{
                                                    $words_ar2 .=$word2 . ' ';
                                                  }
                                                }
                                              $patch23=ucfirst(rtrim($words_ar2,' '));
                                              $name_cat_zp=str_ireplace(" для "," ",$name_cat_zp);
                                              $name_cat_zp=str_ireplace(" на "," ",$name_cat_zp);
                                              $name_cat_zp=str_ireplace(" Хендай "," ",$name_cat_zp);
                                              $name_cat_zp=str_ireplace("hyundai"," ",$name_cat_zp);
                                              $name_cat_zp=str_ireplace("porter"," ",$name_cat_zp);
                                              $name_cat_zp2=str_ireplace(" портер "," ",$name_cat_zp);
                                              $name_cat_zp2=preg_replace("/(.*)([ ])(портер|Портер)$/i","$1",$name_cat_zp2);
                                              $name_cat_zp3=preg_replace("/(.*)([ ])(.*)$/i","$1",$name_cat_zp2);
                                              if (iconv_strlen($patch21)<=$patch2_count) {
                                                $patch2=ucfirst($patch21);
                                              }elseif (iconv_strlen($patch22)<=$patch2_count) {
                                                $patch2=ucfirst($patch22);
                                              }elseif (iconv_strlen($patch23)<=$patch2_count) {
                                                $patch2=ucfirst($patch23);
                                              }elseif(iconv_strlen($name_cat_zp)<=$patch2_count){
                                                $patch2=$name_cat_zp;
                                              }elseif(iconv_strlen($name_cat_zp2)<=$patch2_count){
                                                $patch2=$name_cat_zp2;
                                              }else{
                                                $patch2=$name_cat_zp3;
                                              }
                                              $patch2=preg_replace("/(.*)([ ])(портер|Портер|на|для|и|.)$/i","$1",trim($patch2,' '));
                                              $patch2=preg_replace("/(.*)([ ])(портер|Портер)$/i","$1",$patch2);

                                              $first=mb_strtoupper(mb_substr($patch2, 0, 1));
                                              $all=mb_substr($patch2, 1);
                                              $patch2=$first . $all;
                                              $patch2=str_ireplace(" ","-",$patch2);
                                              $patch2=str_ireplace(",","-",$patch2);
                                              $patch2=str_ireplace("--","-",$patch2);
                                              $patch2=str_ireplace("--","-",$patch2);
                                              $patch2=str_ireplace("--","-",$patch2);
                                              $patch2=trim($patch2,"-");
                                      $title_obyavlenia_simv=iconv_strlen($title_obyavlenia);
                                      $title2_obyavlenia_simv=iconv_strlen($title2_obyavlenia);
                                      $text_obyavlenia_simv=iconv_strlen($text_obyavlenia);
                                      $patch2_simv=iconv_strlen($patch2);
                                      if (isset($title_obyavlenia_simv)) {
                                      if ($title_obyavlenia_simv>$title1_count) {
                                        $title_simv_color="red";
                                      }else{
                                        $title_simv_color="green";
                                      }
                                      }
                                      if (isset($title2_obyavlenia_simv)) {
                                      if ($title2_obyavlenia_simv>$title2_count) {
                                        $title2_simv_color="red";
                                      }else{
                                        $title2_simv_color="green";
                                      }
                                      }
                                      if (isset($text_obyavlenia_simv)) {
                                      if ($text_obyavlenia_simv>$text_count) {
                                        $text_simv_color="red";
                                      }else{
                                        $text_simv_color="green";
                                      }
                                      }
                                      if (isset($text_obyavlenia_simv)) {
                                      if ($patch2_simv>$patch2_count) {
                                        $patch2_simv_color="red";
                                      }else{
                                        $patch2_simv_color="green";
                                      }
                                      }

                                      ?>
                                      <td>
                                        <h2 class="zagolovok"><b style="color:<?=$title_simv_color?>">[<?=$title_obyavlenia_simv?>]</b> – <b style="color:<?=$title2_simv_color?>">[<?=$title2_obyavlenia_simv?>]</b></h2>
                                        <p style=" margin: 0;    padding: 0;font-size: 14px;font-family: Arial,Helvetica,sans-serif;white-space: nowrap;color:<?=$patch2_simv_color?>">[<?=$patch2_simv?>]</p>
                                        <p class="description" style="color:<?=$text_simv_color?>">[<?=$text_obyavlenia_simv?>]</p>
                                      </td>
                                      <td style="width:536px;overflow:break-word;line-height: 17px">
                                        <h2 class="zagolovok"><a><?=$title_obyavlenia?> – <?=$title2_obyavlenia?></a></h2>
                                        <p style=" margin: 0;    padding: 0;font-size: 14px;font-family: Arial,Helvetica,sans-serif;color:#070;    white-space: nowrap;">
                                          <a style="color:#070;    list-style: none;text-decoration: none" href="<?=$url_obyavlenia?>"><b><?=$patch1?></b> › <text class="url2"><?=$patch2?></text></a>
                                        </p>
                                        <p class="description"><?=$text_obyavlenia?></p>
                                        <!-- <p class="description"><?=$str_repl_keyw?> – Недорого, оригинал и аналог. Доставка по Москве и России +100% гарантия возврата!</p> -->
                                      </td>

                                      <td>


                                        <?=$keyword['keywords']?>
                                        <?=$keys?>


                                        <form action="" method="get">
                                          <input hidden name="do" value="<?=$_GET['do']?>"></input>
                                          <input hidden name="table" value="<?=$_GET['table']?>"></input>
                                          <input type="radio" name="danet_f" value="1"></input>
                                          <input hidden name="keyword" value="<?=$keyword['keywords']?>"></input>
                                          <input hidden name="gr_id" value="<?=$id?>"></input>


                                          <input type="submit" value="удалить категорию!">
                                        </form>
                                        <?php if (isset($_GET['danet_f']) AND $_GET['danet_f']==1 AND $_GET['keyword']==$keyword['keywords'] AND $_GET['gr_id']==$id) {
                                          $sql_danet='UPDATE ' . $table_keywords . ' SET danet=1 WHERE keywords="' . $keyword['keywords'] . '" AND group_id=' . $id;
                                          echo '<p style="background-color:#ffa8a9">удалено</p>';
                                          update_sql_db4($sql_danet);
                                        }?>








                                        <?php
                                        $sql='SELECT DISTINCT * FROM ' . $table_kernel_direct . ' WHERE keyword="' . $keyword['keywords'] . '"';
                                        $metriki=select_sql_db4($sql);

                                        ?>

                                      </td>
                                <!--метрики -->
                                      <td>
                                        <div style="display:flex;justify-content:space-between">
                                          <?php if (isset($metriki[0]['zaprosi']) AND $metriki[0]['zaprosi']!=0) {
                                            $cpc=str_ireplace(',','.',$metriki[0]['click_price_final']);
                                            $cpc1=str_ireplace(',','.',$metriki[0]['click_price_final']);
                                            $cpc9=str_ireplace(',','.',$metriki[0]['click_price_62']);
                                            $zaprosi=$metriki[0]['zaprosi'];
                                            $zaprosi1=$metriki[0]['zaprosi'];
                                            $zaprosi2=$metriki[0]['zaprosi'];
                                            $pokazi=$metriki[0]['pokaz_100'];
                                            $pokazi1=$metriki[0]['pokaz_100'];
                                            $pokazi2=$metriki[0]['pokaz_100'];
                                            $marja=$zaprosi*$categorys[0]['conversion_sites']*$categorys[0]['conversion']/100*$categorys[0]['sr_prib_good'];
                                            $marja1=$zaprosi*$categorys[0]['conversion_sites']*$categorys[0]['conversion']/100*$categorys[0]['sr_prib_good'];
                                            $marja2=$zaprosi2*$categorys[0]['conversion_sites']*$categorys[0]['conversion']/100*$categorys[0]['sr_prib_good'];
                                            $zatrati=$zaprosi*$cpc;
                                            $zatrati1=$zaprosi*$cpc;
                                            $zatrati2=$zaprosi2*$cpc9;
                                            // p($metriki[0]['zaprosi']*$categorys[0]['conversion_sites']);
                                            $pribil=number_format($marja-$zatrati,2,'.','');
                                            $pribil1=number_format($marja-$zatrati,2,'.','');
                                            $pribil2=number_format($marja2-$zatrati2,2,'.','');
                                            $max_cpc=number_format($marja/$zaprosi,0,'.','');

                                            if ($zatrati<=0) {
                                              $roi=0;
                                              $roi2=0;
                                            }else{
                                            $roi=number_format($marja/$zatrati*100,2,'.','');
                                            $roi2=number_format($marja/$zatrati2*100,2,'.','');
                                            }
                                            $obiem=75;
                                              if ($pribil<=0) {
                                                // p($cpc);
                                                $cpc=$cpc9;
                                                $zatrati=$zatrati2;
                                                $pribil=$pribil2;
                                                $roi=$roi2;
                                                $marja=$marja2;
                                                $zaprosi=$zaprosi2;
                                                $pokazi=$pokazi2;
                                                $obiem=9;
                                              }
                                            $pribil_all1 .=$pribil1 . ';';
                                            $marja_all1 .=$marja1 . ';';
                                            $zaprosi_all1 .=$zaprosi1 . ';';
                                            $pokazi_all1 .=$pokazi1 . ';';
                                            $cpc_all1 .=$cpc1*$zaprosi1 . ';';
                                            $zatrati_all1 .=$zatrati1 . ';';
                                                    if ($seo_angara=='-' OR empty($seo_angara)) {

                                                    }else{
                                                        $sr_seo_angara .=$seo_angara . ';';
                                                    }
                                                    if ($seo_zp=='-' OR empty($seo_zp)) {

                                                    }else{
                                                        $sr_seo_zp .=$seo_zp . ';';
                                                    }
                                            if ($pribil<=0) {

                                              $color='#dc2020';
                                            }else{
                                                    if ($seo_angara=='-' OR empty($seo_angara)) {

                                                    }else{
                                                        $sr_seo_angara_prib .=$seo_angara . ';';
                                                    }
                                                    if ($seo_zp=='-' OR empty($seo_zp)) {

                                                    }else{
                                                        $sr_seo_zp_prib .=$seo_zp . ';';
                                                    }
                                              $pribil_all .=$pribil . ';';
                                              $marja_all .=$marja . ';';
                                              $zaprosi_all .=$metriki[0]['zaprosi'] . ';';
                                              $pokazi_all .=$metriki[0]['pokaz_100'] . ';';
                                              $cpc_all .=$cpc*$metriki[0]['zaprosi'] . ';';
                                              $zatrati_all .=$zatrati . ';';
                                              $color='#256d20';

                                            }
                                            ?>

                                            <div style="min-width:120px">Зап: <?=$metriki[0]['zaprosi']?>|<?=$zaprosi?><br>Пок: <?=$metriki[0]['pokaz_100']?> | <?=$pokazi?></div>
                                            <div style="min-width:150px">CPC: <?=$cpc?>р(<?=$metriki[0]['click_price_final']?>р)</div>
                                            <div style="min-width:150px">max-CPC: <?=$max_cpc?>р</div>
                                            <div style="color:<?=$color?>;min-width:120px">Приб: <?=$pribil?>р</div>
                                            <div style="min-width:120px">ROI: <?=$roi?>%</div>
                                            <div style="min-width:75px"><?=$obiem?>%</div>
                                          <?php }else{
                                            $cpc=0;
                                            $zatrati=0;
                                            $pribil=0;
                                            $roi=0;
                                            $marja=0;
                                            $zaprosi=0;
                                            $pokazi=0;
                                            $obiem=9;
                                            $max_cpc=0;

                                            $cpc1=0;
                                            $zatrati1=0;
                                            $pribil1=0;
                                            $roi1=0;
                                            $marja1=0;
                                            $zaprosi1=0;
                                            $pokazi1=0;
                                            $obiem1=75;

                                        $pribil_all1 .=$pribil1 . ';';
                                        $marja_all1 .=$marja1 . ';';
                                        $zaprosi_all1 .=$zaprosi1 . ';';
                                        $pokazi_all1 .=$pokazi1 . ';';
                                        $cpc_all1 .=$cpc1*$zaprosi1 . ';';
                                        $zatrati_all1 .=$zatrati1 . ';';
                                          $pribil_all .=$pribil . ';';
                                          $marja_all .=$marja . ';';
                                          $zaprosi_all .=$zaprosi . ';';
                                          $pokazi_all .=$pokazi . ';';
                                          $cpc_all .=$cpc*$zaprosi . ';';
                                          $zatrati_all .=$zatrati . ';';
                                            ?>
                                            <div style="min-width:120px">Зап: 0 || Пок: ~~ </div><div>CPC: ~~ р</div>
                                            <div style="min-width:150px">CPC: -р)</div>
                                            <div style="min-width:150px">max-CPC: -р</div>
                                            <div style="color:<?=$color?>;min-width:120px">Приб: -р</div>
                                            <div style="min-width:120px">ROI: -%</div>
                                            <div style="min-width:75px"><?=$obiem?>%</div>

                                          <?php }?>
                                        </div>
                                <!--SEO показатели-->
                                        <?php
                                        if ($seo_zp=="-" OR $seo_zp=="" OR $seo_zp==0) {
                                          $seo_zp="100";
                                        }
                                        if ($seo_angara=="-" OR $seo_angara=="" OR $seo_angara==0) {
                                          $seo_angara="100";
                                        }
                                        ?>
                                        <div>
                                          <div style="display:flex;justify-content:space-between;border-top:1px #ccc solid;margin-top:2px">
                                            <div style="width:75px">
                                              Angara77
                                            </div>
                                            <div style="width:300px;font-size:12px;overflow:hidden">
                                              <?php if (isset($seo_url_angara) AND $seo_url_angara!='-') {
                                              ?>
                                              <a href="http://angara77.com<?=$seo_url_angara?>" target="_blank"><?=$seo_url_angara?></a>
                                            <?php }else{?>
                                              <b style="color:#dc2020">НЕТ URL</b>
                                            <?php } ?>
                                            </div>
                                            <div>
                                              <div style="width:75px">SEO: <b style="color:<?=$color_seo_angara?>"><?=$seo_angara?></b></div>
                                            </div>
                                          </div>
                                          <div style="display:flex;justify-content:space-between;border-top:1px #ccc solid;margin-top:2px">
                                            <div style="width:75px">
                                              zapporter
                                            </div>
                                            <div style="width:300px;font-size:12px;overflow:hidden">
                                              <?php if (isset($seo_url_zp) AND $seo_url_zp!='-') {
                                              ?>
                                              <a href="https://zapchastiporter.ru<?=$seo_url_zp?>" target="_blank"><?=$seo_url_zp?></a>
                                            <?php }else{?>
                                              <b style="color:#dc2020">НЕТ URL</b>
                                            <?php } ?>
                                            </div>
                                            <div>
                                              <div  style="width:75px">SEO: <b style="color:<?=$color_seo_zp?>"><?=$seo_zp?></b></div>
                                            </div>
                                          </div>
                                        </div>
                                      </td>
                                      <?php
                                      $cpc=number_format($cpc,2,'.','');
                                      if ($roi=="inf" OR $roi=="") {
                                        $roi=0.00;
                                      }
                                      $roi=number_format($roi,2,'.','');
                                      if ($zaprosi=="" OR $zaprosi=="-" OR $zaprosi=="~") {
                                        $zaprosi=0;
                                      }
                                      if ($pokazi=="" OR $pokazi=="-" OR $pokazi=="~") {
                                        $pokazi=0;
                                      }


                                      if ($zaprosi<10) {
                                        $rk=10;
                                      }else{
                                        if ($seo_zp<=5 OR $seo_angara<=5) {
                                          $cpc=$cpc9;
                                          $zatrati=$zatrati2;
                                          $pribil=$pribil2;
                                          $roi=$roi2;
                                          $marja=$marja2;
                                          $zaprosi=$zaprosi2;
                                          $pokazi=$pokazi2;
                                          $obiem=9;
                                          if ($pribil>0) {
                                            $rk=1;
                                          }else{
                                            $rk=2;
                                          }

                                        }else{
                                          if ($obiem==9) {
                                            if ($pribil>0) {
                                              $rk=4;
                                            }else{
                                              $rk=5;
                                            }
                                          }else{
                                            $rk=3;
                                          }
                                        }
                                      }
                                      ?>
                                <!--группы где пересекаются слова -->
                                        <?php
                                        $sql='SELECT DISTINCT category_id,category_name,subcat_id,subcat_name,group_id,group_name FROM ' . $table_keywords . ' WHERE keywords="' . $keyword['keywords'] . '" AND ' . $razdel . '!=' . $id . ' AND danet=0';
                                        $no_group=select_sql_db4($sql);
                                        if (isset($no_group[0]['category_id'])) {
                                        ?>
                                        <td style="background-color:#ffa8a9">
                                        <?php foreach ($no_group as $key => $no_gr){?>

                                          <form action="" method="get">
                                            <a href="/reklama/?do=2&table=<?=$no_gr['category_id']?>"><?=$no_gr['category_name']?></a> / <a href="/reklama/?do=2&table=<?=$no_gr['subcat_id']?>"><?=$no_gr['subcat_name']?></a> / <a href="/reklama/?do=2&table=<?=$no_gr['group_id']?>"><?=$no_gr['group_name']?></a>
                                            <input hidden name="do" value="<?=$_GET['do']?>"></input>
                                            <input hidden name="table" value="<?=$_GET['table']?>"></input>
                                            <input type="radio" checked name="danet" value="1"></input>
                                            <input hidden name="keyword" value="<?=$keyword['keywords']?>"></input>
                                            <input hidden name="no_gr" value="<?=$no_gr['group_id']?>"></input>


                                            <input type="submit" value="удалить категорию!">
                                          </form>
                                          <?php if (isset($_GET['danet']) AND $_GET['danet']==1 AND $_GET['keyword']==$keyword['keywords'] AND $_GET['no_gr']==$no_gr['group_id']) {
                                            $sql_danet='UPDATE ' . $table_keywords . ' SET danet=1 WHERE keywords="' . $keyword['keywords'] . '" AND group_id=' . $no_gr['group_id'];
                                            echo "удалено";
                                            update_sql_db4($sql_danet);
                                          }?>
                                        <?php } ?>
                                        <p>RK = <?=$rk?></p>
                                        </td>
                                      <?php }else{?>
                                        <td><p>RK = <?=$rk?></p></td>
                                      <?php }


                                //НОВАЯ ВСТАВКА





                                      if(isset($metriki[0]['zaprosi']) AND $metriki[0]['zaprosi']==""){
                                        $metriki[0]['zaprosi']=0;
                                      }else{
                                        $metriki[0]['zaprosi']=0;
                                      }

                                      if (isset($metriki[0]['pokaz_100']) AND $metriki[0]['pokaz_100']=="") {
                                        $metriki[0]['pokaz_100']=0;
                                      }else{
                                        $metriki[0]['pokaz_100']=0;
                                      }

                                      $sql_checkeee='SELECT * FROM reklama_blocked_ids WHERE keyword IS NOT NULL';
                                      $checkeee=select_sql_db4($sql_checkeee);
                                      $arr_checkeee="";
                                        foreach ($checkeee as $key => $che) {
                                          $arr_checkeee .=$che['keyword'] . ';';
                                        }

                                        $arr_res_checkeee=explode(';',rtrim($arr_checkeee,';'));
                                      if (in_array($keyword['keywords'],$arr_res_checkeee)) {

                                      }else{
                                        //если вставляем то заменить точки в цифрах на запятые
                                        $cpc=str_ireplace(".",",",$cpc);
                                        $roi=str_ireplace(".",",",$roi);

                                      $table_reklama_done='reklama_done';
                                      $sql_check_done='SELECT * FROM ' . $table_reklama_done . '
                                      WHERE cat_id="' . $id . '" AND keyword="' . $keyword['keywords'] . '"';
                                      $check_reklama_done=select_sql_db4($sql_check_done);
                                      // p($sql_check_done);
                                      if (isset($check_reklama_done[0]['keyword'])) {
                                        if ($url_obyavlenia==$check_reklama_done[0]['url']
                                        AND $check_reklama_done[0]['minus_keyword']==$keys
                                         AND $title_obyavlenia==$check_reklama_done[0]['title']
                                           AND $title_obyavlenia_simv==$check_reklama_done[0]['title_count']
                                            AND $title2_obyavlenia==$check_reklama_done[0]['title2']
                                              AND $check_reklama_done[0]['title2_count']==$title2_obyavlenia_simv
                                               AND $check_reklama_done[0]['text']==$text_obyavlenia
                                                 AND $check_reklama_done[0]['text_count']==$text_obyavlenia_simv
                                                  AND $check_reklama_done[0]['patch']==$patch2
                                                   AND $check_reklama_done[0]['patch_count']==$patch2_simv
                                                     AND $check_reklama_done[0]['zapros']==$zaprosi
                                                       AND $check_reklama_done[0]['pokaz']==$pokazi
                                                        AND $check_reklama_done[0]['cpc']==$cpc
                                                         AND $check_reklama_done[0]['max_cpc']==$max_cpc
                                                          AND $check_reklama_done[0]['roi']==$roi
                                                           AND $check_reklama_done[0]['id_rk']==$rk) {
                                          echo "уже есть";
                                        }else{
                                          $sql_upd='UPDATE ' . $table_reklama_done . '
                                          SET cat_id="' . $id . '" ,
                                          keyword="' . $keyword['keywords'] . '",
                                          minus_keyword="' . $keys . '",
                                          url="' . $url_obyavlenia . '",
                                          title="' . $title_obyavlenia . '",
                                          title_count="' . $title_obyavlenia_simv . '",
                                          title2="' . $title2_obyavlenia . '",
                                          title2_count="' . $title2_obyavlenia_simv . '",
                                          text="' . $text_obyavlenia . '",
                                          text_count="' . $text_obyavlenia_simv . '",
                                          patch="' . $patch2 . '",
                                          patch_count="' . $patch2_simv . '",
                                          zapros="' . $zaprosi . '",
                                          pokaz="' . $pokazi . '",
                                          cpc="' . $cpc . '",
                                          max_cpc="' . $max_cpc . '",
                                          roi="' . $roi . '",
                                          seo_angara="' . $seo_angara . '",
                                          seo_zp="' . $seo_zp . '",
                                          url_seo_angara="' . $seo_url_angara . '",
                                          url_seo_zp="' . $seo_url_zp . '",
                                          id_rk="' . $rk . '"
                                          WHERE cat_id="' . $check_reklama_done[0]['cat_id'] . '" AND keyword="' . $check_reklama_done[0]['keyword'] . '"';
                                          p($sql_upd);
                                          update_sql_db4($sql_upd);
                                        }
                                      }else{
                                        $sql='INSERT INTO ' . $table_reklama_done . '(cat_id,
                                        keyword,
                                        minus_keyword,
                                        url,
                                        title,
                                        title_count,
                                        title2,
                                        title2_count,
                                        text,
                                        text_count,
                                        patch,
                                        patch_count,
                                        zapros,
                                        pokaz,
                                        cpc,
                                        max_cpc,
                                        roi,
                                        seo_angara,
                                        seo_zp,
                                        url_seo_angara,
                                        url_seo_zp,
                                        id_rk)
                                         VALUES ("' . $id . '",
                                         "' . $keyword['keywords'] . '",
                                         "' . $keys . '",
                                         "' . $url_obyavlenia . '",
                                         "' . $title_obyavlenia . '",
                                         "' . $title_obyavlenia_simv . '",
                                         "' . $title2_obyavlenia . '",
                                         "' . $title2_obyavlenia_simv . '",
                                         "' . $text_obyavlenia . '",
                                         "' . $text_obyavlenia_simv . '",
                                         "' . $patch2 . '",
                                         "' . $patch2_simv . '",
                                         "' . $zaprosi . '",
                                         "' . $pokazi . '",
                                         "' . $cpc . '",
                                         "' . $max_cpc . '",
                                         "' . $roi . '",
                                         "' . $seo_angara . '",
                                         "' . $seo_zp . '",
                                         "' . $seo_url_angara . '",
                                         "' . $seo_url_zp . '",
                                         "' . $rk . '")';
                                         p($sql);
                                         update_sql_db4($sql);
                                      }
                                      }



                                    }
                                    if (isset($title_obyavlenia_simv)) {
                                    if ($title_obyavlenia_simv>$title1_count) {
                                      $title_simv_color="red";
                                    }else{
                                      $title_simv_color="green";
                                    }
                                    }
                                    if (isset($title2_obyavlenia_simv)) {
                                    if ($title2_obyavlenia_simv>$title2_count) {
                                      $title2_simv_color="red";
                                    }else{
                                      $title2_simv_color="green";
                                    }
                                    }
                                    if (isset($text_obyavlenia_simv)) {
                                    if ($text_obyavlenia_simv>$text_count) {
                                      $text_simv_color="red";
                                    }else{
                                      $text_simv_color="green";
                                    }
                                    }
                                    if (isset($text_obyavlenia_simv)) {
                                    if ($patch2_simv>$patch2_count) {
                                      $patch2_simv_color="red";
                                    }else{
                                      $patch2_simv_color="green";
                                    }
                                    }


                                //по плюсовым
                                    $res1=rtrim($pribil_all,';');
                                  $summ_prib=number_format(array_sum(explode(';',$res1)),0,'.','');
                                    $res2=rtrim($zaprosi_all,';');
                                  $summ_zapros=array_sum(explode(';',$res2));
                                    $res3=rtrim($pokazi_all,';');
                                  $summ_pokaz=array_sum(explode(';',$res3));
                                    $res5=rtrim($marja_all,';');
                                  $summ_marja=array_sum(explode(';',$res5));
                                    $res6=rtrim($zatrati_all,';');
                                  $summ_zatrati=array_sum(explode(';',$res6));
                                    $res4=rtrim($cpc_all,';');
                                    $arr_cpc=explode(';',$res4);
                                    $count_cpc=count($arr_cpc);
                                    $summ_cpc=array_sum($arr_cpc);

                                  if ($summ_zatrati==0 OR $summ_marja==0) {
                                    $roi_all=0;
                                    $sredn_cpc=0;
                                  }else{
                                    $sredn_cpc=number_format($summ_cpc/$summ_zapros,2);
                                    $roi_all=number_format($summ_marja/$summ_zatrati*100,2,'.','');
                                  }
                                  if ($summ_prib<0) {
                                    $color_summ_prib='#dc2020';
                                  }else{
                                    $color_summ_prib='#256d20';
                                  }


                                //общий подсчет
                                  $res11=rtrim($pribil_all1,';');
                                $summ_prib1=number_format(array_sum(explode(';',$res11)),0,'.','');
                                  $res21=rtrim($zaprosi_all1,';');
                                $summ_zapros1=array_sum(explode(';',$res21));
                                  $res31=rtrim($pokazi_all1,';');
                                $summ_pokaz1=array_sum(explode(';',$res31));
                                  $res51=rtrim($marja_all1,';');
                                $summ_marja1=array_sum(explode(';',$res51));
                                  $res61=rtrim($zatrati_all1,';');
                                $summ_zatrati1=array_sum(explode(';',$res61));
                                  $res41=rtrim($cpc_all1,';');
                                  $arr_cpc1=explode(';',$res41);
                                  $count_cpc1=count($arr_cpc1);
                                  $summ_cpc1=array_sum($arr_cpc1);

                                //SEO подсчет
                                  $arr_seo_angara_prib=explode(';',rtrim($sr_seo_angara_prib,';'));
                                  $srz_seo_ang_prib=number_format(array_sum($arr_seo_angara_prib)/count($arr_seo_angara_prib),2,'.','');
                                  if ($srz_seo_ang_prib>5) {
                                    $color_sr_seo_ang_prib='red';
                                  }else{
                                    $color_sr_seo_ang_prib='green';
                                  }
                                  $arr_seo_angara_pr10=0;
                                  $arr_seo_angara_pr5=0;
                                  $arr_seo_angara_pr3=0;
                                  $arr_seo_angara_pr50=0;
                                  foreach ($arr_seo_angara_prib as $key => $arr_seo_angara_pr) {
                                    if ($arr_seo_angara_pr<=10) {
                                      $arr_seo_angara_pr10++;
                                    }else{
                                      $arr_seo_angara_pr50++;
                                    }
                                    if ($arr_seo_angara_pr<=5) {
                                      $arr_seo_angara_pr5++;
                                    }
                                    if ($arr_seo_angara_pr<=3) {
                                      $arr_seo_angara_pr3++;
                                    }
                                  }

                                  $arr_seo_zp_prib=explode(';',rtrim($sr_seo_zp_prib,';'));
                                  $srz_seo_zp_prib=number_format(array_sum($arr_seo_zp_prib)/count($arr_seo_zp_prib),2,'.','');
                                  if ($srz_seo_zp_prib>5) {
                                    $color_sr_seo_zp_prib='red';
                                  }else{
                                    $color_sr_seo_zp_prib='green';
                                  }
                                  $arr_seo_zp_pr50=0;
                                  $arr_seo_zp_pr10=0;
                                  $arr_seo_zp_pr5=0;
                                  $arr_seo_zp_pr3=0;
                                  foreach ($arr_seo_zp_prib as $key => $arr_seo_zp_pr) {
                                    if ($arr_seo_zp_pr<=10) {
                                      $arr_seo_zp_pr10++;
                                    }else{
                                      $arr_seo_zp_pr50++;
                                    }
                                    if ($arr_seo_zp_pr<=5) {
                                      $arr_seo_zp_pr5++;
                                    }
                                    if ($arr_seo_zp_pr<=3) {
                                      $arr_seo_zp_pr3++;
                                    }
                                  }

                                  $arr_seo_angara=explode(';',rtrim($sr_seo_angara,';'));
                                  $srz_seo_ang=number_format(array_sum($arr_seo_angara)/count($arr_seo_angara),2,'.','');
                                  if ($srz_seo_ang>5) {
                                    $color_sr_seo_ang='red';
                                  }else{
                                    $color_sr_seo_ang='green';
                                  }
                                  $arr_seo_angara_50=0;
                                  $arr_seo_angara_10=0;
                                  $arr_seo_angara_5=0;
                                  $arr_seo_angara_3=0;
                                  foreach ($arr_seo_angara as $key => $arr_seo_angara_) {
                                    if ($arr_seo_angara_<=10) {
                                      $arr_seo_angara_10++;
                                    }else{
                                      $arr_seo_angara_50++;
                                    }
                                    if ($arr_seo_angara_<=5) {
                                      $arr_seo_angara_5++;
                                    }
                                    if ($arr_seo_angara_<=3) {
                                      $arr_seo_angara_3++;
                                    }
                                  }

                                  $arr_seo_zp=explode(';',rtrim($sr_seo_zp,';'));
                                  $srz_seo_zp=number_format(array_sum($arr_seo_zp)/count($arr_seo_zp),2,'.','');
                                  if ($srz_seo_zp>5) {
                                    $color_sr_seo_zp='red';
                                  }else{
                                    $color_sr_seo_zp='green';
                                  }
                                  $arr_seo_zp_50=0;
                                  $arr_seo_zp_10=0;
                                  $arr_seo_zp_5=0;
                                  $arr_seo_zp_3=0;
                                  foreach ($arr_seo_zp as $key => $arr_seo_zp_) {
                                    if ($arr_seo_zp_<=10) {
                                      $arr_seo_zp_10++;
                                    }else{
                                      $arr_seo_zp_50++;
                                    }
                                    if ($arr_seo_zp_<=5) {
                                      $arr_seo_zp_5++;
                                    }
                                    if ($arr_seo_zp_<=3) {
                                      $arr_seo_zp_3++;
                                    }
                                  }
                                // if ($seo_angara=='-' OR empty($seo_angara)) {
                                //
                                // }else{
                                //     $sr_seo_angara_prib .=$seo_angara . ';';
                                // }
                                // if ($seo_zp=='-' OR empty($seo_zp)) {
                                //
                                // }else{
                                //     $sr_seo_zp_prib .=$seo_zp . ';';
                                // }

                                if ($summ_zatrati1==0 OR $summ_marja1==0) {
                                  $roi_all1=0;
                                  $sredn_cpc1=0;
                                }else{
                                  $roi_all1=number_format($summ_marja1/$summ_zatrati1*100,2,'.','');
                                  $sredn_cpc1=number_format($summ_cpc1/$summ_zapros1,2,'.','');
                                }
                                if ($summ_prib1<0) {
                                  $color_summ_prib1='#dc2020';
                                }else{
                                  $color_summ_prib1='#256d20';
                                }
                                ?>
                                <tr>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                </tr>
                                <tr>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td>суммарно по плюсовым</td>

                                  <td>

                                    <div style="color:#256d20">Маржа: <?=$summ_marja?>р</div>
                                    <div style="color:#dc2020">Затраты: <?=$summ_zatrati?>р</div></td>
                                  <td>
                                    <div style="display:flex;justify-content:space-between">
                                      <div>Зап: <?=$summ_zapros?> || Пок: <?=$summ_pokaz?></div>
                                      <div>CPC: <?=$sredn_cpc?>р</div>
                                      <div style="color:<?=$color_summ_prib?>">Приб: <?=$summ_prib?>р</div>
                                      <div>ROI: <?=$roi_all?>%</div>
                                    </div>
                                  <!--SEO показатели-->
                                                    <div>
                                                      <div style="display:flex;justify-content:space-between;border-top:1px #ccc solid;margin-top:2px">
                                                        <div style="width:75px">
                                                          Angara77
                                                        </div>
                                                        <div style="width:60px;font-size:12px;overflow:hidden">
                                                          топ 3: <?=$arr_seo_angara_pr3?>
                                                        </div>
                                                        <div style="width:60px;font-size:12px;overflow:hidden">
                                                          топ 5: <?=$arr_seo_angara_pr5?>
                                                        </div>
                                                        <div style="width:60px;font-size:12px;overflow:hidden">
                                                          топ 10: <?=$arr_seo_angara_pr10?>
                                                        </div>
                                                        <div style="width:60px;font-size:12px;overflow:hidden">
                                                          50+: <?=$arr_seo_angara_pr50?>
                                                        </div>
                                                        <div>
                                                          <div style="width:75px">SEO: <b style="color:<?=$color_sr_seo_ang_prib?>"><?=$srz_seo_ang_prib?></b></div>
                                                        </div>
                                                      </div>
                                                      <div style="display:flex;justify-content:space-between;border-top:1px #ccc solid;margin-top:2px">
                                                        <div style="width:75px">
                                                          zapporter
                                                        </div>
                                                        <div style="width:60px;font-size:12px;overflow:hidden">
                                                          топ 3: <?=$arr_seo_zp_pr3?>
                                                        </div>
                                                        <div style="width:60px;font-size:12px;overflow:hidden">
                                                          топ 5: <?=$arr_seo_zp_pr5?>
                                                        </div>
                                                        <div style="width:60px;font-size:12px;overflow:hidden">
                                                          топ 10: <?=$arr_seo_zp_pr10?>
                                                        </div>
                                                        <div style="width:60px;font-size:12px;overflow:hidden">
                                                          50+: <?=$arr_seo_zp_pr50?>
                                                        </div>
                                                        <div>
                                                          <div  style="width:75px">SEO: <b style="color:<?=$color_sr_seo_zp_prib?>"><?=$srz_seo_zp_prib?></b></div>
                                                        </div>
                                                      </div>
                                                    </div>
                                </td>
                                </tr>
                                <tr>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td>суммарно по группе</td>

                                  <td>
                                    <div style="color:#256d20">Маржа: <?=$summ_marja1?>р</div>
                                    <div style="color:#dc2020">Затраты: <?=$summ_zatrati1?>р</div></td>
                                  <td>
                                    <div  style="display:flex;justify-content:space-between">
                                  <div>Зап: <?=$summ_zapros1?> || Пок: <?=$summ_pokaz1?></div>
                                  <div>CPC: <?=$sredn_cpc1?>р</div>
                                  <div style="color:<?=$color_summ_prib1?>">Приб: <?=$summ_prib1?>р</div>
                                  <div>ROI: <?=$roi_all1?>%</div>
                                </div>
                                    <!--SEO показатели-->
                                    <div>
                                      <div style="display:flex;justify-content:space-between;border-top:1px #ccc solid;margin-top:2px">
                                        <div style="width:75px">
                                          Angara77
                                        </div>
                                        <div style="width:60px;font-size:12px;overflow:hidden">
                                          топ 3: <?=$arr_seo_angara_3?>
                                        </div>
                                        <div style="width:60px;font-size:12px;overflow:hidden">
                                          топ 5: <?=$arr_seo_angara_5?>
                                        </div>
                                        <div style="width:60px;font-size:12px;overflow:hidden">
                                          топ 10: <?=$arr_seo_angara_10?>
                                        </div>
                                        <div style="width:60px;font-size:12px;overflow:hidden">
                                          50+: <?=$arr_seo_angara_50?>
                                        </div>
                                        <div>
                                          <div style="width:75px">SEO: <b style="color:<?=$color_sr_seo_ang?>"><?=$srz_seo_ang?></b></div>
                                        </div>
                                      </div>
                                      <div style="display:flex;justify-content:space-between;border-top:1px #ccc solid;margin-top:2px">
                                        <div style="width:75px">
                                          zapporter
                                        </div>
                                        <div style="width:60px;font-size:12px;overflow:hidden">
                                          топ 3: <?=$arr_seo_zp_3?>
                                        </div>
                                        <div style="width:60px;font-size:12px;overflow:hidden">
                                          топ 5: <?=$arr_seo_zp_5?>
                                        </div>
                                        <div style="width:60px;font-size:12px;overflow:hidden">
                                          топ 10: <?=$arr_seo_zp_10?>
                                        </div>
                                        <div style="width:60px;font-size:12px;overflow:hidden">
                                          50+: <?=$arr_seo_zp_50?>
                                        </div>
                                        <div>
                                          <div  style="width:75px">SEO: <b style="color:<?=$color_sr_seo_zp?>"><?=$srz_seo_zp?></b></div>
                                        </div>
                                      </div>
                                    </div>
                                </td>
                                </tr>
                                <?php
                                $sql='UPDATE ' . $table_reklama . ' SET
                                sr_seo_angara="' . $srz_seo_ang . '",
                                sr_seo_zp="' . $srz_seo_zp . '",
                                cpc="' . $sredn_cpc1 . '",
                                zaprosi="' . $summ_zapros1 . '",
                                zaprosi_prib="' . $summ_prib1 . '",
                                pokazi="' . $summ_pokaz1 . '",
                                roi="' . $roi_all1 . '"
                                WHERE group_id="' . $id . '" ';
                                // update_sql_db4($sql);
                                ?>

<?php }elseif($id!="-" AND $id>1000){
//ЕСЛИ ВЫБРАНА ГРУППА

      //ссылки на запчасти портер
                            $sql_url_zp_cat_category='SELECT DISTINCT b.Address,b.ang_name FROM ' . $table_zp_cat_progon . ' a JOIN ' . $table_zp_cat_url_id . ' b WHERE a.id=b.id AND a.category_id LIKE "%,' . $category['category_id'] . '%"';
                            $arr_url_zp_cat_category=select_sql_db4($sql_url_zp_cat_category);

                            $sql_url_zp_cat_subcat='SELECT DISTINCT b.Address,b.ang_name FROM ' . $table_zp_cat_progon . ' a JOIN ' . $table_zp_cat_url_id . ' b WHERE a.id=b.id AND a.subcat_id LIKE "%,' . $category['subcat_id'] . '%"';
                            $arr_url_zp_cat_subcat=select_sql_db4($sql_url_zp_cat_subcat);


                            $sql_url_zp_cat='SELECT DISTINCT b.Address,b.ang_name FROM ' . $table_zp_cat_progon . ' a JOIN ' . $table_zp_cat_url_id . ' b WHERE a.id=b.id AND a.group_id LIKE "%,' . $id . '%"';
                            $arr_url_zp_cat=select_sql_db4($sql_url_zp_cat);

                            $sql_url_zp_tov='SELECT DISTINCT b.Address,b.ang_name FROM ' . $table_zp_tovar_progon . ' a JOIN ' . $table_zp_tovar_url_id . ' b WHERE a.id=b.id AND a.group_id LIKE "%,' . $id . '%"';
                            $arr_url_zp_tov=select_sql_db4($sql_url_zp_tov);

                          ?>
                          <tr>
                            <td><?php foreach ($arr_url_zp_cat_category as $key => $value) {?>
                              <div>
                                <a style="font-size:13px;" href="<?=$value['Address']?>"><?=$value['ang_name']?></a>
                              </div>
                              <?php }?>
                            </td>
                            <td><?php foreach ($arr_url_zp_cat_subcat as $key => $value) {?>
                              <div>
                                <a style="font-size:13px;" href="<?=$value['Address']?>"><?=$value['ang_name']?></a>
                              </div>
                              <?php }?>
                            </td>
                            <td><?php foreach ($arr_url_zp_cat as $key => $value) {?>
                              <div>
                                <a style="font-size:13px;" href="<?=$value['Address']?>"><?=$value['ang_name']?></a>
                              </div>
                              <?php }?>
                            </td>



                            <td><?php
                              $arr_url_cat='';
                              foreach ($arr_url_zp_tov as $key => $value) {
                              $preg=preg_replace("/([a-z].*)[\/](part)[\/]([0-9]*)[\/]([a-z].*)[\/]/i",'$3',$value['Address']);
                              // p($preg);
                              $sql='SELECT parent FROM angara WHERE 1c_id="' . $preg . '"';
                              $id_1c=select_sql_db4($sql);

                              $id_1c='/porterparts/' . $id_1c[0]['parent'] . '/';
                              $sql2='SELECT * FROM ' . $table_zp_cat_url_id . ' WHERE Address LIKE "%' . $id_1c . '%"';
                              $res_sql2=select_sql_db4($sql2);
                              $url_zp_tov_cat=$res_sql2[0]['Address'];
                              $name_zp_tov_cat=$res_sql2[0]['ang_name'];
                                $arr_url_cat .=$url_zp_tov_cat . '`;`' . $name_zp_tov_cat . '`,`';
                                ?>
                                <div>
                                  <a style="font-size:13px;" href="<?=$value['Address']?>"><?=$value['ang_name']?></a>
                                </div>
                                <?php
                              }
                              $res=rtrim($arr_url_cat,'`,`');
                              $ex=explode('`,`',$res);
                              $ar_count_val=array_count_values($ex);
                              $summ=array_sum($ar_count_val);
                              $max=max($ar_count_val);
                              $percent=$max/$summ*100 . "%";
                              foreach ($ar_count_val as $key => $value) {

                                if ($value==$max) {
                                  $expkeyzp=explode('`;`',$key);
                                  $name_cat_zp=$expkeyzp[1];
                                  $url_cat_zp=$expkeyzp[0];
                                }
                              }
                              ?>
                              <?php if(isset($url_cat_zp) AND !empty($url_cat_zp)){
                                $url_obyavlenia=$url_cat_zp;
                              }elseif (isset($arr_url_zp_cat[0]['Address']) AND !empty($arr_url_zp_cat[0]['Address'])) {
                                $url_obyavlenia=$arr_url_zp_cat[0]['Address'];
                              }elseif (isset($arr_url_zp_cat_subcat[0]['Address']) AND !empty($arr_url_zp_cat_subcat[0]['Address'])) {
                                $url_obyavlenia=$arr_url_zp_cat_subcat[0]['Address'];
                              }elseif (isset($arr_url_zp_cat_category[0]['Address']) AND !empty($arr_url_zp_cat_category[0]['Address'])) {
                                $url_obyavlenia=$arr_url_zp_cat_category[0]['Address'];
                              }?>


                            <td>
                              <div>
                              <a style="font-size:13px;" href="<?=$url_cat_zp?>"><?=$name_cat_zp?> [<?=$percent?>]</a>
                            </div>
                          </td>
                            </td>
                            <td>
                            URL объявления: <br>
                          <?=$url_obyavlenia?></td>
                          <td></td>
                          </tr>


                          <?php
//ссылки на ангару77
                          $sql_url_angara_cat_category='SELECT DISTINCT b.Address,b.ang_name FROM ' . $table_angara_cat_progon . ' a JOIN ' . $table_angara_cat_url_id . ' b WHERE a.id=b.id AND a.category_id LIKE "%,' . $category['category_id'] . '%"';
                          $arr_url_angara_cat_category=select_sql_db4($sql_url_angara_cat_category);

                          $sql_url_angara_cat_subcat='SELECT DISTINCT b.Address,b.ang_name FROM ' . $table_angara_cat_progon . ' a JOIN ' . $table_angara_cat_url_id . ' b WHERE a.id=b.id AND a.subcat_id LIKE "%,' . $category['subcat_id'] . '%"';
                          $arr_url_angara_cat_subcat=select_sql_db4($sql_url_angara_cat_subcat);


                          $sql_url_angara_cat='SELECT DISTINCT b.Address,b.ang_name FROM ' . $table_angara_cat_progon . ' a JOIN ' . $table_angara_cat_url_id . ' b WHERE a.id=b.id AND a.group_id LIKE "%,' . $id . '%"';
                          $arr_url_angara_cat=select_sql_db4($sql_url_angara_cat);

                          $sql_url_angara_tov='SELECT DISTINCT b.Address,b.ang_name FROM ' . $table_angara_tovar_progon . ' a JOIN ' . $table_angara_tovar_url_id . ' b WHERE a.id=b.id AND a.group_id LIKE "%,' . $id . '%"';
                          $arr_url_angara_tov=select_sql_db4($sql_url_angara_tov);

                        ?>
                        <tr>
                          <td><?php foreach ($arr_url_angara_cat_category as $key => $value) {?>
                            <div>
                              <a style="font-size:13px;" href="<?=$value['Address']?>"><?=$value['ang_name']?></a>
                            </div>
                            <?php }?>
                          </td>
                          <td><?php foreach ($arr_url_angara_cat_subcat as $key => $value) {?>
                            <div>
                              <a style="font-size:13px;" href="<?=$value['Address']?>"><?=$value['ang_name']?></a>
                            </div>
                            <?php }?>
                          </td>
                          <td><?php foreach ($arr_url_angara_cat as $key => $value) {?>
                            <div>
                              <a style="font-size:13px;" href="<?=$value['Address']?>"><?=$value['ang_name']?></a>
                            </div>
                            <?php }?>
                          </td>



                          <td>


                            <?php
                            $arr_url_cat='';
                            foreach ($arr_url_angara_tov as $key => $value) {
                            $preg=preg_replace("/([a-z].*)[\/](porter-)([\d\w]*)-([0-9].*)[\/]/i",'$4',$value['Address']);
                            // p($preg);
                            $sql='SELECT parent FROM angara WHERE 1c_id="' . $preg . '"';
                            $id_1c=select_sql_db4($sql);
                            // p($id_1c[0]['parent']);
                            $id_1c_porter='/subcat-1-' . $id_1c[0]['parent'] . '';
                            $id_1c_porter2='/subcat-5-' . $id_1c[0]['parent'] . '';
                            // p($id_1c_porter);
                            $sql2='SELECT * FROM ' . $table_angara_cat_url_id . ' WHERE Address LIKE "%' . $id_1c_porter . '%"';// OR Address LIKE "%' . $id_1c_porter2 . '%"
                            // p($sql2);
                            $res_sql2=select_sql_db4($sql2);
                            $url_angara_tov_cat=$res_sql2[0]['Address'];
                            // p($url_angara_tov_cat);
                            $name_angara_tov_cat=$res_sql2[0]['ang_name'];
                              $arr_url_cat .=$url_angara_tov_cat . '`;`' . $name_angara_tov_cat . '`,`';
                              ?>
                              <div>
                                <a style="font-size:13px;" href="<?=$value['Address']?>"><?=$value['ang_name']?></a>
                              </div>
                              <?php
                            }
                            $res=rtrim($arr_url_cat,'`,`');
                            $ex=explode('`,`',$res);
                            $ar_count_val=array_count_values($ex);
                            $summ=array_sum($ar_count_val);
                            $max=max($ar_count_val);
                            $percent=$max/$summ*100 . "%";
                            foreach ($ar_count_val as $key => $value) {

                              if ($value==$max) {
                                // p($key);
                                $exvalang=explode('`;`',$key);
                                $name_cat_angara=trim($exvalang[1],'`');
                                //preg_replace("/[`](.*)[`][;][`]([\d\w\D\s\S\W]*)[`]/i",'$2',$key);
                                $url_cat_angara=trim($exvalang[0],'`');
                                //preg_replace("/[`](.*)[`][;][`]([\d\w\D\s\S\W]*)[`]/i",'$1',$key);

                              }
                            }
                            ?>
                            <?php if(isset($url_cat_angara) AND !empty($url_cat_angara)){
                              $url_obyavlenia_angara=$url_cat_angara;
                            }elseif (isset($arr_url_angara_cat[0]['Address']) AND !empty($arr_url_angara_cat[0]['Address'])) {
                              $url_obyavlenia_angara=$arr_url_angara_cat[0]['Address'];
                            }elseif (isset($arr_url_angara_cat_subcat[0]['Address']) AND !empty($arr_url_angara_cat_subcat[0]['Address'])) {
                              $url_obyavlenia_angara=$arr_url_angara_cat_subcat[0]['Address'];
                            }elseif (isset($arr_url_angara_cat_category[0]['Address']) AND !empty($arr_url_angara_cat_category[0]['Address'])) {
                              $url_obyavlenia_angara=$arr_url_angara_cat_category[0]['Address'];
                            }?>


                          <td>
                            <div>
                            <a style="font-size:13px;" href="<?=$url_cat_angara?>"><?=$name_cat_angara?> [<?=$percent?>]</a>
                          </div>
                        </td>
                          </td>
                          <td>
                          URL объявления ANGARA77: <br>
                        <?=$url_obyavlenia_angara?></td>
                        <td></td>
                        </tr>
                  <!--ключи -->
                            <?php
                            $pribil_all='';
                            $zaprosi_all='';
                            $pokazi_all='';
                            $cpc_all='';
                            $marja_all='';
                            $zatrati_all='';
                            $pribil_all1='';
                            $zaprosi_all1='';
                            $pokazi_all1='';
                            $cpc_all1='';
                            $marja_all1='';
                            $zatrati_all1='';
                            $sr_seo_angara_prib='';
                            $sr_seo_zp_prib='';
                            $sr_seo_angara='';
                            $sr_seo_zp='';
                              $sql='SELECT DISTINCT keywords FROM ' . $table_keywords . ' WHERE ' . $razdel . '=' . $id . ' AND danet=0';
                              $keywords=select_sql_db4($sql);
                              foreach ($keywords as $key => $keyword) {
                  //ключевое слово

                                $or_predlogi="на|с|для|в|и";

                                $ex_keywords=explode(" ",$keyword['keywords']);
                                // p($ex_keywords);
                                $a='';
                                $sql_where='';
                                $if_preg='';
                                  foreach ($ex_keywords as $key => $ex_key) {
                                    $a .=$ex_key . ',';
                                    $sql_where .='AND keyword LIKE "%' . $ex_key . '%" ';

                                    if (iconv_strlen($ex_key)<=4) {
                                      $tochk="";
                                    }elseif (iconv_strlen($ex_key)<=6){
                                      $tochk="..";
                                    }elseif (iconv_strlen($ex_key)<=9){
                                      $tochk="..";
                                    }else{
                                      $tochk="..";
                                    }
                                    $preg_repl=preg_replace("/(.*)(" . $tochk . ")$/i","$1",$ex_key);
                                    $if_preg .=$preg_repl . '|';
                                    // p($if_preg);
                                    // p($ex_key);
                                    // p(iconv_strlen($ex_key));

                                  }
                                  $res_if_preg=rtrim($if_preg,'|');
                                  $sql='SELECT keyword FROM reklama_done WHERE ' . ltrim($sql_where,'AND') . ' AND keyword!="' . $keyword['keywords'] . '" AND id_rk!=10';
                                  $perececheniya=select_sql_db4($sql);
                                  // echo " ---------------------------------" . $keyword['keywords'] . " ---------------------------------";
                                  // p($res_if_preg);
                                  $b='';
                                  foreach ($perececheniya as $key => $per) {
                                    $b .=$per['keyword'] . ' ';
                                  }

                                  $ex_b=explode(' ',rtrim($b,' '));
                                  // p($ex_b);
                                  $if_preg2='';
                                    foreach ($ex_b as $key => $ex_bb) {
                                      $if_preg2 .=$ex_bb . '|';
                                    }
                                    $res_if_preg2=rtrim($if_preg2,'|');

                                  $ccc='';
                                  foreach ($ex_b as $key => $exb) {
                                      $preg_repl=preg_replace("/(.*)(" . $res_if_preg . ")(.*)/i","qwertyuiop",$exb);

                                      if ($preg_repl=="qwertyuiop" OR $exb=="на" OR $exb=="с" OR $exb=="для" OR $exb=="в" OR $exb=="и") {
                                        // echo "`" . $res_if_preg . "` содержиться в `" . $exb . "` или предлог";
                                        // p($exb);
                                      }else{
                                        $ccc .=$exb . ' ';
                                      }
                                  }
                                  $res_ccc=rtrim($ccc,' ');
                                  $ex_res_ccc=array_unique(explode(' ',$res_ccc));
                                  // p($ex_res_ccc);
                                  // echo "--------------------------------------------!!!!!!!!!!1---------------------";


                                   $cccc='';
                                     foreach ($ex_res_ccc as $key => $exresccc) {
                                       // echo "минус слово: ";
                                       // echo "`" . $exresccc . "`";
                                       if (iconv_strlen($ex_key)<=4) {
                                         $tochk="";
                                       }elseif (iconv_strlen($ex_key)<=6){
                                         $tochk="..";
                                       }elseif (iconv_strlen($ex_key)<=9){
                                         $tochk="..";
                                       }else{
                                         $tochk="..";
                                       }
                                       $minus_tochk=preg_replace("/(.*)(" . $tochk . ")$/i","$1",$exresccc);

                                       $minus_res_if_preg=$res_if_preg . '|' . $minus_tochk;
                                       // echo "________КОМБИНАЦИЯ : `" . $minus_res_if_preg . "`";
                                       $ect6=0;
                                       foreach ($perececheniya as $key => $per) {
                                         $ex_per=explode(" ",$per['keyword']);
                                         $ex_per_count=count($ex_per);
                                         $c=0;
                                         foreach ($ex_per as $key => $ex_per_word) {
                                           // p($ex_per_word);
                                           $minus_all_preg_repl=preg_replace("/(.*)(" . $minus_res_if_preg . ")(.*)/i","qwertyuiop",$ex_per_word);
                                           $minus_all_preg_repl=preg_replace("/(" . $or_predlogi . ")$/i","qwertyuiop",$minus_all_preg_repl);

                                           // p($minus_all_preg_repl);
                                           if ($minus_all_preg_repl=="qwertyuiop") {
                                             $c++;
                                           }else {}
                                         }
                                         if ($c==$ex_per_count) {
                                           $ect6++;
                                         }else{}
                                           // p($c);
                                           // p($ex_per_count);
                                           // echo "-------++++++++++++==========";
                                       }
                                       if ($ect6>0) {
                                          $cccc .='-' . $exresccc . ' ';
                                       }else{
                                         // echo "!!!!!!----------------------------нет такой комбинации слов `" . $minus_res_if_preg . "` ----------------------------!!!!!!";
                                       }

                                     }




                                     $res_cccc1=rtrim($cccc,'- ');
                                   $res_cccc=rtrim($res_cccc1,' ');
                                   $keys=$res_cccc;
                                   //не трогать без МЕНЯ ЭТО РАБОТАЕТ!!!!!!

                                  // p($keys);
                                  // p($perececheniya);
                  //позиции
                                $sql_seo_angara='SELECT DISTINCT * FROM ' . $table_seo_angara . ' WHERE keyword="' . $keyword['keywords'] . '"';
                                $seo_angara_arr=select_sql_db4($sql_seo_angara);
                                $seo_angara=$seo_angara_arr[0]['position'];
                                $seo_url_angara=$seo_angara_arr[0]['url'];
                                if ($seo_angara>=5 OR $seo_angara=='-' OR empty($seo_angara)) {
                                  $color_seo_angara="#dc2020";

                                }else{
                                  $color_seo_angara="#256d20";

                                }
                                $sql_seo_zp='SELECT DISTINCT * FROM ' . $table_seo_zapchastiporter . ' WHERE keyword="' . $keyword['keywords'] . '"';
                                $seo_zp_arr=select_sql_db4($sql_seo_zp);
                                $seo_zp=$seo_zp_arr[0]['position'];
                                $seo_url_zp=$seo_zp_arr[0]['url'];
                                if ($seo_zp>5 OR $seo_zp=='-' OR empty($seo_zp)) {
                                  $color_seo_zp="#dc2020";
                                }else{
                                  $color_seo_zp="#256d20";
                                }
                                ?>

                                <tr>
                                  <td></td>
                                  <td></td>

                                  <?php
                  //ОБЬЯВЛЕНИЕ
                                  $str_repl_keyw=str_ireplace('hyundai porter','Porter',$keyword['keywords']);
                                  $str_repl_keyw=str_ireplace('porter hyundai','Porter',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace('хендай портер','Портер',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace('портер хендай','Портер',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace('хундай портер','Портер',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace('портер хундай','Портер',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace('hyundai','Hyundai',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace('porter','Porter',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace('хендай','Хендай',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace('портер','Портер',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace('гбц ','ГБЦ ',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace('тнвд ','ТНВД ',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace('тагаз ','Тагаз ',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace('егр ','ЕГР ',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace(' гбц',' ГБЦ',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace(' тнвд',' ТНВД',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace(' тагаз',' Тагаз',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace(' егр',' ЕГР',$str_repl_keyw);
                                  $str_repl_keyw=str_ireplace(' москв',' Москв',$str_repl_keyw);

                                  $title_obyavlenia1=ucfirst($str_repl_keyw);
                                            $title_words=explode(' ',$title_obyavlenia1);
                                            $max=count($title_words)-1;
                                            $words_ar='';
                                            foreach ($title_words as $key => $word1) {
                                              if($key==$max){

                                              }else{
                                                $words_ar .=$word1 . ' ';
                                              }
                                            }
                                          $title_obyavlenia2=ucfirst(rtrim($words_ar,' '));
                                          $title_obyavlenia2=preg_replace("/(.*)([ ])(на|для|и)$/i","$1",$title_obyavlenia2);
                                            $title_words2=explode(' ',$title_obyavlenia2);

                                            $max2=count($title_words2)-1;

                                            $words_ar2='';
                                            foreach ($title_words2 as $key => $word2) {
                                              if($key==$max2){

                                              }else{
                                                $words_ar2 .=$word2 . ' ';
                                              }
                                            }
                                          $title_obyavlenia3=ucfirst(rtrim($words_ar2,' '));
                                          $title_obyavlenia3=preg_replace("/(.*)([ ])(на|для|и)$/i","$1",$title_obyavlenia3);
                                            $title_words3=explode(' ',$title_obyavlenia3);
                                            $max3=count($title_words3)-1;
                                            $words_ar3='';
                                            foreach ($title_words3 as $key => $word3) {
                                              if($key==$max3){

                                              }else{
                                                $words_ar3 .=$word3 . ' ';
                                              }
                                            }
                                          $title_obyavlenia4=ucfirst(rtrim($words_ar3,' '));
                                          $title_obyavlenia4=preg_replace("/(.*)([ ])(на|для|и)$/i","$1",$title_obyavlenia4);
                                            if (iconv_strlen($title_obyavlenia1)<=$title1_count) {
                                              $title_obyavlenia=$title_obyavlenia1;
                                            }elseif(iconv_strlen($title_obyavlenia2)<=$title1_count){
                                              $title_obyavlenia=$title_obyavlenia2;
                                            }elseif(iconv_strlen($title_obyavlenia3)<=$title1_count){
                                              $title_obyavlenia=$title_obyavlenia3;
                                            }elseif(iconv_strlen($title_obyavlenia4)<=$title1_count){
                                              $title_obyavlenia=$title_obyavlenia4;
                                            }
                                            $title_obyavlenia=preg_replace("/(.*)([ ])(цена|цены)$/i","$1",$title_obyavlenia);
                                            $title_obyavlenia=preg_replace("/(цена|цены)([ ])(.*)/i","$3",$title_obyavlenia);
                                            $first=mb_strtoupper(mb_substr($title_obyavlenia, 0, 1));
                                            $all=mb_substr($title_obyavlenia, 1);
                                            $title_obyavlenia=$first . $all;


                                  $title2_obyavlenia="В наличии!";
                                  $str_repl_keyw=preg_replace("/(.*)([ ])(цена)$/i","$1",$str_repl_keyw);
                                  $str_repl_keyw=preg_replace("/(цена)([ ])(.*)/i","$3",$str_repl_keyw);
                                  $text_obyavlenia1=ucfirst($str_repl_keyw . " – Недорого. Доставка 290р + 100% гарантия возврата!");
                                  $text_obyavlenia2=ucfirst($str_repl_keyw . " – Недорого. Доставка 290р + 100% гарантия возврата");
                                  $text_obyavlenia3=ucfirst($str_repl_keyw . " – Доставка 290р + 100% гарантия возврата");
                                  $text_obyavlenia4=ucfirst("97% запчастей на Портер в наличии – Доставка 290р + 100% гарантия возврата!");
                                          if (iconv_strlen($text_obyavlenia1)<=$text_count) {
                                            $text_obyavlenia=ucfirst($text_obyavlenia1);
                                          }elseif(iconv_strlen($text_obyavlenia2)<=$text_count){
                                            $text_obyavlenia=ucfirst($text_obyavlenia2);
                                          }elseif(iconv_strlen($text_obyavlenia3)<=$text_count){
                                            $text_obyavlenia=ucfirst($text_obyavlenia3);
                                          }elseif(iconv_strlen($text_obyavlenia4)<=$text_count){
                                            $text_obyavlenia=ucfirst($text_obyavlenia4);
                                          }
                                          $first=mb_strtoupper(mb_substr($text_obyavlenia, 0, 1));
                                          $all=mb_substr($text_obyavlenia, 1);
                                          $text_obyavlenia=$first . $all;
                                  $patch1="https://zapchastiporter.ru";
                                  $patch21=ucfirst($category['group_name']);
                                          $patch21_words=explode(' ',$patch21);
                                          $max=count($patch21_words)-1;
                                          $words_ar='';
                                          foreach ($patch21_words as $key => $word) {
                                            if($key==$max){

                                            }else{
                                              $words_ar .=$word . ' ';
                                            }
                                          }
                                          $patch22=ucfirst(rtrim($words_ar,' '));
                                            $patch22_words=explode(' ',$patch22);
                                            $max2=count($patch22_words)-1;
                                            $words_ar2='';
                                            foreach ($patch22_words as $key => $word2) {
                                              if($key==$max2){

                                              }else{
                                                $words_ar2 .=$word2 . ' ';
                                              }
                                            }
                                          $patch23=ucfirst(rtrim($words_ar2,' '));
                                          $name_cat_zp=str_ireplace(" для "," ",$name_cat_zp);
                                          $name_cat_zp=str_ireplace(" на "," ",$name_cat_zp);
                                          $name_cat_zp=str_ireplace(" Хендай "," ",$name_cat_zp);
                                          $name_cat_zp=str_ireplace("hyundai"," ",$name_cat_zp);
                                          $name_cat_zp=str_ireplace("porter"," ",$name_cat_zp);
                                          $name_cat_zp2=str_ireplace(" портер "," ",$name_cat_zp);
                                          $name_cat_zp2=preg_replace("/(.*)([ ])(портер|Портер)$/i","$1",$name_cat_zp2);
                                          $name_cat_zp3=preg_replace("/(.*)([ ])(.*)$/i","$1",$name_cat_zp2);
                                          if (iconv_strlen($patch21)<=$patch2_count) {
                                            $patch2=ucfirst($patch21);
                                          }elseif (iconv_strlen($patch22)<=$patch2_count) {
                                            $patch2=ucfirst($patch22);
                                          }elseif (iconv_strlen($patch23)<=$patch2_count) {
                                            $patch2=ucfirst($patch23);
                                          }elseif(iconv_strlen($name_cat_zp)<=$patch2_count){
                                            $patch2=$name_cat_zp;
                                          }elseif(iconv_strlen($name_cat_zp2)<=$patch2_count){
                                            $patch2=$name_cat_zp2;
                                          }else{
                                            $patch2=$name_cat_zp3;
                                          }
                                          $patch2=preg_replace("/(.*)([ ])(портер|Портер|на|для|и|.)$/i","$1",trim($patch2,' '));
                                          $patch2=preg_replace("/(.*)([ ])(портер|Портер)$/i","$1",$patch2);

                                          $first=mb_strtoupper(mb_substr($patch2, 0, 1));
                                          $all=mb_substr($patch2, 1);
                                          $patch2=$first . $all;
                                          $patch2=str_ireplace(" ","-",$patch2);
                                          $patch2=str_ireplace(",","-",$patch2);
                                          $patch2=str_ireplace("--","-",$patch2);
                                          $patch2=str_ireplace("--","-",$patch2);
                                          $patch2=str_ireplace("--","-",$patch2);
                                          $patch2=trim($patch2,"-");
                                  $title_obyavlenia_simv=iconv_strlen($title_obyavlenia);
                                  $title2_obyavlenia_simv=iconv_strlen($title2_obyavlenia);
                                  $text_obyavlenia_simv=iconv_strlen($text_obyavlenia);
                                  $patch2_simv=iconv_strlen($patch2);
                                  if (isset($title_obyavlenia_simv)) {
                                  if ($title_obyavlenia_simv>$title1_count) {
                                    $title_simv_color="red";
                                  }else{
                                    $title_simv_color="green";
                                  }
                                  }
                                  if (isset($title2_obyavlenia_simv)) {
                                  if ($title2_obyavlenia_simv>$title2_count) {
                                    $title2_simv_color="red";
                                  }else{
                                    $title2_simv_color="green";
                                  }
                                  }
                                  if (isset($text_obyavlenia_simv)) {
                                  if ($text_obyavlenia_simv>$text_count) {
                                    $text_simv_color="red";
                                  }else{
                                    $text_simv_color="green";
                                  }
                                  }
                                  if (isset($text_obyavlenia_simv)) {
                                  if ($patch2_simv>$patch2_count) {
                                    $patch2_simv_color="red";
                                  }else{
                                    $patch2_simv_color="green";
                                  }
                                  }
                                  // $title1_count=35;
                                  // $title2_count=30;
                                  // $text_count=81;
                                  // $url_count=1017;
                                  // $patch2_count=20;
                                  // $all_fast_url_title_count=66;
                                  // $fast_url_text_count=60;

                                  ?>
                                  <td>
                                    <h2 class="zagolovok"><b style="color:<?=$title_simv_color?>">[<?=$title_obyavlenia_simv?>]</b> – <b style="color:<?=$title2_simv_color?>">[<?=$title2_obyavlenia_simv?>]</b></h2>
                                    <p style=" margin: 0;    padding: 0;font-size: 14px;font-family: Arial,Helvetica,sans-serif;white-space: nowrap;color:<?=$patch2_simv_color?>">[<?=$patch2_simv?>]</p>
                                    <p class="description" style="color:<?=$text_simv_color?>">[<?=$text_obyavlenia_simv?>]</p>
                                  </td>
                                  <td style="width:536px;overflow:break-word;line-height: 17px">
                                    <h2 class="zagolovok"><a><?=$title_obyavlenia?> – <?=$title2_obyavlenia?></a></h2>
                                    <p style=" margin: 0;    padding: 0;font-size: 14px;font-family: Arial,Helvetica,sans-serif;color:#070;    white-space: nowrap;">
                                      <a style="color:#070;    list-style: none;text-decoration: none" href="<?=$url_obyavlenia?>"><b><?=$patch1?></b> › <text class="url2"><?=$patch2?></text></a>
                                    </p>
                                    <p class="description"><?=$text_obyavlenia?></p>
                                    <!-- <p class="description"><?=$str_repl_keyw?> – Недорого, оригинал и аналог. Доставка по Москве и России +100% гарантия возврата!</p> -->
                                  </td>

                                  <td>


                                    <?=$keyword['keywords']?>
                                    <?=$keys?>


                                    <form action="" method="get">
                                      <input hidden name="do" value="<?=$_GET['do']?>"></input>
                                      <input hidden name="table" value="<?=$_GET['table']?>"></input>
                                      <input type="radio" name="danet_f" value="1"></input>
                                      <input hidden name="keyword" value="<?=$keyword['keywords']?>"></input>
                                      <input hidden name="gr_id" value="<?=$id?>"></input>


                                      <input type="submit" value="удалить категорию!">
                                    </form>
                                    <?php if (isset($_GET['danet_f']) AND $_GET['danet_f']==1 AND $_GET['keyword']==$keyword['keywords'] AND $_GET['gr_id']==$id) {
                                      $sql_danet='UPDATE ' . $table_keywords . ' SET danet=1 WHERE keywords="' . $keyword['keywords'] . '" AND group_id=' . $id;
                                      echo '<p style="background-color:#ffa8a9">удалено</p>';
                                      update_sql_db4($sql_danet);
                                    }?>








                                    <?php
                                    $sql='SELECT DISTINCT * FROM ' . $table_kernel_direct . ' WHERE keyword="' . $keyword['keywords'] . '"';
                                    $metriki=select_sql_db4($sql);

                                    ?>

                                  </td>
                  <!--метрики -->
                                  <td>
                                    <div style="display:flex;justify-content:space-between">
                                      <?php if (isset($metriki[0]['zaprosi']) AND $metriki[0]['zaprosi']!=0) {
                                        $cpc=str_ireplace(',','.',$metriki[0]['click_price_final']);
                                        $cpc1=str_ireplace(',','.',$metriki[0]['click_price_final']);
                                        $cpc9=str_ireplace(',','.',$metriki[0]['click_price_62']);
                                        $zaprosi=$metriki[0]['zaprosi'];
                                        $zaprosi1=$metriki[0]['zaprosi'];
                                        $zaprosi2=$metriki[0]['zaprosi'];
                                        $pokazi=$metriki[0]['pokaz_100'];
                                        $pokazi1=$metriki[0]['pokaz_100'];
                                        $pokazi2=$metriki[0]['pokaz_100'];
                                        $marja=$zaprosi*$categorys[0]['conversion_sites']*$categorys[0]['conversion']/100*$categorys[0]['sr_prib_good'];
                                        $marja1=$zaprosi*$categorys[0]['conversion_sites']*$categorys[0]['conversion']/100*$categorys[0]['sr_prib_good'];
                                        $marja2=$zaprosi2*$categorys[0]['conversion_sites']*$categorys[0]['conversion']/100*$categorys[0]['sr_prib_good'];
                                        $zatrati=$zaprosi*$cpc;
                                        $zatrati1=$zaprosi*$cpc;
                                        $zatrati2=$zaprosi2*$cpc9;
                                        // p($metriki[0]['zaprosi']*$categorys[0]['conversion_sites']);
                                        $pribil=number_format($marja-$zatrati,2,'.','');
                                        $pribil1=number_format($marja-$zatrati,2,'.','');
                                        $pribil2=number_format($marja2-$zatrati2,2,'.','');
                                        $max_cpc=number_format($marja/$zaprosi,0,'.','');

                                        if ($zatrati<=0) {
                                          $roi=0;
                                          $roi2=0;
                                        }else{
                                        $roi=number_format($marja/$zatrati*100,2,'.','');
                                        $roi2=number_format($marja/$zatrati2*100,2,'.','');
                                        }
                                        $obiem=75;
                                          if ($pribil<=0) {
                                            // p($cpc);
                                            $cpc=$cpc9;
                                            $zatrati=$zatrati2;
                                            $pribil=$pribil2;
                                            $roi=$roi2;
                                            $marja=$marja2;
                                            $zaprosi=$zaprosi2;
                                            $pokazi=$pokazi2;
                                            $obiem=9;
                                          }
                                        $pribil_all1 .=$pribil1 . ';';
                                        $marja_all1 .=$marja1 . ';';
                                        $zaprosi_all1 .=$zaprosi1 . ';';
                                        $pokazi_all1 .=$pokazi1 . ';';
                                        $cpc_all1 .=$cpc1*$zaprosi1 . ';';
                                        $zatrati_all1 .=$zatrati1 . ';';
                                                if ($seo_angara=='-' OR empty($seo_angara)) {

                                                }else{
                                                    $sr_seo_angara .=$seo_angara . ';';
                                                }
                                                if ($seo_zp=='-' OR empty($seo_zp)) {

                                                }else{
                                                    $sr_seo_zp .=$seo_zp . ';';
                                                }
                                        if ($pribil<=0) {

                                          $color='#dc2020';
                                        }else{
                                                if ($seo_angara=='-' OR empty($seo_angara)) {

                                                }else{
                                                    $sr_seo_angara_prib .=$seo_angara . ';';
                                                }
                                                if ($seo_zp=='-' OR empty($seo_zp)) {

                                                }else{
                                                    $sr_seo_zp_prib .=$seo_zp . ';';
                                                }
                                          $pribil_all .=$pribil . ';';
                                          $marja_all .=$marja . ';';
                                          $zaprosi_all .=$metriki[0]['zaprosi'] . ';';
                                          $pokazi_all .=$metriki[0]['pokaz_100'] . ';';
                                          $cpc_all .=$cpc*$metriki[0]['zaprosi'] . ';';
                                          $zatrati_all .=$zatrati . ';';
                                          $color='#256d20';

                                        }
                                        ?>

                                        <div style="min-width:120px">Зап: <?=$metriki[0]['zaprosi']?>|<?=$zaprosi?><br>Пок: <?=$metriki[0]['pokaz_100']?> | <?=$pokazi?></div>
                                        <div style="min-width:150px">CPC: <?=$cpc?>р(<?=$metriki[0]['click_price_final']?>р)</div>
                                        <div style="min-width:150px">max-CPC: <?=$max_cpc?>р</div>
                                        <div style="color:<?=$color?>;min-width:120px">Приб: <?=$pribil?>р</div>
                                        <div style="min-width:120px">ROI: <?=$roi?>%</div>
                                        <div style="min-width:75px"><?=$obiem?>%</div>
                                      <?php }else{
                                        $cpc=0;
                                        $zatrati=0;
                                        $pribil=0;
                                        $roi=0;
                                        $marja=0;
                                        $zaprosi=0;
                                        $pokazi=0;
                                        $obiem=9;
                                        $max_cpc=0;

                                        $cpc1=0;
                                        $zatrati1=0;
                                        $pribil1=0;
                                        $roi1=0;
                                        $marja1=0;
                                        $zaprosi1=0;
                                        $pokazi1=0;
                                        $obiem1=75;

                                    $pribil_all1 .=$pribil1 . ';';
                                    $marja_all1 .=$marja1 . ';';
                                    $zaprosi_all1 .=$zaprosi1 . ';';
                                    $pokazi_all1 .=$pokazi1 . ';';
                                    $cpc_all1 .=$cpc1*$zaprosi1 . ';';
                                    $zatrati_all1 .=$zatrati1 . ';';
                                      $pribil_all .=$pribil . ';';
                                      $marja_all .=$marja . ';';
                                      $zaprosi_all .=$zaprosi . ';';
                                      $pokazi_all .=$pokazi . ';';
                                      $cpc_all .=$cpc*$zaprosi . ';';
                                      $zatrati_all .=$zatrati . ';';
                                        ?>
                                        <div style="min-width:120px">Зап: 0 || Пок: ~~ </div><div>CPC: ~~ р</div>
                                        <div style="min-width:150px">CPC: -р)</div>
                                        <div style="min-width:150px">max-CPC: -р</div>
                                        <div style="color:<?=$color?>;min-width:120px">Приб: -р</div>
                                        <div style="min-width:120px">ROI: -%</div>
                                        <div style="min-width:75px"><?=$obiem?>%</div>

                                      <?php }?>
                                    </div>
                  <!--SEO показатели-->
                                    <?php
                                    if ($seo_zp=="-" OR $seo_zp=="" OR $seo_zp==0) {
                                      $seo_zp="100";
                                    }
                                    if ($seo_angara=="-" OR $seo_angara=="" OR $seo_angara==0) {
                                      $seo_angara="100";
                                    }
                                    ?>
                                    <div>
                                      <div style="display:flex;justify-content:space-between;border-top:1px #ccc solid;margin-top:2px">
                                        <div style="width:75px">
                                          Angara77
                                        </div>
                                        <div style="width:300px;font-size:12px;overflow:hidden">
                                          <?php if (isset($seo_url_angara) AND $seo_url_angara!='-') {
                                          ?>
                                          <a href="http://angara77.com<?=$seo_url_angara?>" target="_blank"><?=$seo_url_angara?></a>
                                        <?php }else{?>
                                          <b style="color:#dc2020">НЕТ URL</b>
                                        <?php } ?>
                                        </div>
                                        <div>
                                          <div style="width:75px">SEO: <b style="color:<?=$color_seo_angara?>"><?=$seo_angara?></b></div>
                                        </div>
                                      </div>
                                      <div style="display:flex;justify-content:space-between;border-top:1px #ccc solid;margin-top:2px">
                                        <div style="width:75px">
                                          zapporter
                                        </div>
                                        <div style="width:300px;font-size:12px;overflow:hidden">
                                          <?php if (isset($seo_url_zp) AND $seo_url_zp!='-') {
                                          ?>
                                          <a href="https://zapchastiporter.ru<?=$seo_url_zp?>" target="_blank"><?=$seo_url_zp?></a>
                                        <?php }else{?>
                                          <b style="color:#dc2020">НЕТ URL</b>
                                        <?php } ?>
                                        </div>
                                        <div>
                                          <div  style="width:75px">SEO: <b style="color:<?=$color_seo_zp?>"><?=$seo_zp?></b></div>
                                        </div>
                                      </div>
                                    </div>
                                  </td>
                                  <?php
                                  $cpc=number_format($cpc,2,'.','');
                                  if ($roi=="inf" OR $roi=="") {
                                    $roi=0.00;
                                  }
                                  $roi=number_format($roi,2,'.','');
                                  if ($zaprosi=="" OR $zaprosi=="-" OR $zaprosi=="~") {
                                    $zaprosi=0;
                                  }
                                  if ($pokazi=="" OR $pokazi=="-" OR $pokazi=="~") {
                                    $pokazi=0;
                                  }


                                  if ($zaprosi<10) {
                                    $rk=10;
                                  }else{
                                    if ($seo_zp<=5 OR $seo_angara<=5) {
                                      $cpc=$cpc9;
                                      $zatrati=$zatrati2;
                                      $pribil=$pribil2;
                                      $roi=$roi2;
                                      $marja=$marja2;
                                      $zaprosi=$zaprosi2;
                                      $pokazi=$pokazi2;
                                      $obiem=9;
                                      if ($pribil>0) {
                                        $rk=1;
                                      }else{
                                        $rk=2;
                                      }

                                    }else{
                                      if ($obiem==9) {
                                        if ($pribil>0) {
                                          $rk=4;
                                        }else{
                                          $rk=5;
                                        }
                                      }else{
                                        $rk=3;
                                      }
                                    }
                                  }
                                  ?>
                  <!--группы где пересекаются слова -->
                                    <?php
                                    $sql='SELECT DISTINCT category_id,category_name,subcat_id,subcat_name,group_id,group_name FROM ' . $table_keywords . ' WHERE keywords="' . $keyword['keywords'] . '" AND group_id!=' . $id . ' AND danet=0';
                                    $no_group=select_sql_db4($sql);
                                    if (isset($no_group[0]['category_id'])) {
                                    ?>
                                    <td style="background-color:#ffa8a9">
                                    <?php foreach ($no_group as $key => $no_gr){?>

                                      <form action="" method="get">
                                        <a href="/reklama/?do=2&table=<?=$no_gr['category_id']?>"><?=$no_gr['category_name']?></a> / <a href="/reklama/?do=2&table=<?=$no_gr['subcat_id']?>"><?=$no_gr['subcat_name']?></a> / <a href="/reklama/?do=2&table=<?=$no_gr['group_id']?>"><?=$no_gr['group_name']?></a>
                                        <input hidden name="do" value="<?=$_GET['do']?>"></input>
                                        <input hidden name="table" value="<?=$_GET['table']?>"></input>
                                        <input type="radio" checked name="danet" value="1"></input>
                                        <input hidden name="keyword" value="<?=$keyword['keywords']?>"></input>
                                        <input hidden name="no_gr" value="<?=$no_gr['group_id']?>"></input>


                                        <input type="submit" value="удалить категорию!">
                                      </form>
                                      <?php if (isset($_GET['danet']) AND $_GET['danet']==1 AND $_GET['keyword']==$keyword['keywords'] AND $_GET['no_gr']==$no_gr['group_id']) {
                                        $sql_danet='UPDATE ' . $table_keywords . ' SET danet=1 WHERE keywords="' . $keyword['keywords'] . '" AND group_id=' . $no_gr['group_id'];
                                        echo "удалено";
                                        update_sql_db4($sql_danet);
                                      }?>
                                    <?php } ?>
                                    <p>RK = <?=$rk?></p>
                                    </td>
                                  <?php }else{?>
                                    <td><p>RK = <?=$rk?></p></td>
                                  <?php }


//НОВАЯ ВСТАВКА





                                  if(isset($metriki[0]['zaprosi']) AND $metriki[0]['zaprosi']==""){
                                    $metriki[0]['zaprosi']=0;
                                  }else{
                                    $metriki[0]['zaprosi']=0;
                                  }

                                  if (isset($metriki[0]['pokaz_100']) AND $metriki[0]['pokaz_100']=="") {
                                    $metriki[0]['pokaz_100']=0;
                                  }else{
                                    $metriki[0]['pokaz_100']=0;
                                  }

                                  $sql_checkeee='SELECT * FROM reklama_blocked_ids WHERE keyword IS NOT NULL';
                                  $checkeee=select_sql_db4($sql_checkeee);
                                  $arr_checkeee="";
                                    foreach ($checkeee as $key => $che) {
                                      $arr_checkeee .=$che['keyword'] . ';';
                                    }

                                    $arr_res_checkeee=explode(';',rtrim($arr_checkeee,';'));
                                  if (in_array($keyword['keywords'],$arr_res_checkeee)) {

                                  }else{
                                    //если вставляем то заменить точки в цифрах на запятые
                                    $cpc=str_ireplace(".",",",$cpc);
                                    $roi=str_ireplace(".",",",$roi);

                                  $table_reklama_done='reklama_done';
                                  $sql_check_done='SELECT * FROM ' . $table_reklama_done . '
                                  WHERE cat_id="' . $id . '" AND keyword="' . $keyword['keywords'] . '"';
                                  $check_reklama_done=select_sql_db4($sql_check_done);
                                  // p($sql_check_done);
                                  if (isset($check_reklama_done[0]['keyword'])) {
                                    if ($url_obyavlenia==$check_reklama_done[0]['url']
                                    AND $check_reklama_done[0]['minus_keyword']==$keys
                                     AND $title_obyavlenia==$check_reklama_done[0]['title']
                                       AND $title_obyavlenia_simv==$check_reklama_done[0]['title_count']
                                        AND $title2_obyavlenia==$check_reklama_done[0]['title2']
                                          AND $check_reklama_done[0]['title2_count']==$title2_obyavlenia_simv
                                           AND $check_reklama_done[0]['text']==$text_obyavlenia
                                             AND $check_reklama_done[0]['text_count']==$text_obyavlenia_simv
                                              AND $check_reklama_done[0]['patch']==$patch2
                                               AND $check_reklama_done[0]['patch_count']==$patch2_simv
                                                 AND $check_reklama_done[0]['zapros']==$zaprosi
                                                   AND $check_reklama_done[0]['pokaz']==$pokazi
                                                    AND $check_reklama_done[0]['cpc']==$cpc
                                                     AND $check_reklama_done[0]['max_cpc']==$max_cpc
                                                      AND $check_reklama_done[0]['roi']==$roi
                                                       AND $check_reklama_done[0]['id_rk']==$rk) {
                                      echo "уже есть";
                                    }else{
                                      $sql_upd='UPDATE ' . $table_reklama_done . '
                                      SET cat_id="' . $id . '" ,
                                      keyword="' . $keyword['keywords'] . '",
                                      minus_keyword="' . $keys . '",
                                      url="' . $url_obyavlenia . '",
                                      title="' . $title_obyavlenia . '",
                                      title_count="' . $title_obyavlenia_simv . '",
                                      title2="' . $title2_obyavlenia . '",
                                      title2_count="' . $title2_obyavlenia_simv . '",
                                      text="' . $text_obyavlenia . '",
                                      text_count="' . $text_obyavlenia_simv . '",
                                      patch="' . $patch2 . '",
                                      patch_count="' . $patch2_simv . '",
                                      zapros="' . $zaprosi . '",
                                      pokaz="' . $pokazi . '",
                                      cpc="' . $cpc . '",
                                      max_cpc="' . $max_cpc . '",
                                      roi="' . $roi . '",
                                      seo_angara="' . $seo_angara . '",
                                      seo_zp="' . $seo_zp . '",
                                      url_seo_angara="' . $seo_url_angara . '",
                                      url_seo_zp="' . $seo_url_zp . '",
                                      id_rk="' . $rk . '"
                                      WHERE cat_id="' . $check_reklama_done[0]['cat_id'] . '" AND keyword="' . $check_reklama_done[0]['keyword'] . '"';
                                      p($sql_upd);
                                      update_sql_db4($sql_upd);
                                    }
                                  }else{
                                    $sql='INSERT INTO ' . $table_reklama_done . '(cat_id,
                                    keyword,
                                    minus_keyword,
                                    url,
                                    title,
                                    title_count,
                                    title2,
                                    title2_count,
                                    text,
                                    text_count,
                                    patch,
                                    patch_count,
                                    zapros,
                                    pokaz,
                                    cpc,
                                    max_cpc,
                                    roi,
                                    seo_angara,
                                    seo_zp,
                                    url_seo_angara,
                                    url_seo_zp,
                                    id_rk)
                                     VALUES ("' . $id . '",
                                     "' . $keyword['keywords'] . '",
                                     "' . $keys . '",
                                     "' . $url_obyavlenia . '",
                                     "' . $title_obyavlenia . '",
                                     "' . $title_obyavlenia_simv . '",
                                     "' . $title2_obyavlenia . '",
                                     "' . $title2_obyavlenia_simv . '",
                                     "' . $text_obyavlenia . '",
                                     "' . $text_obyavlenia_simv . '",
                                     "' . $patch2 . '",
                                     "' . $patch2_simv . '",
                                     "' . $zaprosi . '",
                                     "' . $pokazi . '",
                                     "' . $cpc . '",
                                     "' . $max_cpc . '",
                                     "' . $roi . '",
                                     "' . $seo_angara . '",
                                     "' . $seo_zp . '",
                                     "' . $seo_url_angara . '",
                                     "' . $seo_url_zp . '",
                                     "' . $rk . '")';
                                     p($sql);
                                     update_sql_db4($sql);
                                  }
                                  }



                                }
                                if (isset($title_obyavlenia_simv)) {
                                if ($title_obyavlenia_simv>$title1_count) {
                                  $title_simv_color="red";
                                }else{
                                  $title_simv_color="green";
                                }
                                }
                                if (isset($title2_obyavlenia_simv)) {
                                if ($title2_obyavlenia_simv>$title2_count) {
                                  $title2_simv_color="red";
                                }else{
                                  $title2_simv_color="green";
                                }
                                }
                                if (isset($text_obyavlenia_simv)) {
                                if ($text_obyavlenia_simv>$text_count) {
                                  $text_simv_color="red";
                                }else{
                                  $text_simv_color="green";
                                }
                                }
                                if (isset($text_obyavlenia_simv)) {
                                if ($patch2_simv>$patch2_count) {
                                  $patch2_simv_color="red";
                                }else{
                                  $patch2_simv_color="green";
                                }
                                }


                  //по плюсовым
                                $res1=rtrim($pribil_all,';');
                              $summ_prib=number_format(array_sum(explode(';',$res1)),0,'.','');
                                $res2=rtrim($zaprosi_all,';');
                              $summ_zapros=array_sum(explode(';',$res2));
                                $res3=rtrim($pokazi_all,';');
                              $summ_pokaz=array_sum(explode(';',$res3));
                                $res5=rtrim($marja_all,';');
                              $summ_marja=array_sum(explode(';',$res5));
                                $res6=rtrim($zatrati_all,';');
                              $summ_zatrati=array_sum(explode(';',$res6));
                                $res4=rtrim($cpc_all,';');
                                $arr_cpc=explode(';',$res4);
                                $count_cpc=count($arr_cpc);
                                $summ_cpc=array_sum($arr_cpc);

                              if ($summ_zatrati==0 OR $summ_marja==0) {
                                $roi_all=0;
                                $sredn_cpc=0;
                              }else{
                                $sredn_cpc=number_format($summ_cpc/$summ_zapros,2);
                                $roi_all=number_format($summ_marja/$summ_zatrati*100,2,'.','');
                              }
                              if ($summ_prib<0) {
                                $color_summ_prib='#dc2020';
                              }else{
                                $color_summ_prib='#256d20';
                              }


                  //общий подсчет
                              $res11=rtrim($pribil_all1,';');
                            $summ_prib1=number_format(array_sum(explode(';',$res11)),0,'.','');
                              $res21=rtrim($zaprosi_all1,';');
                            $summ_zapros1=array_sum(explode(';',$res21));
                              $res31=rtrim($pokazi_all1,';');
                            $summ_pokaz1=array_sum(explode(';',$res31));
                              $res51=rtrim($marja_all1,';');
                            $summ_marja1=array_sum(explode(';',$res51));
                              $res61=rtrim($zatrati_all1,';');
                            $summ_zatrati1=array_sum(explode(';',$res61));
                              $res41=rtrim($cpc_all1,';');
                              $arr_cpc1=explode(';',$res41);
                              $count_cpc1=count($arr_cpc1);
                              $summ_cpc1=array_sum($arr_cpc1);

                  //SEO подсчет
                              $arr_seo_angara_prib=explode(';',rtrim($sr_seo_angara_prib,';'));
                              $srz_seo_ang_prib=array_sum($arr_seo_angara_prib)/count($arr_seo_angara_prib);
                              if ($srz_seo_ang_prib>5) {
                                $color_sr_seo_ang_prib='red';
                              }else{
                                $color_sr_seo_ang_prib='green';
                              }
                              $arr_seo_angara_pr10=0;
                              $arr_seo_angara_pr5=0;
                              $arr_seo_angara_pr3=0;
                              $arr_seo_angara_pr50=0;
                              foreach ($arr_seo_angara_prib as $key => $arr_seo_angara_pr) {
                                if ($arr_seo_angara_pr<=10) {
                                  $arr_seo_angara_pr10++;
                                }else{
                                  $arr_seo_angara_pr50++;
                                }
                                if ($arr_seo_angara_pr<=5) {
                                  $arr_seo_angara_pr5++;
                                }
                                if ($arr_seo_angara_pr<=3) {
                                  $arr_seo_angara_pr3++;
                                }
                              }

                              $arr_seo_zp_prib=explode(';',rtrim($sr_seo_zp_prib,';'));
                              $srz_seo_zp_prib=array_sum($arr_seo_zp_prib)/count($arr_seo_zp_prib);
                              if ($srz_seo_zp_prib>5) {
                                $color_sr_seo_zp_prib='red';
                              }else{
                                $color_sr_seo_zp_prib='green';
                              }
                              $arr_seo_zp_pr50=0;
                              $arr_seo_zp_pr10=0;
                              $arr_seo_zp_pr5=0;
                              $arr_seo_zp_pr3=0;
                              foreach ($arr_seo_zp_prib as $key => $arr_seo_zp_pr) {
                                if ($arr_seo_zp_pr<=10) {
                                  $arr_seo_zp_pr10++;
                                }else{
                                  $arr_seo_zp_pr50++;
                                }
                                if ($arr_seo_zp_pr<=5) {
                                  $arr_seo_zp_pr5++;
                                }
                                if ($arr_seo_zp_pr<=3) {
                                  $arr_seo_zp_pr3++;
                                }
                              }

                              $arr_seo_angara=explode(';',rtrim($sr_seo_angara,';'));
                              $srz_seo_ang=number_format(array_sum($arr_seo_angara)/count($arr_seo_angara),2,'.','');
                              if ($srz_seo_ang>5) {
                                $color_sr_seo_ang='red';
                              }else{
                                $color_sr_seo_ang='green';
                              }
                              $arr_seo_angara_50=0;
                              $arr_seo_angara_10=0;
                              $arr_seo_angara_5=0;
                              $arr_seo_angara_3=0;
                              foreach ($arr_seo_angara as $key => $arr_seo_angara_) {
                                if ($arr_seo_angara_<=10) {
                                  $arr_seo_angara_10++;
                                }else{
                                  $arr_seo_angara_50++;
                                }
                                if ($arr_seo_angara_<=5) {
                                  $arr_seo_angara_5++;
                                }
                                if ($arr_seo_angara_<=3) {
                                  $arr_seo_angara_3++;
                                }
                              }

                              $arr_seo_zp=explode(';',rtrim($sr_seo_zp,';'));
                              $srz_seo_zp=number_format(array_sum($arr_seo_zp)/count($arr_seo_zp),2,'.','');
                              if ($srz_seo_zp>5) {
                                $color_sr_seo_zp='red';
                              }else{
                                $color_sr_seo_zp='green';
                              }
                              $arr_seo_zp_50=0;
                              $arr_seo_zp_10=0;
                              $arr_seo_zp_5=0;
                              $arr_seo_zp_3=0;
                              foreach ($arr_seo_zp as $key => $arr_seo_zp_) {
                                if ($arr_seo_zp_<=10) {
                                  $arr_seo_zp_10++;
                                }else{
                                  $arr_seo_zp_50++;
                                }
                                if ($arr_seo_zp_<=5) {
                                  $arr_seo_zp_5++;
                                }
                                if ($arr_seo_zp_<=3) {
                                  $arr_seo_zp_3++;
                                }
                              }
                            // if ($seo_angara=='-' OR empty($seo_angara)) {
                            //
                            // }else{
                            //     $sr_seo_angara_prib .=$seo_angara . ';';
                            // }
                            // if ($seo_zp=='-' OR empty($seo_zp)) {
                            //
                            // }else{
                            //     $sr_seo_zp_prib .=$seo_zp . ';';
                            // }

                            if ($summ_zatrati1==0 OR $summ_marja1==0) {
                              $roi_all1=0;
                              $sredn_cpc1=0;
                            }else{
                              $roi_all1=number_format($summ_marja1/$summ_zatrati1*100,2,'.','');
                              $sredn_cpc1=number_format($summ_cpc1/$summ_zapros1,2,'.','');
                            }
                            if ($summ_prib1<0) {
                              $color_summ_prib1='#dc2020';
                            }else{
                              $color_summ_prib1='#256d20';
                            }
                            ?>
                            <tr>
                              <td></td>
                              <td></td>
                              <td></td>
                            </tr>
                            <tr>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td>суммарно по плюсовым</td>

                              <td>

                                <div style="color:#256d20">Маржа: <?=$summ_marja?>р</div>
                                <div style="color:#dc2020">Затраты: <?=$summ_zatrati?>р</div></td>
                              <td>
                                <div style="display:flex;justify-content:space-between">
                                  <div>Зап: <?=$summ_zapros?> || Пок: <?=$summ_pokaz?></div>
                                  <div>CPC: <?=$sredn_cpc?>р</div>
                                  <div style="color:<?=$color_summ_prib?>">Приб: <?=$summ_prib?>р</div>
                                  <div>ROI: <?=$roi_all?>%</div>
                                </div>
                              <!--SEO показатели-->
                                                <div>
                                                  <div style="display:flex;justify-content:space-between;border-top:1px #ccc solid;margin-top:2px">
                                                    <div style="width:75px">
                                                      Angara77
                                                    </div>
                                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                                      топ 3: <?=$arr_seo_angara_pr3?>
                                                    </div>
                                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                                      топ 5: <?=$arr_seo_angara_pr5?>
                                                    </div>
                                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                                      топ 10: <?=$arr_seo_angara_pr10?>
                                                    </div>
                                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                                      50+: <?=$arr_seo_angara_pr50?>
                                                    </div>
                                                    <div>
                                                      <div style="width:75px">SEO: <b style="color:<?=$color_sr_seo_ang_prib?>"><?=$srz_seo_ang_prib?></b></div>
                                                    </div>
                                                  </div>
                                                  <div style="display:flex;justify-content:space-between;border-top:1px #ccc solid;margin-top:2px">
                                                    <div style="width:75px">
                                                      zapporter
                                                    </div>
                                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                                      топ 3: <?=$arr_seo_zp_pr3?>
                                                    </div>
                                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                                      топ 5: <?=$arr_seo_zp_pr5?>
                                                    </div>
                                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                                      топ 10: <?=$arr_seo_zp_pr10?>
                                                    </div>
                                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                                      50+: <?=$arr_seo_zp_pr50?>
                                                    </div>
                                                    <div>
                                                      <div  style="width:75px">SEO: <b style="color:<?=$color_sr_seo_zp_prib?>"><?=$srz_seo_zp_prib?></b></div>
                                                    </div>
                                                  </div>
                                                </div>
                            </td>
                            </tr>
                            <tr>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td>суммарно по группе</td>

                              <td>
                                <div style="color:#256d20">Маржа: <?=$summ_marja1?>р</div>
                                <div style="color:#dc2020">Затраты: <?=$summ_zatrati1?>р</div></td>
                              <td>
                                <div  style="display:flex;justify-content:space-between">
                              <div>Зап: <?=$summ_zapros1?> || Пок: <?=$summ_pokaz1?></div>
                              <div>CPC: <?=$sredn_cpc1?>р</div>
                              <div style="color:<?=$color_summ_prib1?>">Приб: <?=$summ_prib1?>р</div>
                              <div>ROI: <?=$roi_all1?>%</div>
                            </div>
                                <!--SEO показатели-->
                                <div>
                                  <div style="display:flex;justify-content:space-between;border-top:1px #ccc solid;margin-top:2px">
                                    <div style="width:75px">
                                      Angara77
                                    </div>
                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                      топ 3: <?=$arr_seo_angara_3?>
                                    </div>
                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                      топ 5: <?=$arr_seo_angara_5?>
                                    </div>
                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                      топ 10: <?=$arr_seo_angara_10?>
                                    </div>
                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                      50+: <?=$arr_seo_angara_50?>
                                    </div>
                                    <div>
                                      <div style="width:75px">SEO: <b style="color:<?=$color_sr_seo_ang?>"><?=$srz_seo_ang?></b></div>
                                    </div>
                                  </div>
                                  <div style="display:flex;justify-content:space-between;border-top:1px #ccc solid;margin-top:2px">
                                    <div style="width:75px">
                                      zapporter
                                    </div>
                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                      топ 3: <?=$arr_seo_zp_3?>
                                    </div>
                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                      топ 5: <?=$arr_seo_zp_5?>
                                    </div>
                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                      топ 10: <?=$arr_seo_zp_10?>
                                    </div>
                                    <div style="width:60px;font-size:12px;overflow:hidden">
                                      50+: <?=$arr_seo_zp_50?>
                                    </div>
                                    <div>
                                      <div  style="width:75px">SEO: <b style="color:<?=$color_sr_seo_zp?>"><?=$srz_seo_zp?></b></div>
                                    </div>
                                  </div>
                                </div>
                            </td>
                            </tr>

                            <?php
                            $sql='UPDATE ' . $table_reklama . ' SET
                            sr_seo_angara="' . $srz_seo_ang . '",
                            sr_seo_zp="' . $srz_seo_zp . '",
                            cpc="' . $sredn_cpc1 . '",
                            zaprosi="' . $summ_zapros1 . '",
                            zaprosi_prib="' . $summ_prib1 . '",
                            pokazi="' . $summ_pokaz1 . '",
                            roi="' . $roi_all1 . '"
                            WHERE group_id="' . $id . '" ';
                            update_sql_db4($sql);






                            ?>

                            <?php
                            $arr_url_cat='';
                            foreach ($arr_url_zp_tov as $key => $value) {
                            $preg=preg_replace("/([a-z].*)[\/](part)[\/]([0-9]*)[\/]([a-z].*)[\/]/i",'$3',$value['Address']);
                            // p($preg);
                            $sql='SELECT parent FROM angara WHERE 1c_id="' . $preg . '"';
                            $id_1c=select_sql_db4($sql);

                            $id_1c='/porterparts/' . $id_1c[0]['parent'] . '/';
                            $sql2='SELECT * FROM ' . $table_zp_cat_url_id . ' WHERE Address LIKE "%' . $id_1c . '%"';
                            $res_sql2=select_sql_db4($sql2);
                            $url_zp_tov_cat=$res_sql2[0]['Address'];
                            $name_zp_tov_cat=$res_sql2[0]['ang_name'];
                              $arr_url_cat .='`'.$url_zp_tov_cat . '`;`' . $name_zp_tov_cat . '`,';
                              $photo=get_image($preg);
                              $img_size=getimagesize(ANG_ROOT . "/img/parts/parts/" . $photo);

                              $filesize=filesize(ANG_ROOT . "/img/parts/parts/" . $photo);
                              // p($filesize);
                              // p($photo);
                              ?>

                            <tr>
                              <td></td>
                              <td>
                                <div>
                                  <a style="font-size:13px;" href="<?=$value['Address']?>"><?=$value['ang_name']?></a>
                                </div>
                              </td>
                              <td><?=p($img_size)?></td>
                              <td>
                                <img src="/img/parts/parts/<?=$photo?>" class="img-responsive">
                              </td>

                              <td>
                                <?=$filesize?>
                                </td>
                              <td>

                            </td>
                            </tr>

                              <?php
                            }


                            ?>
                            <tr>
                              <td>
                                Доп объявление
                              </td>
                              <td>
                                Группа(AdGroupName)
                              </td>
                              <td>
                                Фраза(keyword)
                              </td>
                              <td>
                                Title1
                              </td>
                              <td>
                                Title2
                              </td>
                              <td>
                                Text
                              </td>
                              <td>
                                Url
                              </td>
                              <td>
                                Image
                              </td>
                            </tr>


                            <?php
                            $group_name=$category['group_name'];
                            $group_name=ucfirst($group_name);
                            $str_repl_keyw=str_ireplace('hyundai porter','Porter',$group_name);
                            $str_repl_keyw=str_ireplace('porter hyundai','Porter',$str_repl_keyw);
                            $str_repl_keyw=str_ireplace('хендай портер','Портер',$str_repl_keyw);
                            $str_repl_keyw=str_ireplace('портер хендай','Портер',$str_repl_keyw);
                            $str_repl_keyw=str_ireplace('хундай портер','Портер',$str_repl_keyw);
                            $str_repl_keyw=str_ireplace('портер хундай','Портер',$str_repl_keyw);
                            $str_repl_keyw=str_ireplace('hyundai','Hyundai',$str_repl_keyw);
                            $str_repl_keyw=str_ireplace('porter','Porter',$str_repl_keyw);
                            $str_repl_keyw=str_ireplace('хендай','Хендай',$str_repl_keyw);
                            $str_repl_keyw=str_ireplace('портер','Портер',$str_repl_keyw);
                            $str_repl_keyw=str_ireplace('гбц ','ГБЦ ',$str_repl_keyw);
                            $str_repl_keyw=str_ireplace('тнвд ','ТНВД ',$str_repl_keyw);
                            $str_repl_keyw=str_ireplace('тагаз ','Тагаз ',$str_repl_keyw);
                            $str_repl_keyw=str_ireplace('егр ','ЕГР ',$str_repl_keyw);
                            $str_repl_keyw=str_ireplace(' гбц',' ГБЦ',$str_repl_keyw);
                            $str_repl_keyw=str_ireplace(' тнвд',' ТНВД',$str_repl_keyw);
                            $str_repl_keyw=str_ireplace(' тагаз',' Тагаз',$str_repl_keyw);
                            $str_repl_keyw=str_ireplace(' егр',' ЕГР',$str_repl_keyw);
                            $str_repl_keyw=str_ireplace(' москв',' Москв',$str_repl_keyw);
                            $str_repl_keyw=str_ireplace(' грм',' ГРМ',$str_repl_keyw);
                            $group_name=trim($str_repl_keyw,' \t\n\r\0\x0B');
                            $first_abc=preg_replace('/(..)(.*)/i','$1',$group_name);
                            $second_abc=preg_replace('/(..)(.*)/i','$2',$group_name);
                            $group_name=mb_strtoupper($first_abc) . $second_abc;






                            //массив фоток
                            $ar_phot='';
                              foreach ($arr_url_zp_tov as $key => $value) {
                              $preg=preg_replace("/([a-z].*)[\/](part)[\/]([0-9]*)[\/]([a-z].*)[\/]/i",'$3',$value['Address']);
                              $photo=get_image($preg);
                                if (isset($photo) AND !empty($photo)) {
                                  $img=$photo;
                                  $ar_phot .="/img/parts/parts/" . $img . ';';
                                }else{

                                }
                              }






                              $res_phot=explode(';',rtrim($ar_phot,';'));//это он и есть
                              // p($res_phot);
                              //объявление
                              $title=$group_name . ' Портер';
                              $title2="97% запчастей на Портер в наличии";
                              $text="Доставка по Москве 290р, отправка по России в день заказа +100% Гарантия возврата";
                              $url=$url_obyavlenia_angara . "?utm_source=direct.yandex.ru&utm_medium=cpc&utm_campaign=Angara-porter-RSYA&utm_content=AD&utm_term={keyword}&added={addphrases}&block={position_type}&pos={position}&key={keyword}&campaign={campaign_id}&ad={ad_id}&phrase={phrase_id}";
                              $path=$patch2;
                              $max_cpc=$max_cpc;
                              $dop="-";
                              $typeAds="Текстово-графическое";




                              //если только существуюет хотябы одно ключевое слово
                              if (isset($keywords[0]['keywords'])){

                                //массив с блокированными группами для рекламы
                                          $sql_select_blocked=select_sql_db4('SELECT DISTINCT id FROM rsya_blocked_cat');
                                          $blocked_id_array='';
                                          foreach ($sql_select_blocked as $key => $block_id) {
                                            $blocked_id_array .=$block_id['id'] . ',';
                                          }
                                          $blocked_ids=explode(',',trim($blocked_id_array,','));
                                          //если по этому айди можно создавать рекламу
                                if (in_array($id,$blocked_ids)) {
                                  echo "<p style='color:red'>заблокировано в таблице блока групп для RSYA `rsya_blocked_cat`<p>";
                                }else{
                              //это первое объявление и группа объявлений и набор ключевиков в группе
                            foreach ($keywords as $key => $keyword123123) {
                              $keyword1=$keyword123123['keywords'];
                              ?>
                              <tr>
                                <td><?=$dop?></td>
                                <td><?=$group_name?></td>
                                <td><?=$keyword1?></td>
                                <td><?=$title . " - " . $title2?></td>
                                <td><?=$text?></td>
                                <td><?=$url?></td>
                                <td><?=$path?></td>
                                <td><?=$res_phot[0]?></td>
                                <td><?=$max_cpc?></td>
                              </tr>
                          <?php
                          $sql_check='SELECT * FROM yandex_reklama_xls WHERE cat_id="' . $id . '" AND Keyword="' . $keyword1 . '" AND image="' . $res_phot[0] . '"';
                          // p($sql_check);
                          // p($keyword1);
                          $check_db4_rsya=select_sql_db4($sql_check);
                          // p($check_db4_rsya[0]['cat_id']);
                          if (isset($check_db4_rsya[0]['cat_id']) AND !empty($check_db4_rsya[0]['cat_id'])) {
                            if ($check_db4_rsya[0]['cat_id']==$id AND
                            $check_db4_rsya[0]['Keyword']==$keyword1 AND
                            $check_db4_rsya[0]['AdGroupName']==$group_name AND
                            $check_db4_rsya[0]['dop']==$dop AND
                            $check_db4_rsya[0]['image']==$res_phot[0] AND
                            $check_db4_rsya[0]['url']==$url) {

                            }else{
                              $sql='UPDATE yandex_reklama_xls SET
                                cat_id=' . $id . ',
                               dop="' . $dop . '",
                               type_ads="' . $typeAds . '",
                               mobile_ads="-",
                               AdGroupName="' . $group_name . '",
                               Keyword="' . $keyword1 . '",
                               Title1="' . $title . '",
                               Title2="' . $title2 . '",
                               Text="' . $text . '",
                               url="' . $url . '",
                               path="' . $path . '",
                               max_cpc="' . $max_cpc . '",
                               image="' . $res_phot[0] . '"
                               WHERE cat_id="' . $id . '" AND Keyword="' . $keyword1 . '"
                              ';

                              update_sql_db4($sql);
                            }
                          }else{
                            $sql='INSERT INTO yandex_reklama_xls (`cat_id`,
                               `dop`,
                                `type_ads`,
                                 `mobile_ads`,
                                  `AdGroupName`,
                                   `Keyword`,
                                    `Title1`,
                                     `Title2`,
                                      `Text`,
                                       `url`,
                                        `path`,
                                         `max_cpc`,
                                          `image`)
                             VALUES ("' . $id . '",
                                "' . $dop . '",
                                 "' . $typeAds . '",
                                  "-",
                                   "' . $group_name . '",
                                    "' . $keyword1 . '",
                                     "' . $title . '",
                                      "' . $title2 . '",
                                       "' . $text . '",
                                        "' . $url . '",
                                         "' . $path . '",
                                          "' . $max_cpc . '",
                                           "' . $res_phot[0] . '")';
                                           // p($sql);
                                           update_sql_db4($sql);
                          }
                        }





                            //это дополнительные объявления в группе по одному на каждое фото
                            $keyword='';
                            $max_cpc='';
                            $dop="+";
                            $typeAds="Текстово-графическое";
                            foreach ($res_phot as $key => $phot) {
                              if ($key!=0) {
                              ?>
                            <tr>
                              <td><?=$dop?></td>
                              <td><?=$group_name?></td>
                              <td><?=$keyword?></td>
                              <td><?=$title . " - " . $title2?></td>
                              <td><?=$text?></td>
                              <td><?=$url?></td>
                              <td><?=$path?></td>
                              <td><?=$phot?></td>
                            </tr>
                            <?php
                            $sql_check2='SELECT * FROM yandex_reklama_xls WHERE cat_id="' . $id . '" AND Keyword="' . $keyword . '" AND AdGroupName="' . $group_name . '" AND dop="' . $dop . '" AND image="' . $phot . '"';
                            // p($sql_check2);
                            $check_db4_rsya2=select_sql_db4($sql_check2);
                            // p($check_db4_rsya2);
                            if (isset($check_db4_rsya2[0]['cat_id']) AND !empty($check_db4_rsya2[0]['cat_id'])) {
                              if ($check_db4_rsya2[0]['cat_id']==$id OR
                              $check_db4_rsya2[0]['Keyword']==$keyword OR
                              $check_db4_rsya2[0]['AdGroupName']==$group_name OR
                              $check_db4_rsya2[0]['dop']==$dop OR
                              $check_db4_rsya2[0]['image']==$phot) {

                              }else{
                                $sql='UPDATE yandex_reklama_xls SET
                                  cat_id=' . $id . ',
                                 dop="' . $dop . '",
                                 type_ads="' . $typeAds . '",
                                 mobile_ads="-",
                                 AdGroupName="' . $group_name . '",
                                 Keyword="' . $keyword . '",
                                 Title1="' . $title . '",
                                 Title2="' . $title2 . '",
                                 Text="' . $text . '",
                                 url="' . $url . '",
                                 path="' . $path . '",
                                 max_cpc="' . $max_cpc . '",
                                 image="' . $phot . '"
                                 WHERE cat_id="' . $id . '" AND Keyword="' . $keyword . '" AND AdGroupName="' . $group_name . '" AND dop="' . $dop . '" AND image="' . $phot . '"
                                ';
                                update_sql_db4($sql);
                              }
                            }else{
                              $sql='INSERT INTO yandex_reklama_xls (`cat_id`,
                                 `dop`,
                                  `type_ads`,
                                   `mobile_ads`,
                                    `AdGroupName`,
                                     `Keyword`,
                                      `Title1`,
                                       `Title2`,
                                        `Text`,
                                         `url`,
                                          `path`,
                                           `max_cpc`,
                                            `image`)
                               VALUES ("' . $id . '",
                                  "' . $dop . '",
                                   "' . $typeAds . '",
                                    "-",
                                     "' . $group_name . '",
                                      "' . $keyword . '",
                                       "' . $title . '",
                                        "' . $title2 . '",
                                         "' . $text . '",
                                          "' . $url . '",
                                           "' . $path . '",
                                            "' . $max_cpc . '",
                                             "' . $phot . '")';
                                             update_sql_db4($sql);
                            }
                                  }
                                  }
                                  }
                                  }
                            ?>
<!--КОНЕЦ РАБОТЫ С ГРУППАМИ -->
<!-- если нет в гете ничего -->
                        <?php }else{?>
                          <tr>

                            <td><a href="/reklama/?do=2&table=<?=$category['category_id']?>"><?=$category['category_name']?></a></td>
                        <?php }

                        ?>
                        </tr>
      <?php

    }?>
</table>
<?php
  }elseif ($_GET['do']==33) {

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
   }elseif ($_GET['do']==3) {
     $table_zp_cat_progon='url_zapchastiporter_category_progon';
     $table_zp_tovar_progon='url_zapchastiporter_tovary_progon';
     $table_zp_cat_url_id='url_zp_category_id';
     $table_zp_tovar_url_id='url_zp_tovar_id';
     $table_angara_cat_progon='url_angara_category_progon';
     $table_angara_tovar_progon='url_angara_tovary_progon';
     $table_angara_cat_url_id='url_angara_category_id';
     $table_angara_tovar_url_id='url_angara_tovar_id';
//zapchastiporter sopostavlenie URL
     $sql='SELECT * FROM ' . $table_zp_cat_progon;
     $cat_progon_zp=select_sql_db4($sql);
     foreach ($cat_progon_zp as $key => $word) {
       $word_id=$word['id'];
       $keyword=$word['ang_name'];
       $groups=explode($word['group_id']);
     }
   }
 }
 // code...

?>
</div>



<style>
.url2{

}
/* .url2::first-letter{
  text-transform:uppercase;
} */
.zagolovok{
    line-height: 24px;
    font-weight: 400;
    margin: 0;
    padding: 0;
    position: relative;
    font-size: 18px;
    font-family: Arial,Helvetica,sans-serif;
}
/* .zagolovok::first-letter{
  text-transform:uppercase;
} */
.description{
   margin: 0;
   padding: 0;
   font-size: 13px;
   font-family: Arial,Helvetica,sans-serif;
   color:#333;
   max-width: 97%;
}
/* .description::first-letter{
  text-transform:uppercase;
} */
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
