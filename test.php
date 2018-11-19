<?php
date_default_timezone_set("Asia/Kolkata");
require 'query/conn.php';
// function myErrorHandler( $errType, $errStr, $errFile, $errLine, $errContext ) {
// 	$displayErrors 	= ini_get( 'display_errors' );
// 	$logErrors 		= ini_get( 'log_errors' );
// 	$errorLog 		= ini_get( 'error_log' );

// 	// if( $displayErrors ) echo $errStr.PHP_EOL;
// 	if( $logErrors ) {
// 		$message = sprintf('[%s] - (%s, %s) - %s ', date('Y-m-d H:i:s'), $errFile, $errLine ,$errStr);
// 		file_put_contents( $errorLog, $message.PHP_EOL, FILE_APPEND );
// 	}
// }

// ini_set('log_errors', 1);
// ini_set('error_log', '/opt/lampp/htdocs/pump_master/send_photos.log');
// error_reporting(E_ALL);

// set_error_handler('myErrorHandler');
// trigger_error('Test');


function formatBytes($size, $precision = 2)
{
    $base = log($size, 1024);    
    return round(pow(1024, $base - floor($base)), $precision);
}


$url_main = 'http://fuelmaster.greenboxinnovations.in';

$send = false;
$index = 0;
$postData = array();
$files_name = array();
$total_file_size_mb = 0;

$trans_array = array();


// get distinct dates from db
$sql_dir = "SELECT distinct(date(`date`)) as 'dir_date' FROM `transactions` WHERE `video` = 'N';";
$exe_dir = mysqli_query($conn,$sql_dir);
if(mysqli_num_rows($exe_dir) > 0){


	// foreach ($dirs as $key => $path) {
	while ($row = mysqli_fetch_assoc($exe_dir)) {

		$path =  "/opt/lampp/htdocs/pump_master/videos/".$row['dir_date'];
	

		try {
			// list all files in directory
			$files = array_values(array_diff(scandir($path), array('.', '..')));			

			// Create array of files to post
			foreach ($files as $i => $file) {

				$trans_string =  basename($file, ".mp4");
				array_push($trans_array, $trans_string);

				// check file size first
				$total_file_size = formatBytes(filesize(realpath($path.'/'.$file)), 2) ;
				$total_file_size_mb += $total_file_size;

				// if($index < 2){
				if($total_file_size_mb < 30){
					$postData['file[' . $index . ']'] = curl_file_create(
						realpath($path.'/'.$file),
						mime_content_type($path.'/'.$file),
						basename($path.'/'.$file)
					);

					$postData['path[' . $index . ']'] = $path;
					$index++;

					$files_name[$i] = $file;
				}
				else{
					break;
				}			
			}
		} catch (Exception $e) {
			// trigger_error('Test');
			print_r($e);
		}
	}

	// echo $total_file_size_mb;
	// echo '<pre>';
	// print_r($postData);
	// echo '</pre>';

	// print_r($trans_array);


	if(sizeof($postData) == 0){
		echo 'No Photos Found';
	}
	else{

		// var_dump($postData);
		$target_url = $url_main.'/receive_videos.php';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$target_url);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		// curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
		// curl_setopt($ch, CURLOPT_TIMEOUT, 9000);
		// curl_setopt($ch, CURLOPT_BUFFERSIZE, 256); 	
		// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data',
		// 'Expect:' ));
		// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


		$result = curl_exec ($ch);
		curl_close ($ch);
		echo '<pre>';
			print_r($result);
		echo '</pre>';

		// $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	

		
		// if($response != 200){
		// 	// trigger_error('Response'.$response);
		// 	print_r($response);
		// 	// revert_transactions($trans_array);
		// }
		// else{
		// 	try {
		// 		// response is successfull
		// 		// $data = json_decode($result);
		// 		echo '<pre>';
		// 		print_r($result);
		// 		echo '</pre>';
		// 		echo '<div>videos Sent</div>';

		// 		// foreach ($data as $key => $path) {
		// 		// 	unlink("/opt/lampp/htdocs/pump_master/".$path);
		// 		// }	

		// 		// foreach ($dirs as $key => $dir) {

		// 		// 	if ($dir != "/opt/lampp/htdocs/pump_master/uploads/".date("Y-m-d")) {
		// 		// 		rmdir($dir);		
		// 		// 	}
		// 		// }	
		// 	} catch (Exception $e) {
		// 		// trigger_error('Test');
		// 		print_r($e);
		// 	}		
		// }	
	}
}

?>
