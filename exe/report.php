<?php

date_default_timezone_set("Asia/Kolkata");

$cust_id = $_GET['cust_id'];
$date = date("Y-m-d");

$date1 = $_GET['date1']	;
$date2 = $_GET['date2']	;
$type = $_GET['type'];

$invoice_no = $_GET['invoice_no'];



if ($type == 'new') {

	$date_invoice = $_GET['date_invoice'];

	// $html_file_url = "http://fuelmaster.greenboxinnovations.in/bill.php?cust_id=".$cust_id."&date1=".$date1."&date2=".$date2."&type=".$type."&date_invoice=".$date_invoice; // html file 

	$html_file_url = "http://192.168.1.110/pump_master/bill.php?cust_id=".$cust_id."&date1=".$date1."&date2=".$date2."&type=".$type."&date_invoice=".$date_invoice;

	$pdf_file_url = "../reports/Invoice-".$invoice_no.".pdf"; // pdf file 

	if (!file_exists($pdf_file_url)) {   
		                     
	

		//linux
		// $cmd = "../wkhtmltopdf/bin/wkhtmltopdf --page-size A4 --enable-smart-shrinking \"".$html_file_url."\" \"".$pdf_file_url."\" 2>&1";// command

		//windows
		$cmd = "C:/wkhtmltopdf/bin/wkhtmltopdf.exe --page-size A4 --enable-smart-shrinking \"".$html_file_url."\" \"".$pdf_file_url."\" 2>&1";// command


		echo exec($cmd); // execute command from php

		//header("Location: http://advections.com/reports/daily_report_".$date.".pdf");

		$pdf = file_get_contents($pdf_file_url);

		header('Content-Type: application/pdf');
		header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
		header('Pragma: public');
		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header('Content-Length: '.strlen($pdf));
		header('Content-Disposition: inline; filename="'.basename($pdf_file_url).'";');
		ob_clean(); 
		flush(); 
		echo $pdf;
	}
}else{


	$pdf_file_url = "../reports/Invoice-".$invoice_no.".pdf"; // pdf file 
	$pdf = file_get_contents($pdf_file_url);

	header('Content-Type: application/pdf');
	header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
	header('Pragma: public');
	header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	header('Content-Length: '.strlen($pdf));
	header('Content-Disposition: inline; filename="'.basename($pdf_file_url).'";');
	ob_clean(); 
	flush(); 
	echo $pdf;

}


?>