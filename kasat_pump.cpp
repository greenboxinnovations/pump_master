#include <opencv2/opencv.hpp>
#include <opencv2/aruco.hpp>

#include <thread>
#include <atomic>
#include <mutex>
#include <time.h>     

#include <chrono>

#include <string>
#include <iostream>
#include <fstream>

// Vlc player
#include "VlcCap.h"


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
cv::Mat displayFrame3;

std::atomic<bool> first1(0);
std::atomic<bool> first2(0);
std::atomic<bool> first3(0);



const string CAM1_IP = "rtsp://192.168.0.129:554/Streaming/Channels/2/?transportmode=unicast";
const string CAM2_IP = "rtsp://192.168.0.128:554/Streaming/Channels/2/?transportmode=unicast";
const string CAM3_IP = "rtsp://192.168.0.127:554/Streaming/Channels/1/?transportmode=unicast";


const string C1WINDOW = "cam-ONE";
const string C2WINDOW = "cam-TWO";
// const string C3WINDOW = "cam-THREE";

sql::Driver *driver;
const string HOST = "tcp://127.0.0.1:3306";
const string USER = "root";
const string PASSWORD = "toor";
const string DB = "pump_master";

// system calls
#include <stdlib.h>


// date string stuff
time_t rawtime;
struct tm * timeinfo;
char buffer [80];


// test stuff
const int intervalMillis = 1000 * 5 * 60; 


void setCamStatus(string cam_no) {

	try {
		// housekeeping
		driver = get_driver_instance();
		unique_ptr<sql::Connection> con(driver->connect(HOST.c_str(), USER.c_str(), PASSWORD.c_str()));
		con->setSchema(DB.c_str());
		unique_ptr<sql::Statement> stmt(con->createStatement());

		string update_query = "UPDATE `cameras` SET `status`= 0 WHERE `cam_no` = " + cam_no;
		stmt->executeUpdate(update_query.c_str());
	}
	catch (sql::SQLException &e) {
		cout << "# ERR: SQLException in " << __FILE__;
		cout << "(" << __FUNCTION__ << ") on line " << __LINE__ << endl;
		cout << "# ERR: " << e.what();
		cout << " (MySQL error code: " << e.getErrorCode();
		cout << ", SQLState: " << e.getSQLState() << " )" << endl;
	}

}



void getCamStatus() {	

	try {
		// housekeeping
		driver = get_driver_instance();
		unique_ptr<sql::Connection> con(driver->connect(HOST.c_str(), USER.c_str(), PASSWORD.c_str()));
		con->setSchema(DB.c_str());
		unique_ptr<sql::Statement> stmt(con->createStatement());

		string query = "SELECT * FROM `cameras` WHERE `status` = 1";
		unique_ptr<sql::ResultSet> res(stmt->executeQuery(query.c_str()));
		

		if (res->rowsCount() != 0) {
			while (res->next()) {

				// cout << res->getString("cam_no") << endl;
				// cout << res->getString("type") << endl;
				// cout << res->getString("trans_string") << endl;

				// make a date string
				time (&rawtime);
				timeinfo = localtime (&rawtime);
				strftime(buffer,80,"%Y-%m-%d",timeinfo);
				std::string date(buffer);				


				// make directory if not exists
				// string cmd = "mkdir -m 777 ./uploads/"+date;
				string cmd = "mkdir -m 777 /opt/lampp/htdocs/pump_master/uploads/"+date;
				
				system("clear");
				system(cmd.c_str());


				// make file names
				// string file_name = "uploads/"+date+"/"+res->getString("trans_string") + "_" +res->getString("type")+".jpeg";
				// string file_name2 = "uploads/"+date+"/"+res->getString("trans_string") + "_" + res->getString("type")+"_top.jpeg";
				string file_name = "/opt/lampp/htdocs/pump_master/uploads/"+date+"/"+res->getString("trans_string") + "_" +res->getString("type")+".jpeg";
				string file_name2 = "/opt/lampp/htdocs/pump_master/uploads/"+date+"/"+res->getString("trans_string") + "_" + res->getString("type")+"_top.jpeg";


				// select camera
				if (res->getString("cam_no") == "1")
				{
					imwrite(file_name, displayFrame1 );
				}else{
					imwrite(file_name, displayFrame2 );
				}
				imwrite(file_name2, displayFrame3 );


				// reset status in cameras
				setCamStatus(res->getString("cam_no"));
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


}


void camThread(const string IP) {

	Mat frame;
	VlcCap cap;
	cap.open(IP.c_str());
	// VideoCapture video(IP);

	// open and check video	
	if (!cap.isOpened()) {
		cout << "Error acquiring video" << endl;
		return;
	}
	while (1) {


		// read frame
		cap.read(frame);
		if (!frame.empty()) {			

			if(IP == CAM1_IP){
				frame.copyTo(displayFrame1);
				first1 = true;
			}
			else if(IP == CAM2_IP){
				frame.copyTo(displayFrame2);
					first2 = true;
			}
			else{
				frame.copyTo(displayFrame3);
				first3 = true;
			}
		}
	}
}

void systemThread() {

	// system("python3.5 /opt/lampp/htdocs/pump_master/start_cameras.py");

}


int main(int argc, char** argv) {

	cout << "ESC on window to exit" << endl;
	namedWindow(C1WINDOW,WINDOW_NORMAL);
	namedWindow(C2WINDOW,WINDOW_NORMAL);
	// namedWindow(C3WINDOW,WINDOW_NORMAL);

	cv::resizeWindow(C1WINDOW, 640, 480);
	cv::resizeWindow(C2WINDOW, 640, 480);

	int i = 0;

	// cv::resizeWindow(C3WINDOW, 640, 480);


	cout << "Main start" << endl;

	thread t1(camThread, CAM1_IP);
	t1.detach();

	thread t2(camThread, CAM2_IP);
	t2.detach();


	std::chrono::steady_clock::time_point start = std::chrono::steady_clock::now();


	// time (&rawtime);
	// timeinfo = localtime (&rawtime);
	// strftime(buffer,80,"%Y-%m-%d",timeinfo);
	// std::string date(buffer);	


	thread t3(camThread, CAM3_IP);
	t3.detach();

	// thread t4(systemThread);
	// t4.detach();

	string checkExit;
	while (1) {

		if (first1 && first2 && first3) {
		// if (first1 && first2) {

			imshow(C1WINDOW, displayFrame1);
			imshow(C2WINDOW, displayFrame2);
			// imshow(C3WINDOW, displayFrame3);

			getCamStatus();

			// std::chrono::steady_clock::time_point test = std::chrono::steady_clock::now();
			// if (std::chrono::duration_cast<std::chrono::milliseconds>(test - start).count() > intervalMillis) {
			// 	cout << (intervalMillis / 1000) << " seconds have passed" << endl;
			// 	start = std::chrono::steady_clock::now();


			// 	// unix timestamp-ISH
			// 	// not sure
			// 	auto dur = test.time_since_epoch();
			// 	auto timestamp = std::chrono::duration_cast<std::chrono::seconds>(dur).count();

			// 	std::cout << "Timestamp: " << timestamp << std::endl;
			// 	ostringstream out;
			// 	out << timestamp;
				//imwrite("/clicks/" + out.str() + ".JPG", frame);

				// try{
				// 	imwrite("/opt/lampp/htdocs/pump_master/uploads/left/" +date+ std::to_string(i) + ".jpeg", displayFrame1);
				// 	imwrite("/opt/lampp/htdocs/pump_master/uploads/right/" +date+ std::to_string(i) + ".jpeg", displayFrame2);

				// }catch  (const std::exception& e){
				// 	 std::cout << e.what(); 
				// }

				// i++;			
				
			// }

		}

		char character = waitKey(10);
		switch (character)
		{
		case 27:			
			destroyAllWindows();
			return 0;
			break;

		case 32:
			break;


		default:
			break;
		}
	}

	return 0;
}

