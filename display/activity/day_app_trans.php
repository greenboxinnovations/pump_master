<?php
require '../../query/conn.php';
date_default_timezone_set("Asia/Kolkata");

if(isset($_GET['date'])){

	$date = $_GET['date'];
}
else{
	$date = date('Y-m-d');
}


$sql = "SELECT b.cust_company,b.cust_f_name,b.cust_l_name, count(a.cust_id) as total, a.cust_id 	FROM `transactions` a
		JOIN `customers` b
		ON a.cust_id = b.cust_id
		WHERE `trans_string` != '' AND date(a.date) = '".$date." 'GROUP BY a.cust_id";
$exe = mysqli_query($conn, $sql);

if(mysqli_num_rows($exe) > 0){
	
	echo '<table>';

	echo '<tr>';
		echo '<th style="text-align:left;">Customer</th>';
		echo '<th class="right_num">App Transactions</th>';
	echo '</tr>';
	while($row = mysqli_fetch_assoc($exe)){
		$company 	= $row['cust_company'];
		$f_name 	= $row['cust_f_name'];
		$l_name 	= $row['cust_l_name'];
		$total 		= $row['total'];
		$cust_id 	= $row['cust_id'];

		if($company == ""){
			$company = $cust_f_name." ".$cust_l_name;
		}

		echo '<tr>';
			echo '<td>'.ucwords($company).'</td>';
			echo '<td class="right_num">'.$total.'</td>';
		echo '</tr>';
	}
	echo '</table>';	
}
else{
	echo '<div>No App Transactions Found!</div>';
}


?>