<?php
$ch_url = "https://api.shate-m.ru";
$Login = "angara77";
$Password = "9197703953";
$ApiKey = "fdd9d527-096a-4164-a5ce-40e9fd64e243";
$articeCode = "931104F000";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $ch_url."/login");
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, $Login.":".$Password);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, "ApiKey=".$ApiKey);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

$response = curl_exec($ch);

$headers = substr($response, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
$headers = explode("\r\n", $headers);

foreach ($headers as $header) {
if (strpos($header,'Token:')!==false) {
$token = array($header);
}
}

curl_setopt($ch, CURLOPT_HTTPHEADER,$token);
curl_setopt($ch, CURLOPT_URL, $ch_url."/api/search/GetTradeMarksByArticleCode/".$articeCode);
curl_setopt($ch, CURLOPT_POST,false);

$response = curl_exec($ch);
echo $response;

curl_close($ch);
?>