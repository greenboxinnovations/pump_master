<?php

$car_no_plate = "sdcsd";

$fuel = "petrol";

$amount = "500";

$d ="ULYEfL283F";

// $url = "http://pumpmastertest.greenboxinnovations.in/c_msg.php?t=".$d;

$url = "a";




$message = "Hi, Yor vehicle no ".$car_no_plate." just filled ".$fuel." worth ".$amount.". details: ".$url;
	    $encodedMessage = urlencode($message);
	    $api = "https://www.fast2sms.com/dev/bulk?authorization=CbSpQve5NE&sender_id=FSTSMS&message=" . $encodedMessage . "&language=english&route=p&numbers=8411815106&flash=0";

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
		echo $resp;
		// Close request to clear up some resources
		curl_close($curl);

?>

