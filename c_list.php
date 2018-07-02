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

	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto+Slab" rel="stylesheet">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){

			$('body').delegate('.row', 'click', function() {
				  
				var trans_string = $(this).attr('t');
				window.location.href = "http://fuelmaster.greenboxinnovations.in/c_msg.php?t="+trans_string+"&btn=no";
			
			});

		});
	</script>

	<style type="text/css">
		*{padding: 0;}
		#cust_details{font-size: 20px;font-family: 'Open Sans',sans-serif;padding: 10px;}
		#cust_display_name{font-family: 'Roboto Slab', serif;font-size: 30px;}
		img {
		  height: auto;
		  width: 100%;
		}

		.title{text-align: left;}
		.val{
			 
		}
		.inline{
			display: inline-table;padding: 10px;
		}
		.amount{
			width:50px;
		}
		.fuel{
			width: 40px;
		}
		.date{
			width:85px;
		}
		.sr{
			width: 40px;
		}
	</style>

</head>
<body>
<?php

require 'query/conn.php';

if(isset($_GET['cust_id'])){
	$cust_id = $_GET['cust_id'];
	
	// prepared statement
	// $sql = "SELECT * FROM `transactions` WHERE `trans_string` = ?";
	$sql = "SELECT a.*, b.cust_company,b.cust_f_name,b.cust_l_name
			FROM `transactions` a
			JOIN `customers` b
			ON b.cust_id = a.cust_id
			WHERE a.cust_id = 28 AND a.billed = 'N' ;";
	

	$exe = mysqli_query($conn, $sql);
	if(mysqli_num_rows($exe) > 0){

		$i=0;
		$total = 0;

		while($row = mysqli_fetch_assoc($exe)) {

			//--------------------------------//
			// customer details
			$display_name = $row['cust_company'];
			if($display_name == ""){
				$display_name = $row['cust_f_name']." ".$row['cust_l_name'];
			}


			if ($i == 0) {
				$display_name 	= ucwords($display_name);
				
				echo '<div id="cust_display_name">'.$display_name.'</div>';
				echo'<br/>';

				echo'<div class="container" >';
					echo '<div class="title inline sr">Sr No</div>';  
					echo '<div class="title inline date">Date</div>';  
					echo '<div class="val inline fuel">Fuel</div>';
					echo '<div class="val inline amount">Amount</div>';
				echo '</div>';
			}
			$i++;
	
			echo'<div class="container row" t="'.$row['trans_string'].'">';
				echo '<div class="title inline sr">'.$i.'</div>';  
				echo '<div class="title inline date">'.date('d-m-Y',strtotime($row['date'])).'</div>';  
				echo '<div class="val inline fuel" >'.$row['fuel'].'</div>';
				echo '<div class="val inline amount">'.$row['amount'].'</div>';

			echo '</div>';

			$total += $row['amount']; 
		}	

		echo'<div class="container" >';
			echo '<div class="title inline sr"></div>';  
			echo '<div class="title inline date">Pending</div>';  
			echo '<div class="val inline fuel">Total</div>';
			echo '<div class="val inline amount">'.$total.'</div>';
		echo '</div>';
	}
}

?>
</body>
</html>