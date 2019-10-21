<?php

require_once __DIR__.'/query/conn.php';

	// Set timezone
date_default_timezone_set('UTC');

	// Start date
$date = '2019-06-01';
	// End date
//$end_date = '2019-09-30';
$end_date = date('Y-m-d');




$main_array = array();


while (strtotime($date) <= strtotime($end_date)) {
	// echo "$date\n\n";
	$date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));


	//get cust type
	$sql = "SELECT * FROM `transactions` WHERE date(`date`) = '$date' AND `trans_string` IS NOT NULL;";		
	$exe = mysqli_query($conn,$sql);

	// transaction videos
	$video_dir = 'videos';

	while ( $r = mysqli_fetch_assoc($exe)) {

		$trans_string = $r['trans_string'];

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
}

echo '<pre>';
//echo count($main_array);
print_r($main_array);

echo '</pre>';


?>