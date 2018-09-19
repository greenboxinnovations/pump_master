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
		<meta name="theme-color" content="#FFCB05">

		<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Roboto+Slab" rel="stylesheet">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){

				$('body').delegate('tr', 'click', function() {


					// window.open('exe/report.php?cust_id='+cust_id+'&date1='+from+'&date2='+to+'&type='+type+'&date_invoice='+date_invoice+'&invoice_no='+invoice_no, '_blank');				  

					if($('#table_holder_trans').is(':visible')){

						var trans_string = $(this).attr('t');
						window.location.href = "http://fuelmaster.greenboxinnovations.in/c_msg.php?t="+trans_string;						
					}
					else{
						var invoice_no = $(this).attr('t');

						window.open('exe/report.php?cust_id=old&date1=old&date2=old&type=old&date_invoice=t&invoice_no='+invoice_no, '_blank');	
						// window.open('exe/report.php?cust_id=old&date1=old&date2=old&type=old&date_invoice=t&invoice_no='+invoice_no);	
					}					
				});


				$('.pager_single').on('click', function(){
					if(!$(this).hasClass('pager_active')){

						if($(this).text() == "TRANSACTIONS"){
							$('#main_container').css("border-top-right-radius","7px");
							$('#main_container').css("border-top-left-radius","0px");
							$('#left_top').hide();
							$('#right_top').show();
							$('#table_holder_invoices').hide();
							$('#table_holder_trans').show();
						}
						else{
							$('#main_container').css("border-top-right-radius","0px");
							$('#main_container').css("border-top-left-radius","7px");
							$('#left_top').show();
							$('#right_top').hide();
							$('#table_holder_invoices').show();
							$('#table_holder_trans').hide();
						}

						$('.pager_single').removeClass('pager_active');
						$(this).addClass('pager_active');
					}
				})

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
			#padding_div{padding: 15px;}

			#cust_details{font-size: 20px;font-family: 'Open Sans',sans-serif;padding: 10px;}
			#cust_display_name{
				font-family: 'Roboto Slab', serif;font-size: 30px;
				/*background-color: green;*/
				padding-left: 20px;
				padding-top: 20px;
			}
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

			/*viewpager*/
			#view_pager{
				width: 100%;display: flex;height: 50px;
				font-size: 14px;
				font-weight: 700;line-height: 50px;color: rgb(157,157,171);			
			}
			.pager_single{flex: 1;text-align: center;

				border:1px solid transparent;
				border-bottom: none;
			}
			.pager_active{background-color: #fff;color:rgb(112,157,224);border-top-right-radius: 7px;border-top-left-radius: 7px;
				border:1px solid rgb(220,220,220);
				border-bottom: none;
			}

			#main_container{
				background-color: white;border-radius: 7px;border-top-left-radius: 0px;
				border: 1px solid rgb(220,220,220);
				border-top: none;
				position: relative;
			}
#left_top {
margin-left: 3px;
content: '';
position: absolute;
top: 0;
border-top: 1px solid rgb(220,220,220);
width: calc(50% - 3px);
display: none;
}
#right_top {
margin-right: 3px;
content: '';
position: absolute;
top: 0;
right: 0;
border-top: 1px solid rgb(220,220,220);
width: calc(50% - 3px);
}

			/*#table_holder{padding: 10px;}*/
			table{width: 100%;border-collapse: collapse;}
			td{
				/*background-color: green;*/
				padding-top: 5px;
				padding-bottom: 5px;
				color: rgba(0,0,0,0.8);
			}

			tr:nth-child(even):not(.header){background-color: rgb(248,248,248);}
			th{
				text-align: left;padding-top: 5px;
				padding-bottom: 5px;
				font-size: 12px;
			}

			#table_holder_invoices{display: none;}

			.right_text{text-align: right;}		
			.amount{
				width:50px;
				padding-right: 10px;
				/*background-color: green;*/
			}
			.fuel{
				width: 20px;
				padding-right: 5px;
				text-align: right;
				color: rgb(180,180,180);			
				/*background-color: green;*/
			}
			.date_col{
				width:60px;
				/*color: rgb(80,100,100);*/
			}
			.sr{
				padding-left: 10px;
				width: 25px;
				color: rgb(180,180,180);
				/*background-color: green;*/
			}

			.in_amount{padding-right: 10px;width:50px;}
			.issued{width: 50px;padding-right: 10px;color: rgb(180,180,180);}
			.from,.to{width: 50px;padding-right: 10px;}
		</style>

	</head>
	<body>
	<div id="top_header">
		<img src="css/cmsg_header.png">
	</div>
	<div id="top_header_bar"></div>

	<div id="padding_div">

	<div id="view_pager">
		<div class="pager_single pager_active">TRANSACTIONS</div>
		<div class="pager_single">INVOICES</div>	
	</div>

	<div id="main_container">
		<div id="left_top"></div>
		<div id="right_top"></div>
		<?php
		require 'query/conn.php';

		if(isset($_GET['cust_id'])){
			$cust_id = $_GET['cust_id'];
			
			// prepared statement
			// $sql = "SELECT * FROM `transactions` WHERE `trans_string` = ?";
			// $sql = "SELECT a.*, b.cust_company,b.cust_f_name,b.cust_l_name
			// 		FROM `transactions` a
			// 		JOIN `customers` b
			// 		ON b.cust_id = a.cust_id
			// 		WHERE a.cust_id = '".$cust_id."' AND a.billed = 'N' ;";
			
			$sql = "SELECT a.*, b.cust_company,b.cust_f_name,b.cust_l_name,c.car_no_plate
					FROM `transactions` a
					JOIN `customers` b
					ON b.cust_id = a.cust_id
					JOIN `cars` c
					ON a.car_id = c.car_id
					WHERE a.cust_id = '".$cust_id."' AND a.billed = 'N' ;";


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

						echo '<div id="table_holder_trans">';
						echo '<table>';
						echo'<tr class="header">';
							echo '<th class="sr">#</th>';
							echo '<th class="car">CAR</th>';
							echo '<th class="date_col">DATE</th>';
							echo '<th class="fuel"></th>';
							echo '<th class="right_text amount">AMOUNT</th>';
						echo '</tr>';
					}
					$i++;

					$fuel = ($row['fuel'] == 'petrol') ? "P" : "D";

			
					echo'<tr t="'.$row['trans_string'].'">';
						echo '<td class="sr">'.$i.'</td>';  
						echo '<td>'.strtoupper($row['car_no_plate']).'</td>';
						echo '<td class="date_col">'.date('M j',strtotime($row['date'])).'</td>';  
						
						// echo '<td>'.date('d-m-Y',strtotime($row['date'])).'</td>'; 
						echo '<td class="fuel">'.$fuel.'</td>';
						echo '<td class="right_text amount">'.$row['amount'].'</td>';

					echo '</tr>';

					$total += $row['amount']; 
				}	

				// echo'<tr>';
				// 	echo '<td></td>';  
				// 	echo '<td></td>';  
				// 	echo '<td>Total</td>';
				// 	echo '<td class="right_text">'.$total.'</td>';
				// echo '</tr>';
				echo '</table>';
				echo '</div>'; // id="table_holder_trans"
			}else{
				echo '<div><h2>No Transactions</h2></div>';
			}



			$sql = "SELECT * FROM `invoices` WHERE `cust_id` = '".$cust_id."' ORDER BY `in_id` DESC;";
			$exe = mysqli_query($conn, $sql);
			if(mysqli_num_rows($exe) > 0){
				$i=0;
				while($row = mysqli_fetch_assoc($exe)) {

					if ($i == 0) {
						$display_name 	= ucwords($display_name);
												
						echo '<div id="table_holder_invoices">';
						echo '<table>';
						echo'<tr class="header">';
							echo '<th class="sr">#</th>';
							echo '<th class="from">FROM</th>';
							echo '<th class="to">TO</th>';
							echo '<th class="issued">ISSUED</th>';
							echo '<th class="right_text in_amount">AMOUNT</th>';
						echo '</tr>';
					}
					$i++;					

			
					echo'<tr t="'.$row['invoice_no'].'">';
						echo '<td class="sr">'.$i.'</td>';  
						echo '<td>'.date('M j',strtotime($row['from'])).'</td>';
						echo '<td class="">'.date('M j',strtotime($row['to'])).'</td>';  
						
						// echo '<td>'.date('d-m-Y',strtotime($row['date'])).'</td>'; 
						echo '<td class="issued">'.date('M j',strtotime($row['date'])).'</td>';
						echo '<td class="right_text in_amount">'.$row['amount'].'</td>';

					echo '</tr>';
				}
				echo '</table>';
				echo '</div>'; // id="table_holder_invoices"
			}else{
				echo '<div><h2>No Invoices</h2></div>';
			}
			
		}
		?>
	</div>

	</div><!-- padding div -->
	</body>
	</html>