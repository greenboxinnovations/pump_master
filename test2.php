<?php

require_once __DIR__.'/query/conn.php';


$start_date = date("Y-m-d", strtotime("-1 months"));
$end_date 	= date('Y-m-d');



$main_array = array();

$sql = "SELECT `trans_string`, date(`date`) as 'date' FROM `transactions` WHERE date(`date`) BETWEEN '$start_date' AND '$end_date' AND `trans_string` IS NOT NULL ORDER BY `trans_id` DESC;";

$exe = mysqli_query($conn,$sql);

// transaction videos
$video_dir = 'videos';

while ( $r = mysqli_fetch_assoc($exe)) {

	$trans_string 	= $r['trans_string'];
	$date 			= $r['date'];

	$vid_path = $video_dir."/".$date."/".$trans_string.".mp4";

		// transaction videos		
	if(!file_exists($vid_path)){			
		$new = array('date' => $date, 't_string' => $trans_string,'items' => array('V'));
		array_push($main_array, $new);
	}


		// transaction photos
	$upload_dir = 'uploads';

	$check 			= ['_start.jpeg','_start_top.jpeg','_stop.jpeg','_stop_top.jpeg'];
	$description 	= ['Zero Photo','Zero Overhead Photo','Completion Photo','Completed Overhead Photo'];

	foreach ($check as $i => $extention) {

		$file_path = $upload_dir."/".$date."/".$trans_string.$extention;

		if(!file_exists($file_path)) {
				// check if exists
			$key = array_search($trans_string, array_column($main_array, 't_string'));

			if(!is_bool($key)){
					// found
					// print_r($main_array[$key]);
				array_push($main_array[$key]["items"], $extention);
			}
			else{
				$new = array('date' => $date, 't_string' => $trans_string,'items' => array($extention));
				array_push($main_array, $new);
			}
		}

	}			
}


echo '<pre>';
//echo count($main_array);

$json = json_encode($main_array,JSON_NUMERIC_CHECK);
print_r($json);

echo '</pre>';


?>