
#include <iostream>

// for date string
#include <iostream>
#include <iomanip>
#include <ctime>
#include <sstream>
#include <string>


#include <opencv2/opencv.hpp>
#include <opencv2/aruco.hpp>

 

#include <chrono>

#include <string>
#include <iostream>
#include <fstream>

// Vlc player
#include "VlcCap.h"


using namespace std;
using namespace cv;

cv::Mat displayFrame1;
// cv::Mat displayFrame2;
// cv::Mat displayFrame3;



// const string CAM1_IP = "rtsp://192.168.0.129:554/Streaming/Channels/2/?transportmode=unicast";
// const string CAM2_IP = "rtsp://192.168.0.128:554/Streaming/Channels/2/?transportmode=unicast";
const string CAM3_IP = "rtsp://192.168.0.127:554/Streaming/Channels/1/?transportmode=unicast";
const string C3WINDOW = "cam-THREE";
const string C1WINDOW = "cTHREE";


const static int SENSITIVITY = 20;

string dateString() {
	auto t = std::time(nullptr);
	auto tm = *std::localtime(&t);

	std::ostringstream oss;
	oss << std::put_time(&tm, "%d-%m-%Y %H:%M:%S");
	string str = oss.str();

	std::cout << str << std::endl;
	return str;
}

void writeDateSecondary(Mat& frame){

	string date = dateString();
	// just some valid rectangle arguments
	int x = 0;
	int y = 0;
	int width = 200;
	int height = 33;
	// our rectangle...
	cv::Rect rect(x, y, width, height);			
	// essentially do the same thing
	cv::rectangle(frame, rect, cv::Scalar(0, 0, 0), CV_FILLED);


	cv::putText(frame, //target image
		date, //text
		//cv::Point(10, clickedFrame.rows / 2), //top-left position
		cv::Point(5, 20), //top-left position
		cv::FONT_HERSHEY_DUPLEX,
		0.5,
		CV_RGB(255, 255, 255), //font color
		0.5);
}


void writeDatePrimary(Mat& frame){

	string date = dateString();
	// just some valid rectangle arguments
	int x = 0;
	int y = 0;
	int width = 580;
	int height = 90;
	// our rectangle...
	cv::Rect rect(x, y, width, height);			
	// essentially do the same thing
	cv::rectangle(frame, rect, cv::Scalar(0, 0, 0), CV_FILLED);


	cv::putText(frame, //target image
		date, //text
		//cv::Point(10, clickedFrame.rows / 2), //top-left position
		cv::Point(10, 60), //top-left position
		cv::FONT_HERSHEY_DUPLEX,
		1.5,
		CV_RGB(255, 255, 255), //font color
		2.0);
}

int main() {

	// store feed and clicked image
	Mat webcamFeed, clickedFrame;

	VlcCap cap;
	cap.open(CAM3_IP.c_str());	
	namedWindow(C3WINDOW,WINDOW_NORMAL);
	cv::resizeWindow(C3WINDOW, 640, 480);

	namedWindow(C1WINDOW,WINDOW_NORMAL);
	cv::resizeWindow(C1WINDOW, 640, 480);

	
	if (!cap.isOpened()) {
		cout << "Error acquiring video" << endl;
		return 0;
	}


	while (1) {

		// read feed
		cap.read(webcamFeed);

		// show results		
		imshow(C3WINDOW, webcamFeed);



		// exit on escape keypress
		switch (waitKey(10)) {
		case 27:
			return 0;
			break;

		case 32:
			dateString();
			break;

		case 99:
			destroyWindow("Clicked Image");
			cap.read(clickedFrame);
			writeDatePrimary(clickedFrame);
			imshow(C1WINDOW, clickedFrame);
			break;
		}

	}




	return 0;
}