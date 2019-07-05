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

// for date string
#include <iomanip>
#include <ctime>
#include <sstream>

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
const string C3WINDOW = "GREENBOXINNOVATIONS";

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


string dateString() {
	auto t = std::time(nullptr);
	auto tm = *std::localtime(&t);

	std::ostringstream oss;
	oss << std::put_time(&tm, "%d-%m-%Y %H:%M:%S");
	string str = oss.str();

	std::cout << str << std::endl;
	return str;
}

cv::Mat writeDateSecondary(Mat frame){

	string date = dateString();
	// just some valid rectangle arguments
	int x = 0;
	int y = 0;
	int width = 200;
	int height = 33;
	// our rectangle...
	cv::Rect rect(x, y, width, height);			
	// essentially do the same thing
	cv::rectangle(frame, rect, cv::Scalar(0, 0, 0), FILLED);


	cv::putText(frame, //target image
		date, //text
		//cv::Point(10, clickedFrame.rows / 2), //top-left position
		cv::Point(5, 20), //top-left position
		cv::FONT_HERSHEY_DUPLEX,
		0.5,
		CV_RGB(255, 255, 255), //font color
		0.5);
	return frame;
}


cv::Mat writeDatePrimary(Mat frame){

	string date = dateString();
	// just some valid rectangle arguments
	int x = 0;
	int y = 0;
	int width = 580;
	int height = 90;
	// our rectangle...
	cv::Rect rect(x, y, width, height);			
	// essentially do the same thing
	cv::rectangle(frame, rect, cv::Scalar(0, 0, 0), FILLED);


	cv::putText(frame, //target image
		date, //text
		//cv::Point(10, clickedFrame.rows / 2), //top-left position
		cv::Point(10, 60), //top-left position
		cv::FONT_HERSHEY_DUPLEX,
		1.5,
		CV_RGB(255, 255, 255), //font color
		2.0);

	return frame;
}



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
				string cmd = "mkdir -p -m 777 /opt/lampp/htdocs/pump_master/uploads/"+date;
				
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
					// writeDateSecondary(displayFrame1);
					// imwrite(file_name, displayFrame1 );
					Mat d = writeDateSecondary(displayFrame1);
					imwrite(file_name, d );
				}else{
					// writeDateSecondary(displayFrame2);
					// imwrite(file_name, displayFrame2 );
					Mat d = writeDateSecondary(displayFrame2);
					imwrite(file_name, d );
				}
				// writeDatePrimary(displayFrame3);
				// imwrite(file_name2, displayFrame3 );
				Mat s = writeDatePrimary(displayFrame3);
				imwrite(file_name2, s );


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




// int main(int argc, char** argv) {

// 	cout << "ESC on window to exit" << endl;
// 	namedWindow(C1WINDOW,WINDOW_NORMAL);
// 	namedWindow(C2WINDOW,WINDOW_NORMAL);
// 	namedWindow(C3WINDOW,WINDOW_NORMAL);

// 	cv::resizeWindow(C1WINDOW, 640, 480);
// 	cv::resizeWindow(C2WINDOW, 640, 480);
// 	cv::resizeWindow(C3WINDOW, 640, 480);

// 	int i = 0;

	
// 	// horizontal size = d1.cols + d2.cols
// 	int h_size = displayFrame1.cols + displayFrame2.cols;
// 	// vertical size = d1.ros + d3.rows
// 	int v_size = displayFrame1.rows + displayFrame3.rows;

// 	cv::Mat comboFrame(cv::Size(h_size, v_size));	


// 	cout << "Main start" << endl;

// 	thread t1(camThread, CAM1_IP);
// 	t1.detach();

// 	thread t2(camThread, CAM2_IP);
// 	t2.detach();


// 	std::chrono::steady_clock::time_point start = std::chrono::steady_clock::now();


// 	// time (&rawtime);
// 	// timeinfo = localtime (&rawtime);
// 	// strftime(buffer,80,"%Y-%m-%d",timeinfo);
// 	// std::string date(buffer);	


// 	thread t3(camThread, CAM3_IP);
// 	t3.detach();

// 	// thread t4(systemThread);
// 	// t4.detach();

// 	string checkExit;
// 	while (1) {

// 		if (first1 && first2 && first3) {
// 		// if (first1 && first2) {

// 			imshow(C1WINDOW, displayFrame1);
// 			imshow(C2WINDOW, displayFrame2);
// 			imshow(C3WINDOW, comboFrame);

// 			getCamStatus();

// 			// std::chrono::steady_clock::time_point test = std::chrono::steady_clock::now();
// 			// if (std::chrono::duration_cast<std::chrono::milliseconds>(test - start).count() > intervalMillis) {
// 			// 	cout << (intervalMillis / 1000) << " seconds have passed" << endl;
// 			// 	start = std::chrono::steady_clock::now();


// 			// 	// unix timestamp-ISH
// 			// 	// not sure
// 			// 	auto dur = test.time_since_epoch();
// 			// 	auto timestamp = std::chrono::duration_cast<std::chrono::seconds>(dur).count();

// 			// 	std::cout << "Timestamp: " << timestamp << std::endl;
// 			// 	ostringstream out;
// 			// 	out << timestamp;
// 				//imwrite("/clicks/" + out.str() + ".JPG", frame);

// 				// try{
// 				// 	imwrite("/opt/lampp/htdocs/pump_master/uploads/left/" +date+ std::to_string(i) + ".jpeg", displayFrame1);
// 				// 	imwrite("/opt/lampp/htdocs/pump_master/uploads/right/" +date+ std::to_string(i) + ".jpeg", displayFrame2);

// 				// }catch  (const std::exception& e){
// 				// 	 std::cout << e.what(); 
// 				// }

// 				// i++;			
				
// 			// }

// 		}

// 		char character = waitKey(10);
// 		switch (character)
// 		{
// 		case 27:			
// 			destroyAllWindows();
// 			return 0;
// 			break;

// 		case 32:
// 			break;


// 		default:
// 			break;
// 		}
// 	}

// 	return 0;
// }


#include <sys/stat.h>
#include <time.h>
#include <stdio.h>

// latest file second
void filetime(const string file1){

	struct stat t_stat1;
    stat(file1.c_str(), &t_stat1);
    struct tm * timeinfo1 = localtime(&t_stat1.st_ctime); // or gmtime() depending on what you want

	strftime(buffer,80,"%Y-%m-%d %I-%M-%S",timeinfo1);
	std::string date(buffer);
    printf("File time and date: %s", date.c_str());  
    printf("\n");  

}


// latest file second
// latest file second
void updateTransTime(const string file1,const string file2, const string trans_string){

	struct stat t_stat1;	
    stat(file1.c_str(), &t_stat1);    
    struct tm * timeinfo1 = localtime(&t_stat1.st_ctime); // or gmtime() depending on what you want
    

    // cout << "Current Day, Date and Time is = " 
    //      << asctime(timeinfo1)<< endl;

    struct stat t_stat2;
    stat(file2.c_str(), &t_stat2);
    struct tm * timeinfo2 = localtime(&t_stat2.st_ctime); // or gmtime() depending on what you want


    // cout << "Current Day, Date and Time is = " 
    //      << asctime(timeinfo2)<< endl;

    double pre_sec = std::difftime(t_stat2.st_ctime, t_stat1.st_ctime);

    int total_seconds = (int)pre_sec;

    // std::cout << "Wall time passed: "
    //           << std::difftime(t_stat2.st_ctime, t_stat1.st_ctime) << " s.\n";

    
    int  hours, minutes;
	minutes = total_seconds / 60;
	hours = minutes / 60;
	int seconds = int(total_seconds%60);	
	
	char s[25];
	sprintf(s, "%02d:%02d:%02d", hours, minutes, seconds);
	cout << s << endl;
	string update_string(s);

	try {
		// housekeeping
		driver = get_driver_instance();
		unique_ptr<sql::Connection> con(driver->connect(HOST.c_str(), USER.c_str(), PASSWORD.c_str()));
		con->setSchema(DB.c_str());
		unique_ptr<sql::Statement> stmt(con->createStatement());

		// string update_query = "UPDATE `versions` SET `name`= '"+update_string+"' WHERE `ver_id` = 1";
		string update_query = "UPDATE `transactions` SET `trans_time`= '"+update_string+"' WHERE `trans_string` = '"+trans_string+"';";
		
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

int main(int argc, char **argv)
{
    // struct stat t_stat;
    // stat("1.jpeg", &t_stat);
    // struct tm * timeinfo = localtime(&t_stat.st_ctime); // or gmtime() depending on what you want
    // printf("File time and date: %s", asctime(timeinfo));

	// filetime("1.jpeg");
	// filetime("jiggy.txt");

	filetime("1.jpeg");
	filetime("jiggy.txt");
	updateTransTime("1.jpeg","jiggy.txt","4cPqRS3hYf");

    return 0;
}

