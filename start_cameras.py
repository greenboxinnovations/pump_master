from tkinter import *

import subprocess
# Here, we are creating our class, Window, and inheriting from the Frame
# class. Frame is a class from the tkinter module. (see Lib/tkinter/__init__)
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
        quitButton = Button(self, text="Exit",command=self.client_exit)
        stopButton = Button(self, text="Stop",command=self.kill_program)

        # placing the button on my window
        quitButton.place(x=0, y=0)
        stopButton.place(x=100, y=100)


       

    def client_exit(self):
        exit()

    def kill_program(self):
        call('./program.sh kill',shell=True)
        exit()


def check_program_status():
    result = subprocess.run('./program.sh status',shell=True,stdout=subprocess.PIPE)
    print(result.stdout.decode('utf-8'))
    root.after(1000, check_program_status)  


def send_photos():
    result = subprocess.run('/opt/lampp/bin/php /opt/lampp/htdocs/pump_master/send_photos.php',shell=True,stdout=subprocess.PIPE)
    print(result.stdout.decode('utf-8'))
    root.after(5000, send_photos)   

def sync_check():
    result = subprocess.run('/opt/lampp/bin/php /opt/lampp/htdocs/pump_master/sync_check.php',shell=True,stdout=subprocess.PIPE)
    print(result.stdout.decode('utf-8'))
    root.after(3000, sync_check)  

# root window created. Here, that would be the only window, but
# you can later have windows within windows.
root = Tk()

root.geometry("400x300")

#creation of an instance
app = Window(root)


# loops here
# root.after(1000, check_program_status)

root.after(5000, send_photos)

root.after(3000, sync_check)





#mainloop 
root.mainloop()