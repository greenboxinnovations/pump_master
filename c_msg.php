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

			$('body').delegate('#view_all', 'click', function(e) {
				e.stopPropagation(); 
				var cust_id = $(this).attr('custid');

				//As an HTTP redirect (back button will not work )
				// window.location.replace("http://www.google.com");
				window.location.replace("http://fuelmaster.greenboxinnovations.in/c_list.php?cust_id="+cust_id+"");
			});
		});
	</script>

	<style type="text/css">
		*{padding: 0;margin: 0;}
		body{font-family: 'Open Sans',sans-serif;background-color: rgb(240,240,240);}

		#top_header{
			padding-top: 10px;
			/*height: 70px;*/
			background-color: #FFCB05;
			/*background-color: #2E66B1;*/
			width: 100%;}
		#top_header_bar{background-color: #2E66B1;height: 30px;}

		#padding_div{			
			padding: 20px;
			}

		#cust_details{
			font-size: 20px;
			padding: 20px;
			background-color: white;
			border-radius: 5px;
			border: 1px solid rgb(220,220,220);
			margin-bottom: 30px;
		}
		#cust_display_name{
			font-family: 'Roboto Slab', serif;font-size: 30px;
			/*background-color: green;*/
			display: inline-block;
			vertical-align: top;
			width: 50%;
		}
		#button_div{
			text-align: right;
			display: inline-block;
			vertical-align: top;
			width: 50%;
			margin-top: 5px;
		}
		#button_div button{
			/*background-color: green;padding: 0px 10px 0px 10px;*/
			  position: relative;

/*display: block;*/
/*margin: 30px auto;*/
padding: 10px;

overflow: hidden;

border-width: 0;
outline: none;
border-radius: 2px;
box-shadow: 0 1px 4px rgba(0, 0, 0, .6);
font-weight: 600;
background-color: #2E66B1;
color: #ecf0f1;

transition: background-color .3s;
		}
		#clear_both{clear: both;}
		img {
			display: block;
			vertical-align: bottom;
		  height: auto;
		  width: 100%;
		  border-bottom-left-radius: 5px;
		  border-bottom-right-radius: 5px;
		  /*margin-left: auto;		  */
		  /*margin-right: auto;*/
		  /*margin: 20px;*/
		}

		.img_border{			
			border: 1px solid rgb(180,180,180);margin-bottom: 20px;
			border-radius: 5px;
		}
		.img_desc{
			/*background-color: rgb(200,200,200);*/
			background-color: #2E66B1;
			color: rgba(255,255,255,0.9);
			padding-left: 20px;
			padding-top: 5px;
			padding-bottom: 5px;
			font-weight: 600;
			border-top-left-radius: 5px;
			border-top-right-radius: 5px;
			/*margin-top: 25px;			*/
		}

		.title{text-align: left;}
		.val{
			 
		}
		.inline{
			display: inline-table;width: 50%;
		}
	</style>
</head>
<body>

<div id="top_header">
	<img src="css/cmsg_header.png">
</div>
<div id="top_header_bar"></div>


<div id="padding_div">

<?php

require 'query/conn.php';

if(isset($_GET['t'])){
	$trans_string = $_GET['t'];
	$btn = true;
	
	if (isset($_GET['btn'])) {
		$btn = false;
	}

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

			//--------------------------------//
			// customer details
			$display_name = $row['cust_company'];
			if($display_name == ""){
				$display_name = $row['cust_f_name']." ".$row['cust_l_name'];
			}



			echo '<div id="cust_details">';
				$display_name 	= ucwords($display_name);
				
				echo '<div id="cust_display_name">'.$display_name.'</div>';

				echo '<div id="button_div">';

				if ($btn) {
					echo '<button custid="'.$row['cust_id'].'" id="view_all" >VIEW ALL</button>';
				}
				echo '</div>';				
				echo '<div id="clear_both"></div>';

				echo '<div class="title inline">'.$row['car_no_plate'] .'</div>'; 
				// if ($btn) {
				// 	echo '<div class="val inline" id="view_all" custid="'.$row['cust_id'].'"><button>View All</button></div>';
				// }				
				echo '<br>';
				echo '<br>';

				echo'<div class="container" >';
					echo '<div class="title inline">Fuel</div>';  echo '<div class="val inline">'.ucwords($row['fuel']).'</div>';
				echo '</div>';

				echo'<div class="container" >';
					echo '<div class="title inline">Amount</div>';  echo '<div class="val inline">'.$row['amount'].'</div>';
				echo '</div>';

				echo'<div class="container" >';
					echo '<div class="title inline">Litres</div>';  echo '<div class="val inline">'.$row['liters'].'</div>';
				echo '</div>';

				echo'<div class="container" >';
					echo '<div class="title inline">Fuel Rate</div>';  echo '<div class="val inline" >'.$row['rate'].'</div>';
				echo '</div>';

			echo '</div>';
		

			//--------------------------------//
			// transaction photos
			$upload_dir = 'uploads';
			$date_dir 	= date('Y-m-d', strtotime($row['date']));

			$check 			= ['_start.jpeg','_start_top.jpeg','_stop.jpeg','_stop_top.jpeg'];
			$description 	= ['Zero Photo','Zero Overhead Photo','Completion Photo','Completed Overhead Photo'];

			foreach ($check as $i => $extention) {

				$file_path = $upload_dir."/".$date_dir."/".$trans_string.$extention;

				if(file_exists($file_path)) {
					echo '<div class="img_border">';
					echo '<div class="img_desc">'.strtoupper($description[$i]).'</div>';
					echo '<img src="'.$file_path.'">';
					echo '</div>';
					// echo '<br>';
				}else{
					// echo "photo error";
					// echo '<br>';
				}
			}

			echo'<br/>';
			echo $date_time 	= $row['date'];
			echo '<br/>';
		}	
	}
}



?>

</div><!-- padding div -->

</body>
</html>