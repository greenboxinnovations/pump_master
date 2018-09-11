<?php
date_default_timezone_set("Asia/Kolkata");
require 'query/conn.php';

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
    echo $httpcode;
}
 
// httpGet("http://192.168.0.101/");

function sendMSG($car_no_plate, $fuel, $amount, $url, $phone_no){

	$message = "Hi, Yor vehicle no ".$car_no_plate." just filled ".$fuel." worth ".$amount.". details: ".$url;
    $encodedMessage = urlencode($message);
    $api = "https://www.fast2sms.com/dev/bulk?authorization=CbSpQve5NE&sender_id=SLAUTO&message=" . $encodedMessage . "&language=english&route=t&numbers=".$phone_no.",8411815106&flash=0";

    // Get cURL resource
	$curl = curl_init();
	// Set some options - we are passing in a useragent too here
	curl_setopt_array($curl, array(
	    CURLOPT_RETURNTRANSFER => 1,
	    CURLOPT_URL => $api,
	    //CURLOPT_USERAGENT => 'Codular Sample cURL Request'
	));
	curl_setopt($curl, CURLOPT_FRESH_CONNECT, TRUE);
	// Send the request & save response to $resp
	$resp = curl_exec($curl);
	$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	echo $httpcode;
	// Close request to clear up some resources
	curl_close($curl);
	
	
}

$sql = "SELECT `cust_post_paid`,`cust_ph_no` FROM `customers` WHERE `cust_id` = '28';";	
$exe = mysqli_query($conn,$sql);
$res = mysqli_fetch_assoc($exe);

$ph_no = $res['cust_ph_no'];

// echo $ph_no;


$car_no_plate = "mh11bm0201";
$fuel = 'petrol';
$amount = 100;
$url = "http://google.com";

$d = sendMSG($car_no_plate, $fuel, $amount, $url, $ph_no);

echo $d;
// 
?>