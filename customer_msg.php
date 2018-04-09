<!DOCTYPE html>
<html>
<head>
	<title>GreenBox Innovations</title>

	<!-- favicons -->
	<link rel="apple-touch-icon" sizes="57x57" href="css/favi/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="css/favi/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="css/favi/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="css/favi/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="css/favi/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="css/favi/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="css/favi/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="css/favi/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="css/favi/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="css/favi/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="css/favi/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="css/favi/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="css/favi/favicon-16x16.png">
	<link rel="manifest" href="css/favi/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="css/favi/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">


	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
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
			where a.trans_string = '".$trans_string."'";
	

	$exe = mysqli_query($conn, $sql);
	if(mysqli_num_rows($exe) > 0){

		while($row = mysqli_fetch_assoc($exe)) {

			// print_r($row);

			//--------------------------------//

			// customer company or cust name
			// date - time
			// car details			
			// fuel-type
			// amount
			// litres
			// rate

			// 0 photo
			// 0 top
			// amount photo
			// amount top


			//--------------------------------//
			// customer details
			$display_name = $row['cust_company'];
			if($display_name == ""){
				$display_name = $row['cust_f_name']." ".$row['cust_l_name'];
			}


			echo $display_name 	= ucwords($display_name);
			echo '<br>';
			echo $date_time 	= $row['date'];
			echo '<br>';
			echo $car 			= $row['car_no_plate'];
			echo '<br>';
			echo $fuel_type 	= $row['fuel'];
			echo '<br>';
			echo $amount 		= $row['amount'];
			echo '<br>';
			echo $liters 		= $row['liters'];
			echo '<br>';
			echo $rate 			= $row['rate'];



			echo '<br>';
			echo '<br>';
			echo '<br>';

			//--------------------------------//
			// transaction photos
			$upload_dir = 'uploads';
			$date_dir 	= date('Y-m-d', strtotime($row['date']));

			$check 			= ['_start.jpeg','_start_top.jpeg','_stop.jpeg','_stop_top.jpeg'];
			$description 	= ['Zero Photo','Zero Overhead Photo','Completion Photo','Completed Overhead Photo'];

			foreach ($check as $i => $extention) {

				$file_path = $upload_dir."/".$date_dir."/".$trans_string.$extention;

				if(file_exists($file_path)) {
					echo $description[$i];
					echo '<br>';
					echo '<img src="'.$file_path.'">';
					echo '<br>';
				}
			}
		}	
	}
}



?>
</body>
</html>