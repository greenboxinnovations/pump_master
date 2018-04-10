<?php
date_default_timezone_set("Asia/Kolkata");
require 'query/conn.php';


function queryServer(){

	Global $conn;
	$proceed = false;
	$target_url = 'http://pumpmastertest.greenboxinnovations.in/api/sync/1';
		
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$target_url);
	curl_setopt($ch, CURLOPT_TIMEOUT, 40);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	if($result = curl_exec ($ch)){
		$proceed = true;
	}
	curl_close ($ch);


	// server is up
	if($proceed){
		// echo $result;
		$output = json_decode($result, true);


		foreach ($output as $key => $value) {

			$sync_id 		= $value['sync_id'];
			$table_name 	= $value['table_name'];
			$id 			= $value['id'];
			$last_updated 	= $value['last_updated'];


			$sql = "SELECT * FROM `sync` WHERE `table_name` = '".$table_name."';";
			$exe = mysqli_query($conn, $sql);
			$row = mysqli_fetch_assoc($exe);

			if($table_name == 'rates'){

				if($row['id'] < $id){
					downloadRates($id, $last_updated);
				}
				else if($row['id'] > $id){
					uploadRates();
				}
			}
			else if($table_name == 'transactions'){

			}
			else{
				if($row['last_updated'] != $last_updated){
					echo 'download '.$table_name;
					echo '<br>';
					downloadTable($table_name, $last_updated);
				}
			}		
		}	
	}
	else{
		echo 'Something went Wrong';
	}
}

function updateServerId($table_name, $id){

	Global $conn;
	$proceed = false;	

	$post = [
		'table_name' => $table_name,
		'id' => $id		
	];
	$data_string = json_encode($post);

	$target_url = "http://pumpmastertest.greenboxinnovations.in/api/sync";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $target_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 40);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

	// if server is up and file is available {proceed = true}
	if($result = curl_exec ($ch)){
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if($http_code == 200) {
			$proceed = true;			
		}		
	}
	else{
		echo 'no result';
	}
	curl_close($ch);

	if($proceed){
		echo 'proceed';
	}		
}



function downloadRates($new_id, $new_time){

	Global $conn;
	$proceed = false;
	$target_url = 'http://pumpmastertest.greenboxinnovations.in/exe/check_rates.php?pump_id=1&date=';
		
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$target_url);
	curl_setopt($ch, CURLOPT_TIMEOUT, 40);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	if($result = curl_exec ($ch)){
		$output = json_decode($result, true);
		 
		$petrol 	= $output['petrol'];
		$diesel 	= $output['diesel'];
		$date 	    = date("Y-m-d", strtotime($output['date']));

		$sql = "INSERT INTO `rates` (`pump_id`,`diesel`,`petrol`,`date`) VALUES (1,'".$diesel."','".$petrol."','".$date."');";
		$exe = mysqli_query($conn, $sql);

		$sql = "UPDATE `sync` SET `id` = ".$new_id." , `last_updated` = '".$new_time."' WHERE `table_name` = 'rates';";
		$exe = mysqli_query($conn, $sql);
		
	}
	curl_close ($ch);

}


function uploadRates(){
	Global $conn;
	$output = array();

	$sql = "SELECT * FROM `rates` WHERE 1 ORDER BY `rate_id` DESC LIMIT 1;";
	$exe = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($exe);
	if(mysqli_num_rows($exe) > 0){
		$output['rate_set'] = true;	
		$output['petrol']	= $row['petrol'];			
		$output['diesel']	= $row['diesel'];
		$output['date']	    = $row['date'];
		$output['pump_id']	= 1;
	}


	$data_json = json_encode($output);
	$url = "http://pumpmastertest.greenboxinnovations.in/api/transactions/rates";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response  = curl_exec($ch);
	curl_close($ch);

	
	$json = json_decode($response, true);
	$new_time = $json['unix'];	

	echo $sql = "UPDATE `sync` SET `last_updated` = '".$new_time."' WHERE `table_name` = 'rates';";
	$exe = mysqli_query($conn, $sql);

}




function downloadTable($table_name, $last_updated){

	Global $conn;
	$proceed = false;

	$target_url = "http://pumpmastertest.greenboxinnovations.in/mysql_uploads/".$table_name.".sql";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $target_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 400);

	// if server is up and file is available {proceed = true}
	if($result = curl_exec ($ch)){
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if($http_code == 200) {
			$proceed = true;			
		}		
	}	
	curl_close($ch);

	if($proceed){		
		$destination = "mysql_dump/".$table_name.".sql";

		$file = fopen($destination, "w+");
		fputs($file, $result);
		fclose($file);

		$sql = "TRUNCATE TABLE `".$table_name."` ;";
		$exe = mysqli_query($conn, $sql);

		// linux
		echo exec('/opt/lampp/bin/mysql -u"root" --password="toor"  "pump_master" < /opt/lampp/htdocs/pump_master/mysql_dump/'.$table_name.".sql");

		// windows
		// exec('C:/xampp/mysql/bin/mysql -u"root" --password="toor"  "pump_master" < '.$destination);

		// update local sync table
		switch ($table_name) {
			case 'cars':
				$prim_key = 'car_id';
				break;
			case 'users':
				$prim_key = 'user_id';
				break;
			case 'customers':
				$prim_key = 'cust_id';
				break;
			default:
				$prim_key = '';
				break;
		}


		if($prim_key != ''){
			$sql = "SELECT `".$prim_key."` as 'new_id' FROM `".$table_name."` WHERE 1 ORDER BY `".$prim_key."` DESC LIMIT 1;";
			$exe = mysqli_query($conn, $sql);
			$row = mysqli_fetch_assoc($exe);
			$new_id = $row['new_id'];


			$sql3 = "UPDATE `sync` SET `last_updated`= '".$last_updated."', `id` = '".$new_id."' WHERE `table_name` = '".$table_name."';";
			$exe3 = mysqli_query($conn ,$sql3);

			updateServerId($table_name, $new_id);
		}
	}	
}


function sendLocalTransactions(){
	Global $conn;
	$sql = "SELECT * FROM `transactions` WHERE 1;";
	$exe = mysqli_query($conn, $sql);

	if(mysqli_num_rows($exe) > 0){

		$output = array();
		
		while ($row = mysqli_fetch_assoc($exe)) {			
			array_push($output, $row);
		}

		$data_json = json_encode($output);
		echo 'send transactions';

		// send to server
		$url = "http://pumpmastertest.greenboxinnovations.in/api/transactions/save_local_transactions";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


		// if server is up and file is available {proceed = true}
		if($response = curl_exec ($ch)){
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if($http_code == 200) {
				$json = json_decode($response, true);
				foreach ($json as $trans_id) {
					$sql = "DELETE FROM `transactions` WHERE `trans_id` = '".$trans_id."' ;";
					$exe = mysqli_query($conn, $sql);
				}
			}		
		}
		else{
			echo 'no result';
		}
		curl_close($ch);

	}
	else{
		echo 'No transactions present';
	}
}



queryServer();
sendLocalTransactions();

?>