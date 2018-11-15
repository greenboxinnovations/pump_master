<?php
date_default_timezone_set("Asia/Kolkata");
// function myErrorHandler( $errType, $errStr, $errFile, $errLine, $errContext )
// {
//     $displayErrors = ini_get( 'display_errors' );
//     $logErrors     = ini_get( 'log_errors' );
//     $errorLog      = ini_get( 'error_log' );

//     // if( $displayErrors ) echo $errStr.PHP_EOL;

//     if( $logErrors )
//     {
//         $message = sprintf('[%s] - (%s, %s) - %s ', date('Y-m-d H:i:s'), $errFile, $errLine ,$errStr);
//         file_put_contents( $errorLog, $message.PHP_EOL, FILE_APPEND );
//     }
// }

// ini_set('log_errors', 1);
// ini_set('error_log', 'php-error.log');

// set_error_handler('myErrorHandler');
// trigger_error('Test');


// 	try {
// 		$i = 5/0;
// 	} catch (Exception $e) {
// 		trigger_error('Test');
// 	}


function httpGet($url)
{

    $ch = curl_init();  
 
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT,1);
	$output = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return  array($httpcode, $output);

}

$trans_id = 11068;

$d = httpGet("http://fuelmaster.greenboxinnovations.in/exe/get_trans_details.php?trans_id=".$trans_id);



$res = json_decode($d[1],true)[0];


echo $res['trans_id'];


// $proceed = false;
// $target_url = 'http://fuelmaster.greenboxinnovations.in/api/sync/1';
	
// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL,$target_url);
// curl_setopt($ch, CURLOPT_TIMEOUT, 40);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);



// if($result = curl_exec ($ch)){
// 	$proceed = true;
// }

// $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
// if($response != 200){
// 	echo "<pre>".htmlspecialchars($response)."</pre>";
// }
// curl_close ($ch);


// // server is up
// if($proceed){
// 	// echo $result;
// 	$output = json_decode($result, true);	
// 	print_r($output);
// }
// else{
// 	echo 'Something went Wrong';
// }








// USEFUL QUERIES
// SELECT `cust_id`,count(*) as total FROM `transactions` WHERE `trans_string` != "" GROUP BY `cust_id`


// SELECT * FROM `transactions` WHERE `trans_string` != "" GROUP BY `cust_id`

// SELECT a.cust_id,b.cust_company FROM `transactions` a 
// JOIN `customers` b
// ON a.cust_id = b.cust_id
// WHERE `trans_string` != "" GROUP BY `cust_id`


// SELECT b.cust_company, count(a.cust_id) as total, a.cust_id FROM `transactions` a
// JOIN `customers` b
// ON a.cust_id = b.cust_id
// WHERE `trans_string` != "" GROUP BY a.cust_id
?>
