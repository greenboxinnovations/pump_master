<?php

require '../query/conn.php';

private function updateSyncTable($table_name){
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
		
		updateSyncTable("cars");
	}
	// not all parameters sent
	else{
		$json['success'] 	= false;
		$json['msg']		= "Insuficient Data";

	}

	// encode response
	echo json_encode($json);
}


?>