
import subprocess
# Here, we are creating our class, Window, and inheriting from the Frame
# class. Frame is a class from the tkinter module. (see Lib/tkinter/__init__)

import os
import time



def check_ping():
    hostname = "192.168.0.1"
    response = os.system("ping -c 1 " + hostname)
    # and then check the response...
    if response == 0:
        print("Network Active")
    else:
        print("Network Error")
        

check_ping()