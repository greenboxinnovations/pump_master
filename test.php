<?php

require_once __DIR__.'/query/conn.php';
// echo date('Y-m-d');

// $cur_year = 0;
$cur_fin_year = 0;

function findFinancialYear($date) {	

	$time 	= strtotime($date);
	$month 	= date("n", $time);
	$year 	= date("Y", $time);

	if($month > 3){
		$fin_year = $year;
	}
	else{
		$fin_year = $year - 1;	
	}

	// echo 'FY'.$cur_fin_year.'-'.($cur_fin_year+1);
	// echo '<br>';	
	return $fin_year;
}

// findFinancialYear(date('Y-m-d'));
// findFinancialYear('2019-5-1'); 
// findFinancialYear('2019-4-1');
// findFinancialYear('2019-3-1');
// findFinancialYear('2019-2-1');
// findFinancialYear('2016-1-1');


// $sql = "SELECT `from` FROM `invoices` WHERE `cust_id` ='15' AND `status` = 'Y' ORDER BY `invoice_no` DESC";
// $exe = mysqli_query($conn, $sql);
// $count = mysqli_num_rows($exe);
// if ($count > 0) {
// 	while($row = mysqli_fetch_assoc($exe)){
// 		echo $row['from'];
// 		echo ' ';
// 		findFinancialYear($row['from']);
// 		// echo '<br>';
// 	}	
// }

$sql = "SELECT * FROM `invoices` WHERE `cust_id` ='15' AND `status` = 'Y' ORDER BY `invoice_no` DESC";
$exe = mysqli_query($conn, $sql);
$count = mysqli_num_rows($exe);
if ($count > 0) {

	echo '<table id="header-fixed"></table>';
	echo '<table id="table-1">';
	echo '<thead>';
	echo '<tr style="border:1px solid rgb(207,216,220);">';
	echo '<th>#</th>';
	echo '<th>Invoice No</th>';
	echo '<th>From</th>';
	echo '<th>To</th>';

	echo '<th>Generated</th>';
	echo '<th>Amount</th>';

	echo '<th>Paid</th>';
	echo '<th>Balance</th>';
	echo '<th></th>';
	// if ($_SESSION['role'] == "admin") {
	// 	echo '<th></th>';
	// }
	echo '</tr>';
	echo '</thead>';


	echo '<tbody>';
	$i=1;
	$payable = 0;
	while($row = mysqli_fetch_assoc($exe)){


		$sql1 = "SELECT SUM(`amount_paid`) as paid FROM `payments` WHERE `cust_id` ='15' AND `invoice_no` = '".$row["invoice_no"]."'";
		$exe1 = mysqli_query($conn, $sql1);
		$count1 = mysqli_num_rows($exe1);
		$paid = 0;

		if ($count1 >0) {
			$row1 = mysqli_fetch_assoc($exe1);
			$paid = $row1['paid'];
		}

		$balance = $row['amount'] - $paid;
		$payable = $payable + $balance;


		$inv_f_year = findFinancialYear($row['from']);
		if($inv_f_year != $cur_fin_year){
			echo '<tr><td>Jiggy</td><tr>';
			$cur_fin_year = $inv_f_year;
		}


		echo '<tr class="highlight invoice" custid=15>';
		echo '<td >'.$i.'</td>';
		echo '<td class="invoice_no">'.$row["invoice_no"].'</td>';
		echo '<td class="from">'.$row["from"].'</td>';
				// echo '<td>'.date("d-m-Y", strtotime($row['from'])).'</td>';
		echo '<td class="to">'.$row["to"].'</td>';
				// echo '<td>'.date("d-m-Y", strtotime($row['to'])).'</td>';

		echo '<td>'.date("M-d, g:i a", strtotime($row['date'])).'</td>';
		echo '<td >'.$row["amount"].'</td>';

		echo '<td style="text-align:right">'.$paid.'</td>';

		echo '<td style="text-align:right">'.$balance.'</td>';

		echo '<td class="new_pay" invoiceno="'.$row["invoice_no"].'" invoiceamount="'.$row["amount"].'"></td>';	
		// if (($_SESSION['role'] == "admin")&&($i == 1)) {
		// 	echo '<td class="delete_invoice" id="'.$row['in_id'].'"></td>';	
		// }else if(($_SESSION['role'] == "admin")&&($i != 1)){
		// 	echo '<td></td>';
		// }
		echo '<td></td>';
		echo '</tr>';
		$i++;
	}
	echo'<tr style="text-align:right"><td colspan=7 > Total Payable</td ><td>'.$payable.'</td><td></td>';
	// if(($_SESSION['role'] == "admin")&&($i != 1)){
	// 	echo '<td></td>';
	// }
	echo'</tr>';

	echo '</tbody>';
	echo '</table>';
}

?>