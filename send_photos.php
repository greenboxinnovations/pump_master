<?php
date_default_timezone_set("Asia/Kolkata");
require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';

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
ini_set('error_log', '/opt/lampp/htdocs/pump_master/send_photos.log');
error_reporting(E_ALL);

set_error_handler('myErrorHandler');
// trigger_error('Test');


// multiple images
$dirs = array_filter(glob('/opt/lampp/htdocs/pump_master/uploads/*'), 'is_dir');

$url_main = 'http://fuelmaster.greenboxinnovations.in';

$send = false;
$index = 0;
$postData = array();

$files_name = array();

foreach ($dirs as $key => $path) {

	try {
		// list all files in directory
		$files = array_values(array_diff(scandir($path), array('.', '..')));

		// Create array of files to post
		foreach ($files as $i => $file) {

			$postData['file[' . $index . ']'] = curl_file_create(
					realpath($path.'/'.$file),
					mime_content_type($path.'/'.$file),
					basename($path.'/'.$file)
				);

				$postData['path[' . $index . ']'] = $path;
				$index++;

			$files_name[$i] = $file;

			$data = explode("_", $file);
			if ($data[1]=="stop.jpeg") {

				if (file_exists($path.'/'.$files_name[$i])){
					$file1 = $path.'/'.$data[0].'_start.jpeg';
					$file2 = $path.'/'.$data[0].'_stop.jpeg';

					$t1 = date("Y-m-d H:i:s",filemtime($file1));
				    $t2 = date("Y-m-d H:i:s",filemtime($file2));

				    $interval = (new DateTime($t1))->diff(new DateTime($t2));
				    $time_diff =  $interval->format('%H:%I:%S');
				    
				    // echo $time_diff;

				    $sql1 = "UPDATE `transactions` SET `trans_time` = '".$time_diff."'  WHERE `trans_string` = '".$data[0]."' ;";
					$exe1 = mysqli_query($conn, $sql1);


				    $send = true;
				}
			}
		}
	} catch (Exception $e) {
		trigger_error('Test');
	}
}


if((sizeof($postData) == 0)||(!$send)){
	echo 'No Photos Found';
}
else{
	$target_url = $url_main.'/receive_photos.php';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$target_url);
	curl_setopt($ch, CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	$result = curl_exec ($ch);

	$response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close ($ch);

	if($response != 200){
		trigger_error('Response'.$response);
	}
	else{
		try {
			// response is successfull
			$data = json_decode($result);
			echo '<div>Photos Sent</div>';

			foreach ($data as $key => $path) {
				unlink("/opt/lampp/htdocs/pump_master/".$path);
			}	

			foreach ($dirs as $key => $dir) {

				if ($dir != "/opt/lampp/htdocs/pump_master/uploads/".date("Y-m-d")) {
					rmdir($dir);		
				}
			}	
		} catch (Exception $e) {
			trigger_error('Test');
		}		
	}	
}

?>