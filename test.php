<?php
require 'query/conn.php';
require 'phpqrcode/qrlib.php';


$car_no_plate = "MH12CT5406";
$fuel = "petrol";
$amount = "1000.00";
$url = "http://fuelmaster.greenboxinnovations.in/cmsg.php?t=XgKiuOYnga";


date_default_timezone_set("Asia/Kolkata");
$timestamp = date("d/m/Y H:i:s");
echo "Formatted date from timestamp:" . $timestamp;


$newline = "\n";

$message = "SELECT AUTOMOBILES".$newline."Karve Road".$newline.$newline.$car_no_plate.$newline."Rs.".$amount.$newline.strtoupper($fuel).$newline.$timestamp.$newline.$newline.$url;
$encodedMessage = urlencode($message);
$api = "https://www.fast2sms.com/dev/bulk?authorization=CbSpQve5NE&sender_id=SLAUTO&message=" . $encodedMessage . "&language=english&route=t&numbers=8411815106&flash=0";

echo $api;

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
// Close request to clear up some resources
curl_close($curl);


