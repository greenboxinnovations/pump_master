<?php

require 'query/conn.php';

if(isset($_GET['trans_string'])){
	$trans_string = $_GET['trans_string'];
	
	// prepared statement
	// $sql = "SELECT * FROM `transactions` WHERE `trans_string` = ?";
	$sql = "SELECT a.*,b.car_no_plate, c.cust_company,c.cust_f_name,c.cust_l_name
			FROM `transactions` a
			JOIN `cars` b
			ON a.car_id = b.car_id
			JOIN `customers` c
			ON c.cust_id = a.cust_id
			where a.trans_id = ?";


	$stmt = $conn->prepare($sql);
	$stmt->bind_param('s', $trans_string);

	if($stmt->execute()) {
		$result = $stmt->get_result();
		while($row = $result->fetch_array(MYSQLI_ASSOC)) {

			//--------------------------------//

			// customer company or cust name
			// date - time
			// car details
			// amount
			// fuel-type
			// litres
			// rate

			// 0 photo
			// 0 top
			// amount photo
			// amount top


			//--------------------------------//
			// customer name
			$display_name = $row['cust_company'];
			if($display_name == ""){
				$display_name = $row['cust_f_name']." ".$row['cust_l_name'];
			}
			$display_name = ucwords($display_name);

		}		
	}
	else {
	    error_log ("Didn't work");
	}

}



?>