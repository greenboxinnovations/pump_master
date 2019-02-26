<?php

require_once $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';


function sendMSG($car_no_plate, $phone_no){

	$message = "Hi, Yor vehicle no ".$car_no_plate." has been assigned a QR code";
    $encodedMessage = urlencode($message);

    $api = Globals::msgString($encodedMessage, $phone_no, true);
    // $api = "https://www.fast2sms.com/dev/bulk?authorization=CbSpQve5NE&sender_id=SLAUTO&message=" . $encodedMessage . "&language=english&route=t&numbers=".trim($phone_no)."&flash=0"; 

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
}



 
// function updateSyncTable($table_name){ 
// 	Global $conn;
	
// 	date_default_timezone_set("Asia/Kolkata");
// 	$date = date("Y-m-d H:i:s");
// 	$unix = strtotime($date);

// 	$upload_dir =  realpath(__DIR__ . '/../mysql_uploads');
// 	$filename = $upload_dir ."/".$table_name.'.sql';
// 	$db_name = "pump_master";
	
// 	// exec("/usr/bin/mysqldump -u\"pump_master_user\" --password=\"pump_master_user123!@#\" \"".$db_name."\" \"".$table_name."\" > ".$filename);
// 	exec(Globals::MYSQLDUMP_PATH." -u\"".Globals::DB_USER_NAME."\" --password=\"".Globals::DB_PASSWORD."\" \"".Globals::DB_NAME."\" \"".$table_name."\" > ".$filename);

// 	$sql = "UPDATE `sync` SET `last_updated`= '".$unix."' WHERE `table_name` = '".$table_name."';";
// 	$exe = mysqli_query($conn, $sql);
// }

if($_SERVER['REQUEST_METHOD'] == "POST"){

	// decode into array
	$postParams = json_decode(file_get_contents("php://input"), true);

	$json = array();
	$json['success'] = false;

	// check if all parameters are set
	if(
		isset($postParams["cust_id"]) && 
		isset($postParams["car_id"]) && 
		isset($postParams["qr_code"])
	){
		$cust_id 	= $postParams["cust_id"];	
		$car_id 	= $postParams["car_id"];
		$qr_code 	= $postParams["qr_code"];

		$sql = "SELECT 1 FROM `codes` WHERE `qr_code` = '".$qr_code."' ;";
		$exe = mysqli_query($conn, $sql);
		$count = mysqli_num_rows($exe);

		if ($count == 1) {
			
			$sql = "SELECT * FROM `cars` WHERE `car_qr_code` = '".$qr_code."' ;";
			$exe = mysqli_query($conn, $sql);
			$count = mysqli_num_rows($exe);

			if ($count == 0) {

				$sql5 = "UPDATE `cars` SET `car_qr_code` = '".$qr_code."'  WHERE `car_id` = '".$car_id."' ;";
				$exe5 = mysqli_query($conn, $sql5);

				date_default_timezone_set("Asia/Kolkata");
				$date_new = date("Y-m-d H:i:s");
				$unix = strtotime($date_new);

				$json['success'] 	= true;

				try {

					$sql3 = "SELECT * FROM `cars` a 
							JOIN `customers` b
							ON a.car_cust_id = b.cust_id
							WHERE a.car_id = '".$car_id."';";
					$exe3 = mysqli_query($conn, $sql3);
					while($row3 = mysqli_fetch_assoc($exe3)){

						$cust_f_name	 = $row3["cust_f_name"];
						$cust_m_name	 = $row3["cust_m_name"];
						$cust_l_name	 = $row3["cust_l_name"];
						$cust_company	 = $row3['cust_company'];
						$car_no_plate	 = $row3['car_no_plate'];

						if($cust_company == ""){
							$cust_name 		 = ucwords($cust_f_name.' '.$cust_m_name.' '.$cust_l_name);
						}
						else{
							$cust_name 		 = ucwords($cust_company);
						}
					}
					$car_no_plate = str_replace(' ','',$car_no_plate);
					$msg_string = $car_no_plate." ".$cust_name;
					$json['msg']  = "QR Added Succesfully";
					sendMSG($msg_string, 9762230207);
					// updateSyncTable("cars");
					Globals::updateSyncTable("cars", "car_id", $unix);

				} catch (Exception $e) {
					$json['msg']  = "QR Exception";
					sendMSG($car_id, 9762230207);	
				}
				
				// updateSyncTable("users", "user_id", $unix);
			}else{
				$row = mysqli_fetch_assoc($exe);
				$car_no_plate = $row['car_no_plate'];
				$car_no_plate = strtoupper(str_replace(' ','',$car_no_plate));
				$json['msg']  = "Duplicate Code ".$car_no_plate;
			}
		}else{
			$json['msg']  = "Invalid Code";
		}		
	}
	// not all parameters sent
	else{
		$json['msg']  = "Insuficient Data";
	}

	// encode response
	echo json_encode($json);
}


?>