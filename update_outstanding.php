<?php
date_default_timezone_set("Asia/Kolkata");
require 'query/conn.php';


$sql = "SELECT `cust_id` FROM `customers` WHERE `cust_post_paid` = 'Y';";	
$exe = mysqli_query($conn,$sql);
while ( $res = mysqli_fetch_assoc($exe)) {
	$cust_id = $res['cust_id'];

	$sql2 = "UPDATE `customers` SET `cust_outstanding` = (SELECT SUM(`amount`) FROM `transactions` WHERE `cust_id` = ".$cust_id." AND `billed` = 'N') WHERE `cust_id` = ".$cust_id.";";

	$exe2 = mysqli_query($conn,$sql2);
}


?>