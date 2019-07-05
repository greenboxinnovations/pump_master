<?php

require_once __DIR__.'/query/conn.php';

function httpGet($url)
{
    $ch = curl_init();  
 
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_NOBODY, true);    // we don't need body
	curl_setopt($ch, CURLOPT_TIMEOUT,1);
	$output = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
 
    curl_close($ch);
    return $httpcode;
}
 
// $d = httpGet("http://192.168.0.101/");

// if ($d == 200) {
// 	$p = true;
// }



for ($i=0; $i < 1000; $i++) { 

	try{
		$d = httpGet("http://192.168.0.101/");
		
	} catch (Exception $e) {
		echo $e->getMessage();
	}
	
}


?>