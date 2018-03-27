<?php

require '../query/conn.php';

// $sql = "SELECT * FROM `transactions` WHERE 1";

$sql = "SELECT a.*,b.cust_f_name,b.cust_company,b.cust_m_name,b.cust_l_name,c.car_no_plate
		FROM `transactions` a 
		JOIN `customers` b ON a.cust_id = b.cust_id
		JOIN `cars` c ON c.car_id = a.car_id
		WHERE a.billed = 'N' ORDER BY a.receipt_no ASC";

$exe = mysqli_query($conn, $sql); 

echo '<table id="header-fixed"></table>';
echo '<table id="table-1">';

echo '<thead>';
	echo '<tr>';
		echo '<th class="c_id">ID</th>';
		echo '<th class="c_receipt">R-No</th>';
		echo '<th class="c_name">Cust Name</th>';
		echo '<th class="c_cno">Car No</th>';
		echo '<th class="c_amount">Amount</th>';
		echo '<th class="c_date">Date</th>';
		
	echo '</tr>';
echo '</thead>';

echo '<tbody>';
while($row = mysqli_fetch_assoc($exe)){

	// transaction details
	$trans_id	 	= $row["trans_id"];	
	$cust_id	 	= $row["cust_id"];
	$car_id	 		= $row["car_id"];
	$amount	 		= $row["amount"];
	$date	 		= $row["date"];	

	$cust_company	 = $row["cust_company"];

	if($cust_company == ""){
		// cust details
		$cust_f_name	 = $row["cust_f_name"];
		$cust_m_name	 = $row["cust_m_name"];
		$cust_l_name	 = $row["cust_l_name"];
		$cust_name 		 = ucwords($cust_f_name.' '.$cust_m_name.' '.$cust_l_name);	
	}
	else{
		$cust_name 		 = ucwords($cust_company);	
	}	

	// car details
	$car_no_plate	 = strtoupper($row["car_no_plate"]);

	// date formatting
	date_default_timezone_set("Asia/Kolkata");
	$today 			= date("Y-m-d");
	$unix_tstamp 	= strtotime($date);	// unix	timestamp
	$unix_date 		= date("Y-m-d", $unix_tstamp);	// unix	date only
	// if today show time
	// else gmail style date-format
	if($today == $unix_date){
		$display_date = date("g:i a", $unix_tstamp);	// 1.30 am, 11:00 am
	}
	else{
		$display_date = date("M d", $unix_tstamp);		// Sep 11, Aug 20
	}
	
	echo '<tr >';
		echo '<td class="c_id">'.$trans_id.'</td>';
		echo '<td class="c_receipt" style="text-align:right;">'.$row['receipt_no'].'</td>';
		echo '<td class="c_name">'.$cust_name.'</td>';
		// echo '<td>'.$cust_id.'</td>';
		// echo '<td>'.$car_id.'</td>';
		echo '<td class="c_cno">'.$car_no_plate.'</td>';
		echo '<td class="c_amount">'.$amount.'</td>';
		// echo '<td>'.$date.'</td>';
		echo '<td class="c_date">'.$display_date.'</td>';

	echo '</tr>';
	
}
echo '</tbody>';
echo '</table>';
?>