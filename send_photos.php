<?php
date_default_timezone_set("Asia/Kolkata");
// require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';
require __DIR__.'/query/conn.php';

$local_install_dir = Globals::LOCAL_INSTALL_DIR;

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
ini_set('error_log', $local_install_dir.'send_photos.log');
error_reporting(E_ALL);

set_error_handler('myErrorHandler');
// trigger_error('Test');


// multiple images
$dirs = array_filter(glob($local_install_dir.'uploads/*'), 'is_dir');

// $url_main = 'http://fuelmaster.greenboxinnovations.in'; 
$url_main = Globals::URL_SYNC_CHECK;

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

					if((file_exists($file1)) && (file_exists($file2))){

						$send = true;
					}				    
				}
			}
		}
	} catch (Exception $e) {
		trigger_error('Error');
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
	curl_setopt($ch, CURLOPT_HTTPHEADER,array("Expect:100-continue"));

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
				unlink($local_install_dir.$path);
				//trigger_error($local_install_dir.$path);
			}	

			foreach ($dirs as $key => $dir) {

				if ($dir != $local_install_dir."uploads/".date("Y-m-d")) {
					rmdir($dir);		
				}
			}	
		} catch (Exception $e) {
			trigger_error('Test');
		}		
	}	
}

?>