<?php

require '../query/conn.php';

function updateSyncTable($table_name){
	Global $conn;
	
	date_default_timezone_set("Asia/Kolkata");
	$date = date("Y-m-d H:i:s");
	$unix = strtotime($date);

	$upload_dir =  realpath(__DIR__ . '/../mysql_uploads');
	$filename = $upload_dir ."/".$table_name.'.sql';
	$db_name = "pump_master_test";
	
	exec("/usr/bin/mysqldump -u\"pump_master_user\" --password=\"pump_master_user123!@#\" \"".$db_name."\" \"".$table_name."\" > ".$filename);

	$sql = "UPDATE `sync` SET `last_updated`= '".$unix."' WHERE `table_name` = '".$table_name."';";
	$exe = mysqli_query($conn, $sql);
}





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
			
			$sql = "SELECT 1 FROM `cars` WHERE `car_qr_code` = '".$qr_code."' ;";
			$exe = mysqli_query($conn, $sql);
			$count = mysqli_num_rows($exe);

			if ($count == 0) {

				$sql5 = "UPDATE `cars` SET `car_qr_code` = '".$qr_code."'  WHERE `car_id` = '".$car_id."' ;";
				$exe5 = mysqli_query($conn, $sql5);

				date_default_timezone_set("Asia/Kolkata");
				$date_new = date("Y-m-d H:i:s");
				$unix = strtotime($date_new);

				$json['success'] 	= false;

				updateSyncTable("users", "user_id", $unix);
			}else{

				$json['msg']  = "Duplicate Code";
			}
			

		}else{
			$json['msg']  = "Invalid Code";
		}	
		
		updateSyncTable("cars");
	}
	// not all parameters sent
	else{
		$json['msg']  = "Insuficient Data";
	}

	// encode response
	echo json_encode($json);
}


?>