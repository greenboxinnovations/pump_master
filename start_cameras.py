from tkinter import *

import subprocess
# Here, we are creating our class, Window, and inheriting from the Frame
# class. Frame is a class from the tkinter module. (see Lib/tkinter/__init__)

import os
import time
 


isCamUp = 0
isStarting = 0


class Window(Frame):

    # Define settings upon initialization. Here you can specify
    def __init__(self, master=None):
        
        # parameters that you want to send through the Frame class. 
        Frame.__init__(self, master)   

        #reference to the master widget, which is the tk window                 
        self.master = master

        #with that, we want to then run init_window, which doesn't yet exist
        self.init_window()

    #Creation of init_window
    def init_window(self):

        # changing the title of our master widget      
        self.master.title("GUI")

        # allowing the widget to take the full space of the root window
        self.pack(fill=BOTH, expand=1)

        # creating a button instance
        # quitButton = Button(self, text="Exit",command=self.client_exit)
        # startButton = Button(self, text="Exit",command=self.start_client)
        # quitButton = Button(self, text="Exit",command=self.client_exit)
        stopButton = Button(self, text="Stop",command=self.kill_program)

        # placing the button on my window
        # quitButton.place(x=0, y=0)
        stopButton.place(x=100, y=100)


       

    # def client_exit(self):
    #     exit()

    def kill_program(self):
        result = subprocess.run('/opt/lampp/htdocs/pump_master/program.sh kill',shell=True, stdout=subprocess.PIPE)
        print(result.stdout.decode('utf-8'))
        # exit()


def check_ping():
    hostname = "192.168.0.128"
    response = os.system("ping -c 1 " + hostname)
    # and then check the response...
    if response == 0:
        print("Network Active")
    else:
        print("Network Error")
        kill_program_from_out()



def kill_program_from_out():
	result = subprocess.run('/opt/lampp/htdocs/pump_master/program.sh kill',shell=True, stdout=subprocess.PIPE)
	print(result.stdout.decode('utf-8'))



def check_program_status():
    global isCamUp    
    result = subprocess.run('/opt/lampp/htdocs/pump_master/program.sh status',shell=True,stdout=subprocess.PIPE)

    result_formatted = result.stdout.decode('utf-8').rstrip()
    # print(result.stdout.decode('utf-8'))
    print(result_formatted)
    if(result_formatted == "program not running"):
        if(isCamUp == 1):
            print("start program")
            if(isStarting == 0):
                start_program()
        else:            
            print("Please Check the cameras")            

    else:
        if(isCamUp == 1):
            print("everything OK")            
            
        else:
            print("kill program")
            kill_program_from_out()        
    root.after(5000, check_program_status) 


def start_program():
    isStarting = 1
    time.sleep(10)
    print("starting program now")
    result = subprocess.run('/opt/lampp/htdocs/pump_master/program.sh start&',shell=True)
    isStarting = 0
	# print(result.stdout.decode('utf-8'))



def ping_camera():
    global isCamUp
    hostname = "192.168.0.128"
    response = os.system("ping -c 1 " + hostname)
    # and then check the response...
    if response == 0:
        print("Network Active")
        isCamUp = 1
    else:
        print("Network Error")        
        isCamUp = 0
        # kill_program_from_out()
    root.after(5000, ping_camera)



def send_photos():
    result = subprocess.run('/opt/lampp/bin/php /opt/lampp/htdocs/pump_master/send_photos.php',shell=True,stdout=subprocess.PIPE)
    print(result.stdout.decode('utf-8'))
    root.after(5000, send_photos)   


def sync_check():
    result = subprocess.run('/opt/lampp/bin/php /opt/lampp/htdocs/pump_master/sync_check.php',shell=True,stdout=subprocess.PIPE)
    print(result.stdout.decode('utf-8'))
    root.after(3000, sync_check)  


def disable_event():
    pass

# root window created. Here, that would be the only window, but
# you can later have windows within windows.
root = Tk()

root.geometry("400x300+300+300")

#creation of an instance
app = Window(root)


# loops here

root.after(3000, ping_camera)
root.after(3000, check_program_status)

root.after(5000, send_photos)
root.after(3000, sync_check)

root.protocol("WM_DELETE_WINDOW", disable_event)




#mainloop 
root.mainloop()