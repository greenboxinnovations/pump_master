<?php

require_once __DIR__.'/query/conn.php';

function sendMSG($car_no_plate, $fuel, $amount, $url, $phone_no){

    date_default_timezone_set("Asia/Kolkata");
	$timestamp = date("d/m/Y H:i:s");
	echo "Formatted date from timestamp:" . $timestamp;

	$newline = "\n";
 
	$message = "SELECT AUTOMOBILES".$newline."Karve Road".$newline.$newline.$car_no_plate.$newline."Rs.".$amount.$newline.strtoupper($fuel).$newline.$timestamp.$newline.$newline.$url;
	$encodedMessage = urlencode($message);

	$api = Globals::msgString($encodedMessage,$phone_no, true);

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
}


//get cust type
$sql = "SELECT `cust_post_paid`,`cust_ph_no` FROM `customers` WHERE `cust_id` = '18';";		
$exe = mysqli_query($conn,$sql);
$r = mysqli_fetch_assoc($exe);
$ph_no = $r['cust_ph_no'];



$car_no_plate = "aaaa";

// $url = "http://fuelmaster.greenboxinnovations.in/c_msg.php?t=".$row['trans_string'];
// $url = Globals::URL_MSG_VIEW.$row['trans_string'];
// $url = "http://fuelmaster.greenboxinnovations.in/cmsg.php?t=".$row['trans_string'];



$ph_no = str_replace("|", ",", $ph_no);
$ph_no = "8411815106,9762230207,8668863132,9822446155,9049391171";

sendMSG($car_no_plate, "petrol", "20", "http://9gag.com", $ph_no);


// echo $ph_no;


?>