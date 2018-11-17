<?php
// print_r($_FILES);
// print_r($_POST);

// print_r(phpinfo());

if (isset($_POST) ){
	$output = array();

	for ($i=0; $i < sizeof($_FILES['file']['name']); $i++) { 



		// $dir = $_POST['path'][$i];

		// $dir = str_replace('/opt/lampp/htdocs/pump_master/', '', $dir);

		// if (!file_exists($dir)) {
		// 	mkdir($dir);
		// }

		move_uploaded_file($_FILES['file']['tmp_name'][$i], "videos/99/".$_FILES['file']['name'][$i]);

		// array_push($output,$dir."/".$_FILES['file']['name'][$i]);
		array_push($output, "/opt/lampp/htdocs/pump_master/videos/99/".$_FILES['file']['name'][$i]);
	}
	echo json_encode($_FILES['file']);
	// var_dump($_POST);	
	// var_dump($_FILES);
}

?>