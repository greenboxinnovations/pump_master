<?php


$car_no_plate = "MH-11-BM-0201";
$fuel = "petrol";
$amount = 500;
$phone_no = 9762230207;
$url = "http://google.com";

$message = "Hi, Yor vehicle no ".$car_no_plate." just filled ".$fuel." worth ".$amount.". details: ".$url;
	    $encodedMessage = urlencode($message);
	    $api = "https://www.fast2sms.com/dev/bulk?authorization=CbSpQve5NE&sender_id=SLAUTO&message=" . $encodedMessage . "&language=english&route=t&numbers=".trim($phone_no)."&flash=0";

	    // Get cURL resource
		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => $api,
		    //CURLOPT_USERAGENT => 'Codular Sample cURL Request'
		));
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		// Close request to clear up some resources
		curl_close($curl);


?>