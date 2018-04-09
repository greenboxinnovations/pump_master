<?php
date_default_timezone_set("Asia/Kolkata");
// multiple images
$dirs = array_filter(glob('uploads/*'), 'is_dir');

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

// echo '<pre>';
// print_r($postData);
// echo '</pre>';

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

	foreach ($data as $key => $path) {
		unlink($path);
	}	

	foreach ($dirs as $key => $dir) {

		if ($dir != "uploads/".date("Y-m-d")) {
			rmdir($dir);		
		}
	}

}

// echo '<pre>';
// print_r($args);
// echo '</pre>';

?>