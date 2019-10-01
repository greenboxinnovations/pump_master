<?php
date_default_timezone_set("Asia/Kolkata");

class Admin
{
	private $_db;
	private $_method;
	private $_getParams = null;
	private $_postParams = null;

	public function __construct($db,$method,$getParams,$postParams){

		if (version_compare(phpversion(), '7.1', '>=')) {
			ini_set( 'serialize_precision', -1 );
		}


		$this->_db = $db->getInstance();
		$this->_method = $method;
		$this->_getParams = $getParams;
		$size = sizeof($this->_getParams);
		$this->_postParams = $postParams;

		if($this->_method=='GET')
		{
			
			switch ($size) {
				case 0:
					// $this->getUsers();
					// echo 'working admin';
				break;
				case 1:
				if($this->_getParams[0] == "transactions"){
					$this->getTransactions(date('Y-m-d'));
							// echo 'shjow trancs';
				}

					// echo '<pre>';
					// print_r($this->_getParams[0]);
					// echo '</pre>';


				break;
				case 2:
					//$this->getAllCarsShifts(0,$today);
				if($this->_getParams[0] == "transactions"){
					$date = $this->_getParams[1];
					if($this->validateDate($date)){
						$this->getTransactions($date);
					}
					
							// echo 'shjow trancs';
				}
				break;
				default:
					// $output = array();
					// $output['error'] = 'no date provided';
					// echo json_encode($output);
				break;
			}
		}
		elseif ($this->_method == 'POST')
		{
		}
	}

	// private function getUsers($pump_id){
	// 	$sql = "SELECT * FROM `users` WHERE `user_pump_id` = '".$pump_id."' AND `role` = 'operator' ;";
	// 	$this->_db->query($sql);
	// 	$this->_db->execute();

	// 	$output=array();
	// 	$json=array();

	// 	if($this->_db->rowCount() > 0)
	// 	{
	// 		$r = $this->_db->resultset();
	// 		foreach ($r as $row) {

	// 			$json["user_id"]		= $row["user_id"];
	// 			$json["name"]	 		= $row["name"];
	// 			$json["user_pump_id"]	= $row["user_pump_id"];
	// 			array_push($output, $json);
	// 		}
	// 	}
	// 	echo json_encode($output,JSON_NUMERIC_CHECK);
	// }

	private function getTransactions($date){
		// echo $date;

		$sql = "SELECT b.cust_company,b.cust_disp_name,b.cust_post_paid, a.cust_id,a.*,c.car_no_plate 
		FROM `transactions` a 
		JOIN `customers` b ON a.cust_id = b.cust_id 
		JOIN `cars` c ON c.car_id = a.car_id 
		WHERE date(a.date) = :field1 ORDER BY b.cust_company ASC,a.trans_string DESC;";

		$this->_db->query($sql);
		$this->_db->bind(':field1', $date);
		$this->_db->execute();


		$output=array();
		if($this->_db->rowCount() > 0)
		{
			$r = $this->_db->resultset();
			foreach ($r as $row) {
				$json=array();
				$json["cust_id"]	 	= $row["cust_id"];
				$json["cust_disp_name"]	= $row['cust_disp_name'];
				$json["liters"]	 		= $row["liters"];
				$json["rate"]	 		= $row["rate"];
				$json["amount"]	 		= $row["amount"];
				$json["timestamp"] 		= $row["date"];	
				$json["date"] 			= date('Y-m-d',strtotime($row["date"]));	
				$json["car_no_plate"] 	= strtoupper($row["car_no_plate"]);
				$json["trans_time"] 	= $row["trans_time"];
				$json["trans_string"]	= $row["trans_string"];	
				array_push($output, $json);				
			}
			
		}
		echo json_encode($output,JSON_NUMERIC_CHECK);
	}

	private function validateDate($date, $format = 'Y-m-d') {
		$d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
		return $d && $d->format($format) === $date;
	}
}
?>