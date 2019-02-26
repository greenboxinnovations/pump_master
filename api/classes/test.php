<?php


#!/usr/bin/php
ini_set("display_errors", 1);
ini_set("track_errors", 1);
ini_set("html_errors", 1);
error_reporting(E_ALL);

class TGlobals
{

	// DB connection
	const DB_USER_NAME 	= "root";
	const DB_PASSWORD  	= "toor"; 
	const DB_NAME 		= "pump_master"; 
	const DB_HOSTNAME   = "localhost";
	

	// MSG params
	const SEND_MSG = true;
	const PRINT_RECEIPT = false;


	// URL's
	const URL_SYNC_CHECK = "http://fueltest.greenboxinnovations.in";
	const URL_MSG_VIEW = "http://fuelmaster.greenboxinnovations.in/cmsg.php?t=";

	const MYSQLDUMP_PATH = "/opt/lampp/bin/mysqldump";
}



echo TGlobals::DB_HOSTNAME;
		try {
			$table_name	  = "cars";

			$upload_dir =  realpath(__DIR__ . '/../../mysql_uploads');
			$filename = $upload_dir ."/".$table_name.'.sql';
			// $db_name = "pump_master";


			// echo TGlobals::MYSQLDUMP_PATH." -u\"".TGlobals::DB_USER_NAME."\" --password=\"".TGlobals::DB_PASSWORD."\" \"".TGlobals::DB_NAME."\" \"".$table_name."\" > ".$filename;
			exec(TGlobals::MYSQLDUMP_PATH." -u\"".TGlobals::DB_USER_NAME."\" --password=\"".TGlobals::DB_PASSWORD."\" \"".TGlobals::DB_NAME."\" \"".$table_name."\" > ".$filename." 2>&1"
				,$output,$return_val);
			if($return_val !== 0) {
				echo 'Error<br>';
				print_r($output);   
			}
		} catch (Exception $e) {
			echo $e->errorMessage();
		}


?>