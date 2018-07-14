<?php
session_start();
	
	require_once(dirname(__FILE__) . "/Escpos.php");
	require '../query/conn.php';
$user_id = $_SESSION['user_id'];

if (isset($_GET['bill_no'])) {
	$bill_no = $_GET['bill_no'];
	$field='bill_no';
	$val = $bill_no;
	$sr_no = $_GET['sr_no'];
	$type = 'CANCEL KOT';
}

	
		date_default_timezone_set("Asia/Kolkata");
		$date = date('l jS \of F Y h:i:s A');
		$date1 = date('Y-m-d');
		$time = date("j/M/y, g:i a");
	
			
		$bill = "SELECT * FROM `temporary` WHERE `".$field."` = '".$val."' AND `date` = '".$date1."' AND `sr_no` = '".$sr_no."';";
		$result = mysqli_query($conn,$bill);
		

	
		$items = array();
			$i = 1;
			$total = 0;
			while ($row = mysqli_fetch_assoc($result)) {
				$items[$i] = new item($row['sr_no'],$row['name'],'',$row['quantity'],'');
				
				$tno = $row['table_no'];
				$bill_no = $row['bill_no'];

				// $mobile = $row['mobile'];
				$i++;
			}
	
		$user_q = "SELECT `name` FROM `users` WHERE `user_id` = '".$user_id."' ;";
		$user_re = mysqli_query($conn,$user_q);
		$row_re = mysqli_fetch_assoc($user_re);
		$user = $row_re['name'];
	
	
	 
		print_r($items);
		/* Start the printer */
	
		$connector = new NetworkPrintConnector("192.168.0.102", 9100);
		$printer = new Escpos($connector);
	
		/* Print top */
		$printer -> setJustification(Escpos::JUSTIFY_CENTER);
		$printer -> selectPrintMode(Escpos::MODE_DOUBLE_WIDTH);
		$printer -> text("KOT -> ".$type."\n");
		$printer -> text($time);
		$printer -> selectPrintMode();
		$printer -> feed(2);
	

		// if(($_SESSION['grid'] == 'takeaway')){

		// 	$name= "SELECT `name`,`address` FROM `customer_records` WHERE `mobile` = '".$mobile."' ;";  

		// 	$result_q = mysqli_query($conn,$name);
		// 	$row_n = mysqli_fetch_assoc($result_q);

		// 	$tno = $row_n['name'];
		// 	$add = $row_n['address'];


		// 	//header
		// 	$printer -> setJustification(Escpos::JUSTIFY_LEFT);
		// 	$printer -> text("BILL No : ".$bill_no);
		// 	$printer -> text("                 Waiter : ".$user."\n");
		// 	$printer -> text("Name : ".$tno."\n");
		// 	$printer -> text("Address : ".$add."\n");
			
		// 	$printer -> text("------------------------------------------------\n");
		// }else{
			//header
			$printer -> setJustification(Escpos::JUSTIFY_LEFT);
			$printer -> text("BILL No : ".$bill_no);
			$printer -> text("  Table : ".$tno);
			$printer -> text(" Cashier : ".$user."\n");
			$printer -> text("------------------------------------------------\n");
		// }
	
		//header
		$printer -> setJustification(Escpos::JUSTIFY_LEFT);
		$printer -> text("Sr. ");
		$printer -> text("Item Name          ");
		$printer -> text("              ");
		$printer -> text("Qty  ");
		$printer -> text("      \n");
		$printer -> text("------------------------------------------------\n");
		$printer -> feed();
	
	
		/* Items */
		$printer -> setJustification(Escpos::JUSTIFY_LEFT);
		foreach($items as $item) {
			$printer -> text($item);
		}
		$printer -> text("------------------------------------------------\n");
		$printer -> setEmphasis(false);
		$printer -> selectPrintMode();
	
		/* Footer */
		$printer -> feed();
		$printer -> setJustification(Escpos::JUSTIFY_CENTER);
		$printer -> selectPrintMode(Escpos::MODE_DOUBLE_WIDTH);
		$printer -> feed();
		
	
		/* Cut the receipt and open the cash drawer */
		$printer -> cut();
		$printer -> pulse();
	
		$printer -> close();
	
	
		/* A wrapper to do organise item names & prices into columns */
		class item {
			private $sr_no;
			private $name;
			private $price;
			private $quantity;
			private $amount;
			private $dollarSign;
	
			public function __construct($sr_no = '', $name = '',$price = '',$quantity = '',$amount = '') {
				$this -> sr_no = $sr_no;
				$this -> name = $name;
				$this -> price = $price;
				$this -> quantity = $quantity;
				$this -> amount = $amount;
	
			}
			
			public function __toString() {
				$one = str_pad($this -> sr_no, 4);
				$two = str_pad($this -> name, 28);
				$three = str_pad($this -> price, 6);
				$four = str_pad($this -> quantity,5);
				$five = str_pad($this -> amount, 0);
				
				return "$one$two$three$four$five\n";
			}
		}

?>
