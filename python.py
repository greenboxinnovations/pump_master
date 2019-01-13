
import subprocess
# Here, we are creating our class, Window, and inheriting from the Frame
# class. Frame is a class from the tkinter module. (see Lib/tkinter/__init__)

import os
import time



def send_videos():
    result = subprocess.run('/opt/lampp/bin/php /opt/lampp/htdocs/pump_master/send_videos.php',shell=True,stdout=subprocess.PIPE)
    print(result.stdout.decode('utf-8'))
    with open("test.txt", "a+") as myfile:
    	myfile.write(result.stdout.decode('utf-8'))
    myfile.close()    	
    # root.after(3000, send_videos)

send_videos()