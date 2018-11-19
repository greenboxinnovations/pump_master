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
ini_set('error_log', 'receive_videos.log');
error_reporting(E_ALL);

set_error_handler('myErrorHandler');


if (isset($_POST) ){

	$names  = array();
	$output = array();
	$output['success'] = false;

	try {

		for ($i=0; $i < sizeof($_FILES['file']['name']); $i++) { 

			$dir = $_POST['path'][$i];

			$dir = str_replace('/opt/lampp/htdocs/pump_master/', '', $dir);

			if (!file_exists($dir)) {
				mkdir($dir);
			}

			move_uploaded_file($_FILES['file']['tmp_name'][$i], $dir.'/'.$_FILES['file']['name'][$i]);	

			array_push($names, $_FILES['file']['name'][$i]);
			$output['success'] = true;
		}
		
	} catch (Exception $e) {
		array_push($names, $e);
		$output['success'] = false;
		trigger_error('Test');
	}

	$output['names']= $names;

	echo json_encode($output);

}

?>