<?php
date_default_timezone_set("Asia/Kolkata");

class Test
{
	private $_db;
	private $_method;
	private $_getParams = null;
	private $_postParams = null;

	public function __construct($db,$method,$getParams,$postParams){

		$this->_db = $db->getInstance();
		$this->_method = $method;
		$this->_getParams = $getParams;		
		$size = sizeof($this->_getParams);
		$this->_postParams = $postParams;

		if($this->_method=='GET')
		{
			$today = date("Y-m-d");
			switch ($size) {
				case 0:
					$this->test23();					
					break;
				case 1:
					//$this->getAllCarsShifts(0,$today);
					break;
				case 2:
					//$this->getAllCarsShifts(0,$today);
					break;
				default:
					$output = array();
					$output['error'] = 'no date provided';
					echo json_encode($output);
					break;
			}
		}
		elseif ($this->_method == 'POST')
		{ 
			if ($this->_getParams[0] == 'delete')
			{
				$this->deleteBook($this->_postParams);
			}else{
				$this->addBook($this->_postParams);
			}
		}
	}

	private function addBook($postParams){

		

	}

	private function test23(){

		date_default_timezone_set("Asia/Kolkata");
		$date = date("Y-m-d H:i:s");
		$table_name	  = "cars";

		try {
			$upload_dir =  realpath(__DIR__ . '/../../mysql_uploads');
			$filename = $upload_dir ."/".$table_name.'.sql';
			// $db_name = "pump_master";


			echo Globals::MYSQLDUMP_PATH." -u\"".Globals::DB_USER_NAME."\" --password=\"".Globals::DB_PASSWORD."\" \"".Globals::DB_NAME."\" \"".$table_name."\" > ".$filename;
			exec(Globals::MYSQLDUMP_PATH." -u\"".Globals::DB_USER_NAME."\" --password=\"".Globals::DB_PASSWORD."\" \"".Globals::DB_NAME."\" \"".$table_name."\" > ".$filename." 2>&1"
				,$output,$return_val);
			if($return_val !== 0) {
				echo 'Error<br>';
				print_r($output);   
			}
		} catch (Exception $e) {
			echo $e->errorMessage();
		}
		

	}
}
?>