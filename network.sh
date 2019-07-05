#!/bin/bash

ping -I enp5s0 -c 2 8.8.4.4 > /dev/null 2>&1

if [ $? -ne 0 ]; then

	nmcli c down uuid 186dda8b-d2fd-3d0f-8880-5b39afb51076 
	nmcli c up uuid 0466b5da-1ebd-44fd-bcb2-bedc865e0b90
	nmcli radio wifi on
	nmcli c down uuid 0466b5da-1ebd-44fd-bcb2-bedc865e0b90
	nmcli c up uuid 186dda8b-d2fd-3d0f-8880-5b39afb51076 

else
    nmcli radio wifi off
fi