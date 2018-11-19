<?php
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
	}

	$output['names']= $names;

	echo json_encode($output);

}

?>