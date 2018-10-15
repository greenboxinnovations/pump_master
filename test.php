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
?>
