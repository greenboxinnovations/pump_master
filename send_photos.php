<?php
date_default_timezone_set("Asia/Kolkata");

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
ini_set('error_log', 'send_photos.log');
error_reporting(E_ALL);

set_error_handler('myErrorHandler');
// trigger_error('Test');




// multiple images
$dirs = array_filter(glob('/opt/lampp/htdocs/pump_master/uploads/*'), 'is_dir');

$url_main = 'http://fuelmaster.greenboxinnovations.in';

$index = 0;
$postData = array();

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
		}

	} catch (Exception $e) {
		trigger_error('Test');
	}
}


if(sizeof($postData) == 0){
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
		trigger_error('Test'.$response);
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