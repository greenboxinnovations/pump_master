<?php


// echo 'working';
// print_r($_FILES);


// $upDir = 'test_uploads/';
// move_uploaded_file($_FILES['file_contents']['tmp_name'], $upDir. $_FILES['file_contents']['name']);


$upDir = 'test_uploads/';
for ($i=0; $i < sizeof($_FILES['file']['name']); $i++) { 
	move_uploaded_file($_FILES['file']['tmp_name'][$i], $upDir.$_FILES['file']['name'][$i]);	
}

?>