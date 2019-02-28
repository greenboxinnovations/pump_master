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

?>