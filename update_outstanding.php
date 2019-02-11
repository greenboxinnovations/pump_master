<?php
date_default_timezone_set("Asia/Kolkata");
require_once $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';

if (isset($_GET['ok'])) {
	$sql = "SELECT `cust_id` FROM `customers` WHERE `cust_post_paid` = 'Y';";	
	$exe = mysqli_query($conn,$sql);
	while ( $res = mysqli_fetch_assoc($exe)) {
		$cust_id = $res['cust_id'];

		$sql2 = "UPDATE `customers` SET `cust_outstanding` = (SELECT SUM(`amount`) FROM `transactions` WHERE `cust_id` = ".$cust_id." AND `billed` = 'N') WHERE `cust_id` = ".$cust_id.";";

		$exe2 = mysqli_query($conn,$sql2);

		echo'Customer Updated';
		echo'<br/>';
	}
}

?>