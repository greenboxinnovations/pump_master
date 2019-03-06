<?php
date_default_timezone_set("Asia/Kolkata");
require_once $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';

$json = file_get_contents('php://input');
$obj = json_decode($json,true);

$qr = addslashes($obj['qr']);

$json = array();

if($qr != ""){

	// get details from cars
	$sql1 = "SELECT * FROM `cars` WHERE `car_qr_code` = '".$qr."';";
	$exe1 = mysqli_query($conn ,$sql1);

	if(mysqli_num_rows($exe1) > 0){
		// fetch results
		$pre_row = mysqli_fetch_assoc($exe1);
		// details from CARS
		$cust_id 	= $pre_row['car_cust_id'];
		$car_id 	= $pre_row['car_id'];
		$fuel_type 	= $pre_row['car_fuel_type'];
		if($fuel_type == "petrol"){
			$isPetrol = true;
		}
		else{
			$isPetrol = false;
		}
		$car_no 	= $pre_row['car_no_plate'];

		// details from CUSTOMERS
		$sql = "SELECT * FROM `customers` WHERE `cust_id` = '".$cust_id."';";
		$exe = mysqli_query($conn ,$sql);
		$row = mysqli_fetch_assoc($exe);

		// display company or name
		$display_name = ucwords($row['cust_company']);
		if($display_name == ""){
			$display_name = ucwords($row['cust_f_name']." ".$row['cust_l_name']);
		}
		// encode results
		$json['success'] 	= true;
		$json['cust_id'] 	= $cust_id;
		$json['cust_name'] 	= $display_name;
		$json['isPetrol'] 	= $isPetrol;
		$json['car_no'] 	= $car_no;
		$json['car_id'] 	= $car_id;

		$cust_post_paid 	= $row["cust_post_paid"];
		$cust_app_limit 	= $row["cust_app_limit"];
		$cust_outstanding 	= $row["cust_outstanding"];
		$cust_balance 		= $row["cust_balance"];
		$cust_credit_limit 	= $row["cust_credit_limit"];

		// $json['cust_credit_limit'] 			= $cust_credit_limit;
		// $json['cust_outstanding'] 	= $cust_outstanding;




		if($cust_post_paid == "Y"){
			$working_balance = $cust_credit_limit - $cust_outstanding;
		}
		else{
			$working_balance = $cust_balance;
		}

		ini_set("serialize_precision",-1);		
		
		$alert_value = 0;
		$alert = false;
		if($working_balance > 0){ 
			// customer has not crossed credit limit
			if($working_balance <= $cust_app_limit){
				// customer working balance is less than app limit
				$alert = true;
				$alert_value = number_format(round($working_balance, 2), 2);
			}
		}
		else{ 		
			// customer has exceeded credit-limit		
			$alert = true;		
		}

		$json['alert'] 			= $alert;
		$json['alert_value'] 	= $alert_value;

	}
	else{
		// no matches in cars db
		$json['success'] = false;

		$sql_c = "SELECT 1 FROM `codes` WHERE `qr_code` = '".$qr."' ;";
		$exe_c = mysqli_query($conn, $sql_c);
		$count = mysqli_num_rows($exe_c);

		if($count == 0){
			$json['msg'] = 'Invalid QR-Code';
		}
		else{
			$json['msg'] = 'Unassigned QR-Code';
		}		
	}		
}
else{
	// QR is empty
	$json['success'] = false;
	$json['msg'] = "Empty QR-Code";
}

echo json_encode($json, JSON_NUMERIC_CHECK);

?>