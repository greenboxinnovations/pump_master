<?php


// $dir = "uploads";

// // if (is_dir_empty($dir)) {
// // 	echo $dir." is empty";
// // }
// // else{
// // 	echo "not empty";
// // }

// function is_dir_empty($dir) {
//   if (!is_readable($dir)) return NULL; 
//   return (count(scandir($dir)) == 2);
// }



// // $curl_handle = curl_init('http://http://pumpmastertest.greenboxinnovations.in/test.php');
// // $curl_handle = curl_init('http://localhost/pump_master/receive_photos.php');
// $target_url = 'http://localhost/pump_master/receive_photos.php';
// $cFile = curl_file_create('C:/xampp/htdocs/pump_master/uploads/image1.jpg');
// $post = array('extra_info' => '123456','file_contents'=> $cFile);
// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL,$target_url);
// curl_setopt($ch, CURLOPT_POST,1);
// curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
// echo $result=curl_exec ($ch);
// echo "\n";
// echo $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
// curl_close ($ch);
// // echo '<pre>';
// // print_r($args);
// // echo '</pre>';




// multiple images

// list all files in directory
$path    = 'uploads';
$files = array_values(array_diff(scandir($path), array('.', '..')));
//print_r($files);

// Create array of files to post
foreach ($files as $index => $file) {
    $postData['file[' . $index . ']'] = curl_file_create(
        realpath($path.'/'.$file),
        mime_content_type($path.'/'.$file),
        basename($path.'/'.$file)
    );
}

// echo '<pre>';
// print_r($postData);
// echo '</pre>';




// $curl_handle = curl_init('http://http://pumpmastertest.greenboxinnovations.in/test.php');
// $curl_handle = curl_init('http://localhost/pump_master/receive_photos.php');
$target_url = 'http://localhost/pump_master/receive_photos.php';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$target_url);
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
echo '<pre>';
echo $result=curl_exec ($ch);
echo '</pre>';
echo "\n";
echo $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close ($ch);
// echo '<pre>';
// print_r($args);
// echo '</pre>';

?>