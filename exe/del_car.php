<?php
if(!isset($_SESSION))
{
	session_start();
}
require '../query/conn.php';
	
	if (isset($_POST['car_id'])) {
		$car_id = $_POST['car_id'];
		$table_name = 'cars';

		$sql5 = "UPDATE `cars` SET `status` = 'inactive'  WHERE `car_id` = '".$car_id."' ;";
		$exe5 = mysqli_query($conn, $sql5);

		date_default_timezone_set("Asia/Kolkata");
		$date = date("Y-m-d H:i:s");		
		$unix = strtotime($date); 

		$upload_dir =  realpath(__DIR__ . '/../mysql_uploads');
		$filename = $upload_dir ."/".$table_name.'.sql';
		$db_name = "pump_master_test";
		
		exec("/usr/bin/mysqldump -u\"pump_master_user\" --password=\"pump_master_user123!@#\" \"".$db_name."\" \"".$table_name."\" > ".$filename);

		$sql3 = "UPDATE `sync` SET `last_updated`= '".$unix."' WHERE `table_name` = 'cars';";
		$exe3 = mysqli_query($conn ,$sql3);


		echo'success';
	}
	
?>