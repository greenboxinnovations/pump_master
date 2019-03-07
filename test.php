<?php

require_once __DIR__.'/query/conn.php';


// echo getcwd();
// echo "\n";
// echo $_SERVER["DOCUMENT_ROOT"];

// date_default_timezone_set("Asia/Kolkata");
// $date = date("Y-m-d H:i:s");
// $unix = strtotime($date);

// $sql = "UPDATE `sync` SET `last_updated`= '".$unix."' WHERE `table_name` = 'cars';";
// $exe = mysqli_query($conn ,$sql);


// // $api = "https://www.fast2sms.com/dev/bulk?authorization=CbSpQve5NE&sender_id=SLAUTO&message=" . $encodedMessage . "&language=english&route=t&numbers=".trim($phone_no)."&flash=0";
class Globals2
{

	// DB connection
	const DB_USER_NAME 	= "root";
	const DB_PASSWORD  	= "toor"; 
	const DB_NAME 		= "pump_master"; 
	const DB_HOSTNAME   = "localhost";
	

	// MSG params
	const SEND_MSG = true;
	const PRINT_RECEIPT = false;


	// URL's
	const URL_SYNC_CHECK = "http://fueltest.greenboxinnovations.in";
	const URL_MSG_VIEW = "http://fuelmaster.greenboxinnovations.in/cmsg.php?t=";

	const MYSQLDUMP_PATH = "/opt/lampp/bin/mysqldump";

	const SMS_API = "https://www.fast2sms.com/dev/bulk?authorization=CbSpQve5NE&sender_id=SLAUTO&message={MESSAGE}&language=english&route=t&numbers={PHONE_NO}&flash=0";
	// (($imei == "357120093538457") || ($imei == "358213083247251")|| ($imei == "353107095806820")||("864510039799492"))

	const IMEI_LIST = array("357120093538457", "358213083247251", "353107095806820", "864510039799492");
}


// // $api2 = "https://www.fast2sms.com/dev/bulk?authorization=CbSpQve5NE&sender_id=SLAUTO&message={MESSAGE}&language=english&route=t&numbers={PHONE_NO}&flash=0";

// $variables = array("first_name"=>"John","last_name"=>"Smith","status"=>"won");
// $string = 'Dear {FIRST_NAME} {LAST_NAME}, we wanted to tell you that you {STATUS} the competition.';

// foreach($variables as $key => $value){
//     $string = str_replace('{'.strtoupper($key).'}', $value, $string);
// }



// function msgString($message, $phone_no){

// 	$variables = array("MESSAGE"=>$message,"PHONE_NO"=>$phone_no);
// 	$msg_string = Globals2::SMS_API;

// 	foreach($variables as $key => $value){
// 		$msg_string = str_replace('{'.strtoupper($key).'}', $value, $msg_string);
// 	}

// 	return $msg_string;
// }


// foreach (Globals2::IMEI_LIST as $key => $value) {
// 	echo $value;
// }

print_r(Globals::IMEI_LIST);

// if(in_array("BM", Globals::IMEI_LIST)){
// 	echo "f";
// }

// $msg = Globals::msgString("tesasdt msg",123, true);


// echo $msg	;

// echo 0;

<<<<<<< HEAD
// echo '<pre>';

// details from CUSTOMERS
// $sql = "SELECT * FROM `customers` WHERE 1";
// $exe = mysqli_query($conn ,$sql);
// while ($row = mysqli_fetch_assoc($exe)) {
// 	// display company or name
// 	$display_name = ucwords($row['cust_company']);
// 	if($display_name == ""){
// 		$display_name = ucwords($row['cust_f_name']." ".$row['cust_l_name']);
// 	}
// 	// encode results
// 	$json['success'] 	= true;	
// 	$json['cust_name'] 	= $display_name;

// 	$cust_post_paid 	= $row["cust_post_paid"];
// 	$cust_app_limit 	= (float)$row["cust_app_limit"];
// 	$cust_outstanding 	= (float)$row["cust_outstanding"];
// 	$cust_balance 		= (float)$row["cust_balance"];
// 	$cust_credit_limit 	= (float)$row["cust_credit_limit"];



// 	// $cust_app_limit 	= 20;
// 	// $cust_outstanding 	= 400;
// 	// $cust_balance 		= $row["cust_balance"];
// 	// $cust_credit_limit 	= 50;

// 	if($cust_post_paid == "Y"){
// 		$working_balance = $cust_credit_limit - $cust_outstanding;
// 	}
// 	else{
// 		$working_balance = $cust_balance;
// 	}	
	
// 	(float)$alert_value = 0.0;
// 	$alert = false;
// 	if($working_balance > 0){ 
// 		// customer has not crossed credit limit
// 		if($working_balance <= $cust_app_limit){
// 			// customer working balance is less than app limit
// 			$alert = true;
// 			$alert_value = $working_balance;
// 		}
// 	}
// 	else{ 		
// 		// customer has exceeded credit-limit		
// 		$alert = true;		
// 	}






// 	// echo "cust_post_paid\t".$cust_post_paid;
// 	// echo "<br>";
	
// 	// echo "cust_outstanding\t".$cust_outstanding;
// 	// echo "<br>";
// 	// echo "cust_balance\t".$cust_balance;
// 	// echo "<br>";
// 	// // echo "cust_credit_limit\t".$cust_credit_limit;
// 	// // echo "<br>";
// 	// echo "working_balance\t".$working_balance;
// 	// echo "<br>";
// 	if($alert){
// 		echo "cust_app_limit\t".$row["cust_id"];
// 	echo "<br>";
// 		// ini_set("serialize_precision",-1);
// 		// echo ini_get("serialize_precision");
// 		var_dump($alert_value);
// 		echo "<br>";
// 	// echo "alert\ttrue";
// 	}
// 	// else{
// 	// echo "alert\tfalse";
// 	// }

	
// 	// echo "alert_value\t".$alert_value;
// 	// echo "<br>";
// 	// echo "<br>";
// }

print_r(Globals::generateRandTest());
// print_r($json);
// echo '</pre>';




=======
>>>>>>> parent of ec85f4b... add receipt from android
?>