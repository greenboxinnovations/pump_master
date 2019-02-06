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
ini_set('max_execution_time', 0);
set_error_handler('myErrorHandler');


if (isset($_POST) ){

	file_put_contents('v_log.txt',print_r($_FILES,true));

	$output = array();
	$output['success'] = false;
	$trans_string = "";

	try {		

		$date = $_POST['date'];
		$dir  = 'videos/'.$date;

		if (!file_exists($dir)) {
			mkdir($dir);
		}

		if(move_uploaded_file($_FILES['file']['tmp_name'], $dir.'/'.$_FILES['file']['name'])){
			$output['success'] = true;
			$trans_string =  basename($_FILES['file']['name'], ".mp4");
		}else{
			trigger_error('Move upload failed');
		}			
		
	} catch (Exception $e) {

		$output['success'] = false;
		trigger_error('Test');
	}

	$output['trans_string']= $trans_string;

	echo json_encode($output,JSON_NUMERIC_CHECK);

}

?>