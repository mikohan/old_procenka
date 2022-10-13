<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);
mb_internal_encoding("utf-8");
ini_set('max_execution_time', 60000000);
	include_once('lib/simple_html_dom.php');
	include_once('lib/curl_query.php');
	include_once('lib/function_parser.php');
	require_once ('config.php');



	// функция для вставки в таблицу данных для ззап
	// INSERT INTO `1_to_zzap`(number,brand)
	// SELECT number,"KIA HYUNDAI" FROM `p_uniq_starex_magaz` WHERE number!='' AND number!='0'

// получение значений
// таблица которую отправляем на ззап
$table ="1_to_zzap";
// исходная таблица
$table2 ="`1_zzap_out`";
// столбец в котором номер
$column1="number";
// столбец в котором бренд
$column2="brand";

$r = tovar_category($table,$column1,$column2);
	foreach ($r as $key => $r1){
		$r2 = $r1[$column1];
		$r3 = $r1[$column2];


//начало скрипта
$sErrorText = "";
$aDataResult = array();
$sUrl = "https://www.zzap.ru//webservice/datasharing.asmx/GetSearchResult";

//данные для отправки
$aData = array();
$aData["login"] = "angara77@gmail.com";
$aData["password"] = "olesya1234538501";
$aData["location"] = "1";
$aData["partnumber"] = $r2;
$aData["class_man"] = $r3;
$aData["row_count"] = "500";
$aData["api_key"] = "EAAAAIcZJEWVsToo1tStTkt3qqVe2w8i60k6ue9+q2RKvX5MVv56HF3zz4EJOEa3ddIftA==";
//p($aData);
//преобразовываем массив в строку
$sData = "";
foreach($aData as $sKey => $sValue)
{
  if($sKey !== "login")
  {
  	$sData .= "&";
  }

  $sData .= $sKey . "=" . urlencode($sValue);
}
//отправляем запрос
$oCurl = curl_init();
curl_setopt($oCurl, CURLOPT_URL, $sUrl);
curl_setopt($oCurl, CURLOPT_HEADER, false);
curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($oCurl, CURLOPT_POST, true);
curl_setopt($oCurl, CURLOPT_POSTFIELDS, $sData);
if($sText = curl_exec($oCurl))
{
  //ответ получили

  if(preg_match_all('/<string xmlns="http:\/\/www\.zzap\.ru\/">(.+)<\/string>/', $sText, $aResult) === 1)
  {
  	$aDataResult = json_decode($aResult[1][0], true);
	//p($aDataResult);
  	if(is_array($aDataResult) and !empty($aDataResult))
  	{
      //массив получили
			//проверяем на ошибки
      if(isset($aDataResult["error"]) and $aDataResult["error"] != "")
      {
      	$sErrorText = $aDataResult["error"];
				to_bd_error($table,$r1['id'],$sErrorText);
      }elseif(!empty($aDataResult['table'][0]) and $aDataResult["error"] === "")
			{
			  //массив с данными есть

			  //выводим все поля

			  foreach($aDataResult as $Key => $vValue) {
				//p($vValue);
				tobd($vValue,$r2,$r3,$table2);
				to_bd_done($table,$r1['id']);





			  }
			}else{
				to_bd_none($table,$r1['id']);
			}
  	}
  }
}

curl_close($oCurl);






sleep(5);
}
