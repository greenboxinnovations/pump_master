<?php

date_default_timezone_set("Asia/Kolkata");
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';

$url_main = 'http://fuelmaster.greenboxinnovations.in/receive_videos.php';

function myErrorHandler( $errType, $errStr, $errFile, $errLine, $errContext ) {
	$displayErrors 	= ini_get( 'display_errors' );
	$logErrors 		= ini_get( 'log_errors' );
	$errorLog 		= ini_get( 'error_log' );

	// if( $displayErrors ) echo $errStr.PHP_EOL;

	if( $logErrors ) {
		$message = sprintf('[%s] - (%s, %s) - %s ', date('Y-m-d H:i:s'), $errFile, $errLine ,$errStr);
		file_put_contents( $errorLog, $message.PHP_EOL, FILE_APPEND );
	}
}

ini_set('log_errors', 1);
ini_set('error_log', '/opt/lampp/htdocs/pump_master/send_videos.log');
error_reporting(E_ALL);
set_error_handler('myErrorHandler');

$trans_string = "";
$file_name    = "";
$index = 0;
$date = '';

$trans_array = array();
$sql = "SELECT `trans_string` FROM `transactions` WHERE `video` = 'N' ORDER BY `trans_id` DESC LIMIT 1;";
$exe = mysqli_query($conn,$sql);
while($row1 = mysqli_fetch_assoc($exe)){
	$trans_string = $row1['trans_string'];	
}

if ($trans_string != "") {
	// get distinct dates from db
	$sql_dir = "SELECT distinct(date(`date`)) as 'dir_date' FROM `transactions` WHERE `video` = 'N';";
	$exe_dir = mysqli_query($conn,$sql_dir);

	if(mysqli_num_rows($exe_dir) > 0){
		// foreach ($dirs as $key => $path) {
		while ($row = mysqli_fetch_assoc($exe_dir)) {

			try{

				$path =  "/opt/lampp/htdocs/pump_master/videos/".$row['dir_date'];
				// $path =  "/opt/lampp/htdocs/pump_master/videos/2018-11-17";
				
				$files = array_values(array_diff(scandir($path), array('.', '..')));

				foreach ($files as $i => $file) {

					if(($trans_string == basename($file,'.mp4'))&&($index == 0)) {				
						$file_name = $path.'/'.$file;
						$date = $row['dir_date'];
						// $date = "2018-11-17";
						$index++;
					}
				}
			} catch (Exception $e) {
				trigger_error('Test');
			}
		}
	}

	if($file_name != ""){

		$sql = "UPDATE `transactions` SET `video` = 'U' WHERE `trans_string` = '".$trans_string."' ;";
		$exe = mysqli_query($conn,$sql);
		
		$cmd = 'curl -F "date='.$date.'" -F "file=@'.$file_name.'" http://fuelmaster.greenboxinnovations.in/receive_videos.php -m 1200';

		try {

			$output = json_decode(shell_exec($cmd),true);

			if($output['success']){

				//echo "working";

				$t_string = $output['trans_string'];

				$sql = "UPDATE `transactions` SET `video` = 'Y' WHERE `trans_string` = '".$t_string."' ;";
				$exe = mysqli_query($conn,$sql);

			}else{

				//echo "error";

				$sql = "UPDATE `transactions` SET `video` = 'N' WHERE `trans_string` = '".$trans_string."' ;";
				$exe = mysqli_query($conn,$sql);

			}
		} catch (Exception $e) {

			//print_r($e);

			trigger_error($e);

			$sql = "UPDATE `transactions` SET `video` = 'N' WHERE `trans_string` = '".$trans_string."' ;";
			$exe = mysqli_query($conn,$sql);
		}
	}
}
?>