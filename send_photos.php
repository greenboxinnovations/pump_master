<?php
date_default_timezone_set("Asia/Kolkata");
// multiple images
$dirs = array_filter(glob('/opt/lampp/htdocs/pump_master/uploads/*'), 'is_dir');

$index = 0;
$postData = array();

foreach ($dirs as $key => $path) {
	// list all files in directory
	$files = array_values(array_diff(scandir($path), array('.', '..')));

	// Create array of files to post
	foreach ($files as $i => $file) {
	    $postData['file[' . $index . ']'] = curl_file_create(
	        realpath($path.'/'.$file),
	        mime_content_type($path.'/'.$file),
	        basename($path.'/'.$file)
	    );

	    
	    $postData['path[' . $index . ']'] = $path;
	    $index++;
	}

}


if(sizeof($postData) == 0){
	echo 'No Photos Found';
}
else{
	$target_url = 'http://pumpmastertest.greenboxinnovations.in/receive_photos.php';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$target_url);
	curl_setopt($ch, CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	$result=curl_exec ($ch);

	$response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close ($ch);


	$data =  json_decode($result);


	if ($response == 200) {

		echo 'Photos Sent';

		foreach ($data as $key => $path) {
			unlink("/opt/lampp/htdocs/pump_master/".$path);
		}	

		foreach ($dirs as $key => $dir) {

			if ($dir != "/opt/lampp/htdocs/pump_master/uploads/".date("Y-m-d")) {
				rmdir($dir);		
			}
		}

	}	
}

?>