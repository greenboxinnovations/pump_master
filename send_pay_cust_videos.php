<?php

date_default_timezone_set("Asia/Kolkata");
// require $_SERVER["DOCUMENT_ROOT"].'/query/conn.php';
require __DIR__.'/query/conn.php';

// $local_install_dir = Globals::LOCAL_INSTALL_DIR;
// $url_main = Globals::URL_SYNC_CHECK.'/receive_videos.php';

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
// ini_set('error_log', $local_install_dir.'/logs/send_videos.log');
// error_reporting(E_ALL);
// set_error_handler('myErrorHandler');

$trans_string = "";
$file_name    = "";
$dir_date     = "";
$date = date('Y-m-d H:i:s');




$trans_details 	= getRandomOnlineCustomerTransaction();
$trans_string 	= $trans_details['trans_string'];
$trans_date 	= $trans_details['trans_date'];

// check if any results
if($trans_string != "") {

	echo $trans_string;
	echo $trans_date;
	

	// if no videos, dir might not exist
	// then files too, will not exist
	
	// check if mp4 exists
	if(!mp4VideoExists($trans_string, $trans_date)) {

		echo "mp4 video not found";

		// doesnt exist
		// check for avi
		// might not have been converted properly
		// try to reconvert
		if(aviVideoExists($trans_string, $trans_date)) {
			reEncodeVideo($trans_string, $trans_date);
		}
		// video doesnt exists
		// update database to reflect missing video
		else{
			// write logs here
			updateMissingVideoDB($trans_string);
		}			
	}
	// mp4 exists, upload
	else{

		echo "exists";

		// check if server has moved to completed
		if(canPostToServer($trans_string, $trans_date)) {

			echo "can post";

			// set to uploading while network request
			setTransationUploadingStatus($trans_string);

			// post to server
			if(postToServer($trans_string, $trans_date)) {
				// on success
				// delete from local transactions
				deleteFromLocalTransactions($trans_string);
			}
			// on error
			// revert status to N in transaction
			else{
				revertTransactionStatus($trans_string);
			}
		}
		// maybe android post failed
		// after X interval middle server must intervene
		// TODO
		else{
			echo "can NOT post";
			//movePendingToCompleted($trans_string)	
		}				
	}	
}



function getRandomOnlineCustomerTransaction() {

	Global $conn;

	$ret_array = array();
	$ret_array['trans_string'] = "";
	$ret_array['trans_date'] = "";

	$sql = "SELECT `trans_string` , date(`date`) as 'dir_date', `date` as t FROM `transactions` WHERE `video` = 'N' AND `cust_type`='online' ORDER BY RAND() LIMIT 1;";
	$exe = mysqli_query($conn, $sql);
	while($row = mysqli_fetch_assoc($exe)) {
		$ret_array['trans_string'] 	= $row['trans_string'];	
		$ret_array['trans_date'] 	= $row['dir_date'];		
	}

	return $ret_array;
}


function directoryForDateExists($trans_date) {
	$path = __DIR__.'/videos/'.$trans_date;
	if (file_exists($path)) {
		return true;
	}
	return false;
}


function mp4VideoExists($trans_string, $trans_date) {
	$path = __DIR__.'/videos/'.$trans_date.'/'.$trans_string.'.mp4';
	if (file_exists($path)) {
		return true;
	}
	return false;
}


function aviVideoExists($trans_string, $trans_date) {
	$path = __DIR__.'/videos/'.$trans_date.'/'.$trans_string.'.avi';
	if (file_exists($path)) {
		return true;
	}
	return false;
}


function reEncodeVideo($trans_string, $trans_date) {
	// TODO
	return;
}


function updateMissingVideoDB($trans_string) {

	echo "updateMissingVideoDB";

	Global $conn;

	$sql = "UPDATE `transactions` SET `video` = 'A' WHERE `trans_string` = '".$trans_string."' ;";
	$exe = mysqli_query($conn, $sql);
}


function canPostToServer($trans_string, $trans_date) {
	
	// empty check
	if(($trans_string=="")&&($trans_date=="")){
		return false;
	}	

	$query = http_build_query([
		't_string' 	=> $trans_string,
		't_date' 	=> $trans_date
	]);

	$url = "http://192.168.0.104/slim_test/post_video_check?".$query;


	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",  
		CURLOPT_HTTPHEADER => array(
			"Content-Type: application/json"
		),
	));

	$response = curl_exec($curl);
	// handle error if any
	if (curl_errno($curl)) {
		$error_msg = curl_error($ch);
	}

	curl_close($curl);
	//echo $response;

	if (isset($error_msg)) {
		// TODO - Handle cURL error accordingly
		return false;
	}
	// no errors
	else {
		$ret = json_decode($response, true);		
		return $ret['success'];
	}
	return false;	
}


function setTransationUploadingStatus($trans_string) {

	echo "setTransationUploadingStatus";

	Global $conn;

	$sql = "UPDATE `transactions` SET `video` = 'U' WHERE `trans_string` = '".$trans_string."' ;";
	$exe = mysqli_query($conn, $sql);
}


function postToServer($trans_string, $trans_date) {
	$path = __DIR__.'/videos/'.$trans_date.'/'.$trans_string.'.mp4';


	$curl = curl_init();

	$url = "http://192.168.0.104/slim_test/post_video";

	curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => array('video'=> new CURLFILE($path),'trans_date' => $trans_date),
	));

	// $response = curl_exec($curl);

	// curl_close($curl);
	// echo $response;

	$response = curl_exec($curl);
	// handle error if any
	if (curl_errno($curl)) {
		$error_msg = curl_error($ch);
	}

	curl_close($curl);
	//echo $response;

	if (isset($error_msg)) {
		// TODO - Handle cURL error accordingly
		return false;
	}
	// no errors
	else {
		$ret = json_decode($response, true);		
		return $ret['post_video'];
	}
	return false;
}


function  deleteFromLocalTransactions($trans_string) {	

	echo "deleteFromLocalTransactions";

	Global $conn;

	$sql = "UPDATE `transactions` SET `video`= 'Y' WHERE `trans_string` = '".$trans_string."' ;";
	$exe = mysqli_query($conn, $sql);
	
}


function  revertTransactionStatus($trans_string) {

	echo "revertTransactionStatus";

	Global $conn;

	$sql = "UPDATE `transactions` SET `video` = 'N' WHERE `trans_string` = '".$trans_string."' ;";
	$exe = mysqli_query($conn, $sql);	
}

?>