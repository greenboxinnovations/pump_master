<?php

	$host_name 	= 'localhost';
	$user_name 	= 'fuel_test';
	$password 	= 'fuel123test!@#';
	// $user_name 	= 'root';
	// $password 	= 'toor';
	$db_name 	= 'fuel_test';
	// $db_name 	= 'pump_master';

	$conn = mysqli_connect($host_name, $user_name, $password, $db_name);

	if($conn == FALSE)
	{
		echo 'ERROR: '.mysqli_connect_error($conn);
	}
?>