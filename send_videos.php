<?php

date_default_timezone_set("Asia/Kolkata");
require 'query/conn.php';

ini_set('log_errors', 1);
ini_set('error_log', '/opt/lampp/htdocs/pump_master/send_videos.log');
error_reporting(E_ALL);
set_error_handler('myErrorHandler');

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

function formatBytes($size, $precision = 2)
{
    $base = log($size, 1024);    
    return round(pow(1024, $base - floor($base)), $precision);
}

$url_main = 'http://fuelmaster.greenboxinnovations.i';

$index = 0;
$postData = array();
$trans_array = array();

// $trans_array = array();

// get distinct dates from db
$sql_dir = "SELECT distinct(date(`date`)) as 'dir_date' FROM `transactions` WHERE `video` = 'N';";
$exe_dir = mysqli_query($conn,$sql_dir);

if(mysqli_num_rows($exe_dir) > 0){
	// foreach ($dirs as $key => $path) {
	while ($row = mysqli_fetch_assoc($exe_dir)) {

		try{

			$path =  "/opt/lampp/htdocs/pump_master/videos/".$row['dir_date'];
			
			$files = array_values(array_diff(scandir($path), array('.', '..')));

			foreach ($files as $i => $file) {

				$trans_string =  basename($file, ".mp4");
				array_push($trans_array, $trans_string);

				$postData['file[' . $index . ']'] = curl_file_create(
							realpath($path.'/'.$file),
							mime_content_type($path.'/'.$file),
							basename($path.'/'.$file)
						);

				$postData['path[' . $index . ']'] = $path;
				$index++;
			}

		} catch (Exception $e) {
			trigger_error('Test');
		}

	}
}

if(sizeof($postData) == 0){
	echo 'No Videos Found';
}
else{
	foreach ($trans_array as $trans_string) {

		$sql = "UPDATE `transactions` SET `video` = 'U' WHERE `trans_string` = '".$trans_string."' ;";
		$exe = mysqli_query($conn,$sql);
	}

	$target_url = $url_main.'/receive_videos.php';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$target_url);
	curl_setopt($ch, CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = json_decode(curl_exec ($ch),true);
	$response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close ($ch);

	if($response != 200){
		trigger_error('Response code'.$response);
		foreach ($trans_array as $trans_string) {

			$sql = "UPDATE `transactions` SET `video` = 'N' WHERE `trans_string` = '".$trans_string."' ;";
			$exe = mysqli_query($conn,$sql);
		}
	}else{

		if ($result['success']) {
			foreach ($result['names'] as $trans_string_name) {
				$trans_string =  basename($trans_string_name, ".mp4");

				$sql = "UPDATE `transactions` SET `video` = 'Y' WHERE `trans_string` = '".$trans_string."' ;";
				$exe = mysqli_query($conn,$sql);
			}
		}else{
			foreach ($trans_array as $trans_string) {

				$sql = "UPDATE `transactions` SET `video` = 'N' WHERE `trans_string` = '".$trans_string."' ;";
				$exe = mysqli_query($conn,$sql);
			}
		}

	}	
}

?>
