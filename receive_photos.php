<?php
// print_r($_FILES);
// print_r($_POST);

if (isset($_POST) ){
	$output = array();

	for ($i=0; $i < sizeof($_FILES['file']['name']); $i++) { 

		$dir = $_POST['path'][$i];

		if (!file_exists($dir)) {
			mkdir($dir);
		}

		move_uploaded_file($_FILES['file']['tmp_name'][$i], $dir."/".$_FILES['file']['name'][$i]);	

		array_push($output,$dir."/".$_FILES['file']['name'][$i]);

	}
	echo json_encode($output);
}

?>