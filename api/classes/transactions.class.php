<?php
date_default_timezone_set("Asia/Kolkata");

class Transactions
{
	private $_db;
	private $_method;
	private $_getParams = null;
	private $_postParams = null;

	public function __construct($db, $method, $getParams, $postParams){

		$this->_db = $db->getInstance();
		$this->_method = $method;
		$this->_getParams = $getParams;
		$size = sizeof($this->_getParams);
		$this->_postParams = $postParams;

		if($this->_method=='GET')
		{
			$today = date("Y-m-d");
			switch ($size) {
				case 0:
					// $this->getAllCarsShifts(0,$today);				
					break;
				case 1:
					//$this->getAllCarsShifts(0,$today);
					break;
				case 2:
					//$this->getAllCarsShifts(0,$today);
					break;
				default:
					$output = array();
					$output['error'] = 'no date provided';
					echo json_encode($output);
					break;
			}
		}
		elseif ($this->_method == 'POST')
		{
			if ($this->_getParams[0] == 'backup')
			{
				$this->postTransactionBackup($this->_postParams);
			}

			else if ($this->_getParams[0] == 'regular')
			{
				$this->postTransaction($this->_postParams);
			}

			else if ($this->_getParams[0] == 'android')
			{
				$this->postAndroidTransaction($this->_postParams);
			}	

			else if ($this->_getParams[0] == 'rates')
			{
				$this->postRates($this->_postParams);
			}

			else if ($this->_getParams[0] == 'print')
			{
				$this->printReceipt(8183);
			}


			else if ($this->_getParams[0] == 'save_local_transactions')
			{
				$this->save_local_transactions($this->_postParams);				
			}

			else if ($this->_getParams[0] == 'delete')
			{
				$this->deleteTransaction($this->_postParams);
			}		
		}
	}


	private function postTransaction($postParams){

		if(!isset($_SESSION))
		{
			session_start();
		}

		date_default_timezone_set("Asia/Kolkata");

		$valid = false;
		$output = array();


		$type 		= $postParams['type'];

		$pump_id		 = $postParams['pump_id'];
		$pump_id		 = trim($pump_id);

		if ($pump_id == -2) {
			$pump_id = $_SESSION['pump_id'];
		}

		$cust_id		 = $postParams['cust_id'];
		$cust_id	 	 = trim($cust_id);

		$car_id			 = $postParams['car_id'];
		$car_id	 		 = trim($car_id);



		if ($car_id == -1) {
			$fuel 			= $postParams['fuel'];
			$car_no_plate 	= $postParams['car_no_plate'];


			$sql = "INSERT INTO `cars` (`car_brand`,`car_sub_brand`,`car_no_plate`,`car_pump_id`,`car_fuel_type`,`car_cust_id`) VALUES ('unknown','unknown',:field1,:field4,:field2,:field3);";

			$this->_db->query($sql);
			$this->_db->bind(':field1', $car_no_plate);
			$this->_db->bind(':field2', $fuel);
			$this->_db->bind(':field3', $cust_id);
			$this->_db->bind(':field4', $pump_id);
			$this->_db->execute();


			$sql = "SELECT `car_id` FROM `cars` WHERE `car_no_plate` = '".$car_no_plate."';";
			$this->_db->query($sql);
			$this->_db->execute();
			$row = $this->_db->single();
			$car_id = $row['car_id'];
		}
		else{
			$sql = "SELECT `car_fuel_type` FROM `cars` WHERE `car_id` = '".$car_id."';";		
			$this->_db->query($sql);
			$r = $this->_db->single();
			$fuel = $r['car_fuel_type'];
		}

		$receipt_no	 = $postParams['receipt_no'];
		$receipt_no	 = trim($receipt_no);

		if (($receipt_no != 0)&&($type == 'new')) {

			$sql = "SELECT `cust_id` FROM `receipt_books` WHERE '".$receipt_no."' BETWEEN `min` AND `max` AND '".$receipt_no."' NOT IN (SELECT `receipt_no` FROM `transactions` WHERE `receipt_no` = '".$receipt_no."') ;";
			$this->_db->query($sql);
			$this->_db->execute();

			if($this->_db->rowCount() > 0){
				$r = $this->_db->single();

				if ($cust_id == $r['cust_id']) {
					$valid = true;
				}
				else{
					$output['success'] = false;
					$output['msg'] = 'Wrong Receipt No';
				}			
			}
			else{
				$output['success'] = false;
				$output['msg'] = 'Wrong Receipt No';
			}
		}
		else{
			$valid = true;
		}

		$user_id			 = $postParams['user_id'];
		$user_id	 		 = trim($user_id);
		

		$is_postpaid	 = $postParams['is_postpaid'];

		$amount		 	 = $postParams['amount'];
		$liters		 	 = number_format($postParams['liters'],2);

		$rate 			 = $postParams['rate'];
	
		$date = date("Y-m-d H:i:s" , strtotime($postParams['date']));

		if ($user_id == -2) {
			$user_id = $_SESSION['user_id'];			
			$time = date("H:i:s");
			$date = $postParams['date']." ".$time;
		}

		$this->updateOldRate($rate, $date, $fuel, $pump_id);

		$last_updated	 = $date;		

		$trans_id 	= $postParams['trans_id'];

		if ($postParams['shift'] != NULL) {
			$shift	 		 = $postParams['shift'];
		}else{
			$output['success'] = false;
			$valid = false;
		}

		if ($valid) {
			if ($type == 'new') {
				
				$sql = "INSERT INTO `transactions` (`pump_id`,`cust_id`,`car_id`,`user_id`,`receipt_no`,`fuel`,`amount`,`rate`,`liters`,`date`,`last_updated`,`shift`) 
						VALUES (:field1,:field2,:field3,:field4,:field5,:field6,:field7,:field8,:field9,:field10,:field11,:field12);";

				$this->_db->query($sql);

				$this->_db->bind(':field1', $pump_id);
				$this->_db->bind(':field2', $cust_id);
				$this->_db->bind(':field3', $car_id);
				$this->_db->bind(':field4', $user_id);
				$this->_db->bind(':field5', $receipt_no);
				$this->_db->bind(':field6', $fuel);
				$this->_db->bind(':field7', $amount);
				$this->_db->bind(':field8', $rate);
				$this->_db->bind(':field9', $liters);
				$this->_db->bind(':field10', $date);
				$this->_db->bind(':field11', $last_updated);
				$this->_db->bind(':field12', $shift);
				$this->_db->execute();


				if ($is_postpaid) {
					$sql = "UPDATE `customers` SET `cust_outstanding` = `cust_outstanding`+ '".$amount."' WHERE `cust_id` = '".$cust_id."' ;";
				}else{
					$sql = "UPDATE `customers` SET `cust_balance` = `cust_balance`- '".$amount."' WHERE `cust_id` = '".$cust_id."' ;";
				}
				$this->_db->query($sql);
				$this->_db->execute();

			}
			else{

				$sql = "SELECT `amount` FROM `transactions` WHERE `trans_id` = '".$trans_id."';";
				$this->_db->query($sql);
				$this->_db->execute();
				$row = $this->_db->single();
				$old_amount = $row['amount'];


				$last_updated = date("Y-m-d H:i:s");


				$sql = "UPDATE `transactions` SET `amount`= :field1,`rate`= :field2, `shift` = :field4, `liters`= :field3,`date` = :field5, `last_updated`= :field6, `user_id` = :field8 WHERE `trans_id` = :field7 ;";

				$this->_db->query($sql);

				$this->_db->bind(':field1', $amount);
				$this->_db->bind(':field2', $rate);
				$this->_db->bind(':field3', $liters);
				$this->_db->bind(':field4', $shift);
				$this->_db->bind(':field5', $date);
				$this->_db->bind(':field6', $last_updated);
				$this->_db->bind(':field7', $trans_id);
				$this->_db->bind(':field8', $user_id);
				$this->_db->execute();


				$new_amount = $amount - $old_amount;


				if ($is_postpaid) {
					$sql = "UPDATE `customers` SET `cust_outstanding` = `cust_outstanding`+ '".$new_amount."' WHERE `cust_id` = '".$cust_id."' ;";
				}else{
					$sql = "UPDATE `customers` SET `cust_balance` = `cust_balance`- '".$new_amount."' WHERE `cust_id` = '".$cust_id."' ;";
				}
				$this->_db->query($sql);
				$this->_db->execute();

			}


			$table_name	  = "transactions";
			$id           = "trans_id";
			$unix = strtotime($last_updated); 

			$this->updateSyncTable($table_name, $id, $unix);

		
			$output['success'] = true;
		}

		
		echo json_encode($output);
	}

	private function postAndroidTransaction($postParams){

		date_default_timezone_set("Asia/Kolkata");

		$valid = false;
		$output = array();


		$pump_id		= trim($postParams['pump_id']);		

		$car_id			= trim($postParams['car_id']);		

		$cust_id		= trim($postParams['cust_id']);		

		$isPetrol		= $postParams['isPetrol'];
		$fuel 			= $isPetrol ? 'petrol' : 'diesel';

		$user_id		= trim($postParams['user_id']);		


		$amount		 	= trim($postParams['amount']);		
		$liters		 	= number_format($postParams['liters'],2);

		$rate 			= trim($postParams['fuel_rate']);
		
		$pre_shift	 	= trim($postParams['shift']);
		$shift 			= ($pre_shift == "a") ? 1 : 2;

		$date 			= date("Y-m-d H:i:s");
		$last_updated	= $date;

		$pump_code      = trim($postParams['pump_code']);	

		$sql = "SELECT `trans_string` FROM `cameras` WHERE `cam_qr_code` = '".$pump_code."';";		
		$this->_db->query($sql);
		$r = $this->_db->single();
		$trans_string = $r['trans_string'];


		$sql_pre = "SELECT `last_updated` FROM `transactions` 
					WHERE `pump_id` = :field1
					AND `cust_id` = :field2
					AND `car_id` = :field3
					AND date(`date`) = :field4;";
		$this->_db->query($sql_pre);
		$this->_db->bind(':field1', $pump_id);
		$this->_db->bind(':field2', $cust_id);
		$this->_db->bind(':field3', $car_id);
		$this->_db->bind(':field4', date('Y-m-d'));
		$this->_db->execute();
		
		if($this->_db->rowCount() > 0){
			$r = $this->_db->single();


			$last_found = $r['last_updated'];

			$diff = strtotime($date) - strtotime($last_found);

			// 20 seconds
			if($diff > 20){
				$valid = true;
			}			
		}
		else{
			$valid = true;
		}

		if($valid){
			$sql = "INSERT INTO `transactions` (`pump_id`,`cust_id`,`car_id`,`user_id`,`fuel`,`amount`,`rate`,`liters`,`date`,`last_updated`,`shift`,`trans_string`) VALUES (:field1,:field2,:field3,:field4,:field5,:field6,:field7,:field8,:field9,:field10,:field11,:field12);";

			$this->_db->query($sql);

			$this->_db->bind(':field1', $pump_id);
			$this->_db->bind(':field2', $cust_id);
			$this->_db->bind(':field3', $car_id);
			$this->_db->bind(':field4', $user_id);		
			$this->_db->bind(':field5', $fuel);
			$this->_db->bind(':field6', $amount);
			$this->_db->bind(':field7', $rate);
			$this->_db->bind(':field8', $liters);
			$this->_db->bind(':field9', $date);
			$this->_db->bind(':field10', $last_updated);
			$this->_db->bind(':field11', $shift);
			$this->_db->bind(':field12', $trans_string);
			$this->_db->execute();

			$output['success'] = true;	

			$table_name	  = "transactions";
			$id           = "trans_id";
			$unix = strtotime($last_updated); 

			$sql = "SELECT `trans_id` FROM `transactions` WHERE 1 ORDER BY `trans_id` DESC LIMIT 1;";		
			$this->_db->query($sql);
			$r = $this->_db->single();
			$trans_id = $r['trans_id'];

			$this->printReceipt($trans_id);
			$this->printReceipt($trans_id);
			$this->updateSyncTable($table_name,$id,$unix);


		}
		else{
			$output['success'] 	= false;		
			$output['msg'] 		= "Something went wrong";		
		}		
		echo json_encode($output);
	}

	private function printReceipt($trans_id){

		try {
	      file_get_contents("http://192.168.0.100/pump_master/print/print.php?trans_id=".$trans_id);
	    } catch (Exception $e) {
	    	
	    }

	}

	private function postRates($postParams){
		date_default_timezone_set("Asia/Kolkata");

		if(!isset($_SESSION))
		{
			session_start();
		}

		$output = array();
		if(isset($postParams['pump_id'])){			
			$pump_id 			= $postParams['pump_id'];
			$output['source'] 	= 'Android';
		}
		else{
			$pump_id 			= $_SESSION['pump_id'];
			$output['source'] 	= 'Web';
		}

		$petrol 	= trim($postParams['petrol']);
		$diesel		= trim($postParams['diesel']);
		
		$date = date("Y-m-d");

		$sql1 = "SELECT * FROM `rates` WHERE `date` = '".$date."';";	
		$this->_db->query($sql1);
		$this->_db->execute();		

		if($this->_db->rowCount() < 2){
			$sql = "INSERT INTO `rates` (`pump_id`,`petrol`,`diesel`,`date`) VALUES (:field1,:field2,:field3,:field4);";
			$this->_db->query($sql);

			$this->_db->bind(':field1', $pump_id);
			$this->_db->bind(':field2', $petrol);
			$this->_db->bind(':field3', $diesel);
			$this->_db->bind(':field4', $date);

			$this->_db->execute();

			$table_name	  = "rates";
			$id           = "rate_id";
			$date_new = date("Y-m-d H:i:s");
			$unix = strtotime($date_new); 

			$this->updateSyncTableRates($table_name,$id,$unix);


			$output['success'] = true;
			$output['msg'] = 'Rates updated successfully!';
			$output['unix'] = $unix;
			
		}
		else{
			$output['success'] = false;
			$output['msg'] = 'Rates already Set!';
		}
		echo json_encode($output);
	}


	private function updateOldRate($rate, $date, $fuel_type, $pump_id){
		date_default_timezone_set("Asia/Kolkata");
		$check_date = date("Y-m-d" , strtotime($date));

		if($fuel_type == 'petrol'){
			$sql = "SELECT `petrol` FROM `rates` WHERE `date` = '".$check_date."';";
		}
		else if($fuel_type == 'diesel'){
			$sql = "SELECT `diesel` FROM `rates` WHERE `date` = '".$check_date."';";
		}

		$this->_db->query($sql);
		$this->_db->execute();		

		// rate not found 
		// insert into rates		
		if($this->_db->rowCount() == 0)
		{
			if($fuel_type == 'petrol'){

				$sql = "INSERT INTO `rates` (`pump_id`,`petrol`,`date`) VALUES (:field1,:field2,:field3);";
				$this->_db->query($sql);
				$this->_db->bind(':field1', $pump_id);
				$this->_db->bind(':field2', $rate);
				$this->_db->bind(':field3', $date);
				$this->_db->execute();
			}
			else if($fuel_type == 'diesel'){

				$sql = "INSERT INTO `rates` (`pump_id`,`diesel`,`date`) VALUES (:field1,:field2,:field3);";
				$this->_db->query($sql);
				$this->_db->bind(':field1', $pump_id);
				$this->_db->bind(':field2', $rate);
				$this->_db->bind(':field3', $date);
				$this->_db->execute();
			}

			$table_name	  = "rates";
			$id           = "rate_id";
			$date_new = date("Y-m-d H:i:s");
			$unix = strtotime($date_new);

			$this->updateSyncTable($table_name,$id,$unix);
		}
		else{
			$row = $this->_db->single();
			if($fuel_type == 'petrol'){
				if($row["petrol"] == '0.00'){

					$sql = "UPDATE `rates` SET `petrol` = :field2 
							WHERE `pump_id` = :field1 AND `date` = :field3;";
					$this->_db->query($sql);
					$this->_db->bind(':field1', $pump_id);
					$this->_db->bind(':field2', $rate);
					$this->_db->bind(':field3', $check_date);
					$this->_db->execute();
				}
			}
			else if($fuel_type == 'diesel'){
				if($row["diesel"] == '0.00'){

					$sql = "UPDATE `rates` SET `diesel` = :field2 
							WHERE `pump_id` = :field1 AND `date` = :field3;";
					$this->_db->query($sql);
					$this->_db->bind(':field1', $pump_id);
					$this->_db->bind(':field2', $rate);
					$this->_db->bind(':field3', $check_date);
					$this->_db->execute();
				}
			}
		}

		
	}

	private function deleteTransaction($postParams){
		
		$trans_id = $postParams['trans_id'];


		$sql = "SELECT `amount`,`cust_id` FROM `transactions` WHERE `trans_id` = '".$trans_id."';";
		$this->_db->query($sql);
		$r = $this->_db->single();
		$amount = $r['amount'];
		$cust_id = $r['cust_id'];


		$sql = "SELECT `cust_post_paid` FROM `customers` WHERE `cust_id` = '".$cust_id."'  ;";
		$this->_db->query($sql);
		$r = $this->_db->single();
		$cust_post_paid = $r['cust_post_paid'];
		if ($cust_post_paid == 'Y') {
			$sql = "UPDATE `customers` SET `cust_outstanding` = `cust_outstanding`-'".$amount."' WHERE `cust_id` = '".$cust_id."' ;";
		}else{
			$sql = "UPDATE `customers` SET `cust_balance` = `cust_balance`+'".$amount."' WHERE `cust_id` = '".$cust_id."' ;";
		}

		$this->_db->query($sql);
		$this->_db->execute();


		$sql = "DELETE FROM `transactions` WHERE `trans_id` = '".$trans_id."' ";
		$this->_db->query($sql);
		$this->_db->execute();

		echo'Transaction deleted Successfully';
	}

	private function updateSyncTable($table_name, $id, $unix){
		date_default_timezone_set("Asia/Kolkata");
		$date = date("Y-m-d H:i:s");
		
		$upload_dir =  realpath(__DIR__ . '/../../mysql_uploads');
		$filename = $upload_dir ."/".$table_name.'.sql';
		$db_name = "pump_master";

		if ($table_name == "customers") {
			
			exec("/usr/bin/mysqldump -u\"pump_master_user\" --password=\"pump_master_user123!@#\"  -t \"".$db_name."\" \"".$table_name."\" > ".$filename);
		}
		

		$sql = "UPDATE `sync` SET `last_updated`= '".$unix."' WHERE `table_name` = '".$table_name."';";
		
		$this->_db->query($sql);
		$this->_db->execute();

		 
	} 

	private function updateSyncTableRates($table_name, $id, $unix){
		date_default_timezone_set("Asia/Kolkata");
		$date = date("Y-m-d H:i:s");

		$sql = "UPDATE `sync` SET `last_updated`= '".$unix."', `id` = `id`+1 WHERE `table_name` = '".$table_name."';";
		
		$this->_db->query($sql);
		$this->_db->execute();

		
	}


	// takes json array of transactions
	// send back prim key of local
	// local will delete keys received
	private function save_local_transactions($postParams){
		date_default_timezone_set("Asia/Kolkata");
		$output = array();
		$d = false;
		foreach ($postParams as $row) {	

			$sql1 = "SELECT * FROM `transactions` WHERE `car_id` = '".$row['car_id']."' AND  date(`date`) = '".$row['date']."' AND `amount` = '".$row['amount']."' ;";	
			$this->_db->query($sql1);
			$this->_db->execute();

			if($this->_db->rowCount() == 0){


				$sql111 = "INSERT INTO `transactions`(`pump_id`, `cust_id`, `car_id`, `user_id`, `receipt_no`, `shift`, `fuel`, `amount`, `rate`, `liters`, `billed`, `date`, `last_updated`,`trans_string`) VALUES ('".$row['pump_id']."','".$row['cust_id']."','".$row['car_id']."','".$row['user_id']."','".$row['receipt_no']."','".$row['shift']."','".$row['fuel']."','".$row['amount']."','".$row['rate']."','".$row['liters']."','".$row['billed']."','".$row['date']."','".$row['last_updated']."','".$row['trans_string']."');";
		
				$this->_db->query($sql111);
				$this->_db->execute();


				//get cust type
				$sql = "SELECT `cust_post_paid`,`cust_ph_no` FROM `customers` WHERE `cust_id` = '".$row['cust_id']."';";		
				$this->_db->query($sql);
				$r = $this->_db->single();
				$ph_no = $r['cust_ph_no'];
				$post_paid =$r['cust_post_paid'];

				if($r['cust_post_paid'] == "Y"){

					$sql = "UPDATE `customers` SET `cust_outstanding` = `cust_outstanding`+ '".$row['amount']."' WHERE `cust_id` = '".$row['cust_id']."' ;";
				}else{
					$sql = "UPDATE `customers` SET `cust_balance` = `cust_balance`- '".$row['amount']."' WHERE `cust_id` = '".$row['cust_id']."' ;";
				}

				$this->_db->query($sql);
				$this->_db->execute();


				//get car no
				$sql = "SELECT `car_no_plate` FROM `cars` WHERE `car_id` = '".$row['car_id']."';";		
				$this->_db->query($sql);
				$r = $this->_db->single();
				$car_no_plate = $r['car_no_plate'];

				$url = "http://fuelmaster.greenboxinnovations.in/c_msg.php?t=".$row['trans_string'];

				$this->sendMSG($car_no_plate, $row['fuel'], $row['amount'], $url, $ph_no);

				$d = true;

			}

			array_push($output, $row['trans_id']);			
		}

		echo json_encode($output);

		if ($d) {
			$date_new = date("Y-m-d H:i:s");
			$unix = strtotime($date_new);

			$table_name	  = "customers";
			$id           = "cust_id";
			
			$this->updateSyncTable($table_name,$id,$unix);
		}

		

	}

	private function sendMSG($car_no_plate, $fuel, $amount, $url, $phone_no){

		$message = "Hi, Yor vehicle no ".$car_no_plate." just filled ".$fuel." worth ".$amount.". details: ".$url;
	    $encodedMessage = urlencode($message);
	    $api = "https://www.fast2sms.com/dev/bulk?authorization=CbSpQve5NE&sender_id=SLAUTO&message=" . $encodedMessage . "&language=english&route=t&numbers=".$phone_no.",8411815106&flash=0";

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
}
?>