<?php
	require '../api/classes/globals.class.php';

	$host_name 	= Globals::DB_HOSTNAME;
	$user_name 	= Globals::DB_USER_NAME;
	$password 	= Globals::DB_PASSWORD;
	$db_name 	= Globals::DB_NAME;

	$conn = mysqli_connect($host_name, $user_name, $password, $db_name);

	if($conn == FALSE)
	{
		echo 'ERROR: '.mysqli_connect_error($conn);
	}
?>