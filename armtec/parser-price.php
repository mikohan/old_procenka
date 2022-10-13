<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);
mb_internal_encoding("utf-8");
ini_set('max_execution_time', 60);
	include_once('../../admin/app/lib/simple_html_dom.php');
	include_once('../../admin/app/lib/curl_query.php');
	//include_once('function_parser.php');
	//require_once ('../../admin/app/config.php');

// получение значений


// $r = tovar_category(1304793080);
// 	foreach ($r as $key => $r1){
// 		$r2 = $r1['article_orig'];
// 		//p($r2);
// 		print_r ($r2);

// //начало скрипта
$sErrorText = "";
$aDataResult = array();
$sUrl = "http://ws.armtek.ru/api/ws_search/search?format=json";

$aData = array();
$aData["username"] = "suply.angara77@gmail.com";
$aData["password"] = "Angara33338501";
$aData["VKORG"] = "5000";
$aData["KUNNR_RG"] = "43039901";
$aData["PIN"] = '221-1515R-AE';
//var_dump($aData);
//преобразовываем массив в строку
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
  if(preg_match_all('/<string xmlns="http:\/\/www\.armtek\.ru\/">(.+)<\/string>/', $sText, $aResult) === 1)
  {

  	$aDataResult = json_decode($aResult[1][0], true);

  	if(is_array($aDataResult) and !empty($aDataResult))
  	{
      //массив получили

      //проверяем на ошибки
      if(isset($aDataResult["error"]) and $aDataResult["error"] != "")
      {
      	$sErrorText = $aDataResult["error"];
      }
  	}
  }
}

curl_close($oCurl);

if(!empty($aDataResult) and $sErrorText === "")
{
  //массив с данными есть

  //выводим все поля
  foreach($aDataResult as $Key => $vValue) {
		var_dump($aDataResult);
		//p($vValue);
		tobd($vValue);
  }
}
sleep(3);
//}
