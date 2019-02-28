<?php

date_default_timezone_set("Asia/Kolkata");
require_once $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';
$local_install_dir = Globals::LOCAL_INSTALL_DIR;
$url_main = Globals::URL_SYNC_CHECK.'/receive_videos.php';

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
ini_set('error_log', $local_install_dir.'send_videos.log');
error_reporting(E_ALL);
set_error_handler('myErrorHandler');

$trans_string = "";
$file_name    = "";
$date = date('Y-m-d H:i:s');


$trans_array = array();
$sql = "SELECT `trans_string` , date(`date`) as 'dir_date', `date` as t FROM `transactions` WHERE `video` = 'N' ORDER BY RAND() LIMIT 1;";
$exe = mysqli_query($conn,$sql);
while($row1 = mysqli_fetch_assoc($exe)){
	$trans_string = $row1['trans_string'];	
	$dir_date = $row1['dir_date'];
	$time_diff = strtotime($date) - strtotime($row1['t']);
}

if (($trans_string != "")&&($time_diff > 30)) {

	try{

		$path =  $local_install_dir.'/'.$dir_date;
		$video = $path.'/'.$trans_string.'.mp4';
		

		if (file_exists($video)) {
			$file_name = $video;
			//trigger_error("exists".$video);
		}else{
			//trigger_error("dosent exists".$time_diff.$trans_string);
			$sql = "UPDATE `transactions` SET `video` = 'A' WHERE `trans_string` = '".$trans_string."' ;";
			$exe = mysqli_query($conn,$sql);
		}

	} catch (Exception $e) {
		trigger_error('Test');
	}
	
	if($file_name != ""){

		$sql = "UPDATE `transactions` SET `video` = 'U' WHERE `trans_string` = '".$trans_string."' ;";
		$exe = mysqli_query($conn,$sql);
		
		// $cmd = 'curl -F "date='.$date.'" -F "file=@'.$file_name.'" http://fuelmaster.greenboxinnovations.in/receive_videos.php -m 1200';
		$cmd = 'curl -F "date='.$dir_date.'" -F "file=@'.$file_name.'" '.$url_main.' -m 1200';

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