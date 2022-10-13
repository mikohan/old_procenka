<?php
	function curl_get($url, $referer = 'http://www.google.com') {

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_REFERER, $referer);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Expect:'));
		curl_setopt ($ch , CURLOPT_FOLLOWLOCATION , true);
		// выполнить запрос
		$data = curl_exec($ch);
		// получить результат работы
		// $result = curl_multi_getcontent ($ch);
		// // вывести результат
		// echo "\n".'Login OK'."\n".'[result ===8<===>'."\n".$result."\n".'<===>8=== result]'."\n";
		// // закрыть сессию работы с cURL
		curl_close ($ch);



		//echo $data;

		 return $data;

	}

	function p($array) {
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}//Конец функции
