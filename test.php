<?php
date_default_timezone_set("Asia/Kolkata");
// function myErrorHandler( $errType, $errStr, $errFile, $errLine, $errContext )
// {
//     $displayErrors = ini_get( 'display_errors' );
//     $logErrors     = ini_get( 'log_errors' );
//     $errorLog      = ini_get( 'error_log' );

//     // if( $displayErrors ) echo $errStr.PHP_EOL;

//     if( $logErrors )
//     {
//         $message = sprintf('[%s] - (%s, %s) - %s ', date('Y-m-d H:i:s'), $errFile, $errLine ,$errStr);
//         file_put_contents( $errorLog, $message.PHP_EOL, FILE_APPEND );
//     }
// }

// ini_set('log_errors', 1);
// ini_set('error_log', 'php-error.log');

// set_error_handler('myErrorHandler');
// trigger_error('Test');


// 	try {
// 		$i = 5/0;
// 	} catch (Exception $e) {
// 		trigger_error('Test');
// 	}


function httpGet($url)
{

    $ch = curl_init();  
 
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT,1);
	$output = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return  array($httpcode, $output);

}

$trans_id = 11068;

$d = httpGet("http://fuelmaster.greenboxinnovations.in/exe/get_trans_details.php?trans_id=".$trans_id);



$res = json_decode($d[1],true)[0];


echo $res['trans_id'];


// $proceed = false;
// $target_url = 'http://fuelmaster.greenboxinnovations.in/api/sync/1';
	
// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL,$target_url);
// curl_setopt($ch, CURLOPT_TIMEOUT, 40);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);



// if($result = curl_exec ($ch)){
// 	$proceed = true;
// }

// $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
// if($response != 200){
// 	echo "<pre>".htmlspecialchars($response)."</pre>";
// }
// curl_close ($ch);


// // server is up
// if($proceed){
// 	// echo $result;
// 	$output = json_decode($result, true);	
// 	print_r($output);
// }
// else{
// 	echo 'Something went Wrong';
// }








// USEFUL QUERIES
// SELECT `cust_id`,count(*) as total FROM `transactions` WHERE `trans_string` != "" GROUP BY `cust_id`


// SELECT * FROM `transactions` WHERE `trans_string` != "" GROUP BY `cust_id`

// SELECT a.cust_id,b.cust_company FROM `transactions` a 
// JOIN `customers` b
// ON a.cust_id = b.cust_id
// WHERE `trans_string` != "" GROUP BY `cust_id`


// SELECT b.cust_company, count(a.cust_id) as total, a.cust_id FROM `transactions` a
// JOIN `customers` b
// ON a.cust_id = b.cust_id
// WHERE `trans_string` != "" GROUP BY a.cust_id
?>

#include <opencv2/opencv.hpp>
#include <opencv2/aruco.hpp>

#include <thread>
#include <atomic>
#include <mutex>

#include <chrono>

#include <string>
#include <iostream>
#include <fstream>



// database includes 
#include "mysql_connection.h"
#include <cppconn/driver.h>
#include <cppconn/exception.h>
#include <cppconn/resultset.h>
#include <cppconn/statement.h>

#include <boost/shared_ptr.hpp>
#include <boost/scoped_ptr.hpp>

using namespace std;
using namespace cv;

cv::Mat displayFrame1;
cv::Mat displayFrame2;

std::atomic<bool> first1(0);
std::atomic<bool> first2(0);

const int		crossedPointX = 35200;
const double	resetInterval = 10.0;

// DEBUG true creates openCV GUI windows and keycontrols on window (ESC to exit)
// DEBUG false disables this, allows controls through terminal (ENTER to exit)
// ENTER to exit SHOULD BE DISABLED IN FINAL VERSION, with PRODUCTION = true
const bool		DEBUG		= false;
const bool		PRODUCTION	= false;

const string CAM1_IP = "rtsp://192.168.0.123:554/Streaming/Channels/2/?transportmode=unicast";
const string CAM2_IP = "rtsp://192.168.0.124:554/Streaming/Channels/2/?transportmode=unicast";


const string C1WINDOW = "cam-ONE";
const string C2WINDOW = "cam-TWO";


sql::Driver *driver;
const string HOST = "tcp://127.0.0.1:3306";
const string USER = "root";
const string PASSWORD = "toor";
const string DB = "timer";

std::chrono::time_point<std::chrono::system_clock> curtime;
bool checkBools = false;
Ptr<aruco::Dictionary> dictionary = cv::aruco::getPredefinedDictionary(cv::aruco::DICT_4X4_50);

struct Kart {
	int id;
	bool crossed;
	std::chrono::time_point<std::chrono::system_clock> last_time;
};

class TSKartVec {
private:
	std::mutex mu_;
	std::vector<Kart> vec;
	bool var = false;

	
	bool hasCrossed(const int id) {
		// check if id has true bool
		// if none found return false
		bool result = false;
		std::vector<Kart>::iterator it;
		for (it = vec.begin(); it != vec.end(); ++it) {
			if ((it->id == id) && (it->crossed == true)) {
				result = true;
			}
		}
		return result;
	}


	void insertUpdateKart(const int id, std::chrono::time_point<std::chrono::system_clock> time) {

		// if found update
		// else insert
		int hitCount = 0;
		std::vector<Kart>::iterator it;
		for (it = vec.begin(); it != vec.end(); ++it) {
			if (it->id == id)
			{
				++hitCount;
				it->last_time = time;
				it->crossed = true;
				checkBools = true;
				cout << "updated from insertUpdate id:" << id << endl;
			}
		}

		if (hitCount == 0) {
			addKart(id, time);
		}
	}

	void addKart(const int id, std::chrono::time_point<std::chrono::system_clock> curtime) {
		Kart k = { id, true, curtime };
		vec.push_back(k);
		checkBools = true;
		cout << "added " << id << endl;
	}

	void updateKart(const int id, std::chrono::time_point<std::chrono::system_clock> time) {
		
		std::vector<Kart>::iterator it;
		for (it = vec.begin(); it != vec.end(); ++it) {
			if (it->id == id)
			{
				it->last_time = time;
				it->crossed = true;
				checkBools = true;
				cout << "updated from update id:" << id << endl;
			}
		}		
	}

	bool isKartActive(const int id) {

		bool active = false;

		// check if kart has started	
		int id_result = getLap(id);

		// if more than 1
		// kart is running
		if (id_result > 0) {
			active = true;
		}
		else {
			insertUpdateKart(id, curtime);
		}

		return active;
	}

	/////
	int getLap(int kart_id) {

		int ret = -1;

		try {
			// housekeeping
			driver = get_driver_instance();
			unique_ptr<sql::Connection> con(driver->connect(HOST.c_str(), USER.c_str(), PASSWORD.c_str()));
			con->setSchema(DB.c_str());
			unique_ptr<sql::Statement> stmt(con->createStatement());

			string query = "SELECT `cur_lap` FROM `operations` WHERE `kart_id` = " + std::to_string(kart_id);

			unique_ptr<sql::ResultSet> res(stmt->executeQuery(query.c_str()));

			if (res->rowsCount() != 0) {

				while (res->next()) {
					ret = res->getInt(1);
				}
			}
		}
		catch (sql::SQLException &e) {
			cout << "# ERR: SQLException in " << __FILE__;
			cout << "(" << __FUNCTION__ << ") on line " << __LINE__ << endl;
			cout << "# ERR: " << e.what();
			cout << " (MySQL error code: " << e.getErrorCode();
			cout << ", SQLState: " << e.getSQLState() << " )" << endl;
		}

		return ret;
	}

	void saveLap(int kart_id, double lap_time) {
		try {
			// housekeeping
			driver = get_driver_instance();
			unique_ptr<sql::Connection> con(driver->connect(HOST.c_str(), USER.c_str(), PASSWORD.c_str()));
			con->setSchema(DB.c_str());
			unique_ptr<sql::Statement> stmt(con->createStatement());

			//string query = "SELECT `cur_lap` FROM `operations` WHERE `kart_id` = " + std::to_string(kart_id);
			string query = "SELECT `timing` FROM `operations` WHERE `kart_id` = " + std::to_string(kart_id);

			unique_ptr<sql::ResultSet> res(stmt->executeQuery(query.c_str()));

			if (res->rowsCount() != 0) {

				// string for db update
				string update_timing;
				// convert double to string
				stringstream stream;
				stream << fixed << setprecision(3) << lap_time;
				string lap_time_str = stream.str();

				while (res->next()) {
					string timing = res->getString("timing").c_str();	// crashes unless .c_str() used
					if (timing == "") {
						update_timing = lap_time_str;
					}
					else {
						update_timing = timing + "|" + lap_time_str;
					}
				}

				// update operations
				string update_query = "UPDATE `operations` SET `timing`= '" + update_timing + "', `cur_lap` = `cur_lap` + 1  WHERE `kart_id` = " + std::to_string(kart_id);
				stmt->executeUpdate(update_query.c_str());
			}
		}
		catch (sql::SQLException &e) {
			cout << "# ERR: SQLException in " << __FILE__;
			cout << "(" << __FUNCTION__ << ") on line " << __LINE__ << endl;
			cout << "# ERR: " << e.what();
			cout << " (MySQL error code: " << e.getErrorCode();
			cout << ", SQLState: " << e.getSQLState() << " )" << endl;
		}
	}

	std::chrono::time_point<std::chrono::system_clock> getTime(int id) {

		std::chrono::time_point<std::chrono::system_clock> time;

		bool result = false;
		std::vector<Kart>::iterator it;
		for (it = vec.begin(); it != vec.end(); ++it) {
			if (it->id == id) {
				time = it->last_time;
			}
		}

		return time;
	}

	double t_diff(std::chrono::time_point<std::chrono::system_clock> stop, std::chrono::time_point<std::chrono::system_clock> start) {
		auto difference = std::chrono::duration_cast<std::chrono::milliseconds>(stop - start);
		double time_diff = (double)difference.count() / (double)1000;
		return time_diff;
	}

public:
	// main entry point 
	// requires LOCK
	void kartTransaction(const int id) {
		std::lock_guard<std::mutex> lock(mu_);
		std::cout << std::this_thread::get_id() << std::endl;

		if (!hasCrossed(id)) {
			cout << "hasCrossed" << endl;
			curtime = std::chrono::system_clock::now();

			if (isKartActive(id)) {
				std::chrono::time_point<std::chrono::system_clock> last_time = getTime(id);
				double lap_time = t_diff(curtime, last_time);
				cout << "laptime for id:" << id << " " << lap_time << endl;

				//postLap(id, lap_time);				
				updateKart(id, curtime);
				saveLap(id, lap_time);

			}
			else {
				// first lap condition
				//postLap(id, 0);				
				updateKart(id, curtime);
				saveLap(id, 0);
			}
		}		
	}

	// reset all bools
	void resetBools() {
		// if any true is found
		// then find time diff
		// if more than reset_interval
		// make false

		std::chrono::time_point<std::chrono::system_clock> now = std::chrono::system_clock::now();

		int hitCount = 0;
		std::vector<Kart>::iterator it;
		for (it = vec.begin(); it != vec.end(); ++it) {
			if (it->crossed == true)
			{

				if (t_diff(now, it->last_time) > resetInterval) {
					cout << t_diff(now, it->last_time) << endl;
					cout << "Reseting " << it->id << " after " << resetInterval << " seconds" << endl;
					it->crossed = false;
				}
				else {
					++hitCount;
				}
			}
		}

		// if all false 
		// make checkbools false
		if (hitCount == 0) {
			checkBools = false;
		}
	}
};


void camThread(const string IP, TSKartVec &kVec) {

	Mat frame;
	VideoCapture video(IP);


	vector <int> ids;
	vector <std::vector<cv::Point2f>> corners;

	// open and check video	
	if (!video.isOpened()) {
		cout << "Error acquiring video" << endl;
		return;
	}
	while (1) {

		// read frame
		video.read(frame);
		if (!frame.empty()) {

			//detect
			cv::aruco::detectMarkers(frame, dictionary, corners, ids);

			// if at least one marker detected 
			if (ids.size() > 0) {
				// draw
				aruco::drawDetectedMarkers(frame, corners, ids);
				// foreach id
				for (vector<int>::size_type i = 0; i != ids.size(); i++) {
					int id = ids[i];
					//kVec.kartTransaction(id);
					cout << id << endl;
				}				
			}


			if (DEBUG) {
				if (IP == CAM1_IP) {
					frame.copyTo(displayFrame1);
					first1 = true;
				}
				else {
					frame.copyTo(displayFrame2);
					first2 = true;
				}
			}
		}
	}
}


int main(int argc, char** argv) {

	// no exit controls
	if (PRODUCTION) {
		cout << "NO EXIT CONTROLS" << endl;
	}

	// if debug create windows
	if (DEBUG) {
		cout << "ESC on window to exit" << endl;
		namedWindow(C1WINDOW);
		namedWindow(C2WINDOW);

		cv::resizeWindow(C1WINDOW, 640, 480);
		cv::resizeWindow(C2WINDOW, 640, 480);
	}


	cout << "Main start" << endl;

	TSKartVec kVec;

	thread t1(camThread, CAM1_IP, std::ref(kVec));
	t1.detach();

	thread t2(camThread, CAM2_IP, std::ref(kVec));
	t2.detach();

	string checkExit;
	while (1) {

		// reset bools here
		if (checkBools) {
			kVec.resetBools();
		}


		// if DEBUG update windows
		if (DEBUG) {
			
			if (first1 && first2) {

				imshow(C1WINDOW, displayFrame1);
				imshow(C2WINDOW, displayFrame2);


				char character = waitKey(10);
				switch (character)
				{
				case 27:
					destroyAllWindows();
					return 0;
					break;

				default:
					break;
				}
			}
		}
		else {
			// DEBUG == false, allow exit with ENTER PRESS
			if (!PRODUCTION) {
				cout << "ENTER KEY in TERMINAL to exit." << endl;
				while (1) {
					while (getline(cin, checkExit))
					{
						if (checkExit.empty())
							return 0;
					}
				}
			}			
		}
	}

	return 0;
}

