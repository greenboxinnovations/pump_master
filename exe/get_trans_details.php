<?php
require '../query/conn.php';

if(isset($_GET['trans_id'])){
	$trans_id = $_GET['trans_id'];
	$output = array();
	$sql0 = "SELECT a.*,b.car_no_plate,d.cust_company,d.cust_f_name,d.cust_l_name,c.id as max FROM `transactions` a JOIN  `cars` b ON a.car_id=b.car_id JOIN `sync` c JOIN `customers` d ON b.car_cust_id = d.cust_id  WHERE a.trans_id =  '".$trans_id."' AND c.table_name = 'transactions';";
	$result0 = mysqli_query($conn,$sql0);

	while($row = mysqli_fetch_assoc($result0)){
		array_push($output, $row);		
	}	
	echo json_encode($output);
}

?>

