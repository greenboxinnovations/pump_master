<?php
require 'query/conn.php';

	$sql = "SELECT `trans_string` FROM `transactions` WHERE `trans_string` IS NOT NULL ;";
	$exe = mysqli_query($conn, $sql);
	while($row = mysqli_fetch_assoc($exe)){
	   // echo $row['trans_string'];
	   // echo'<br/>';

		$sql1 = "INSERT INTO `trans_string`(`trans_string`) VALUES ('".$row['trans_string']."') ;";
		$exe1 = mysqli_query($conn, $sql1);
	
	}


// USEFUL QUERIES
// SELECT `cust_id`,count(*) as total FROM `transactions` WHERE `trans_string` != "" GROUP BY `cust_id`


// SELECT * FROM `transactions` WHERE `trans_string` != "" GROUP BY `cust_id`

// SELECT a.cust_id,b.cust_company FROM `transactions` a 
// JOIN `customers` b
// ON a.cust_id = b.cust_id
// WHERE `trans_string` != "" GROUP BY `cust_id`


// SELECT b.cust_company, count(a.cust_id) as total, a.cust_id FROM `transactions` a
// JOIN `customers` b
// ON a.cust_id = b.cust_id
// WHERE `trans_string` != "" GROUP BY a.cust_id
?>
