"""
Continuously read the serial port and process IO data received from a remote XBee.
Depedency:1)https://pypi.python.org/pypi/XBee/2.1.0
          2)database.py
"""

#from xbee import XBee
from database import DBConnector
import serial
import re

#Initialization
ser = serial.Serial('/dev/ttyS0', 38400)
#xbee = XBee(ser)
d = DBConnector()

#Continuously read/parse/store packets
while True:

        """
        read raw data from xbee
        """
        dataStr =''
        msgStr =''
        #response = xbee.wait_read_frame()
        while(dataStr!='\n'):
                response = ser.read()
                dataStr = str(response)
                #print dataStr
                msgStr = msgStr + dataStr

        print msgStr
        """
        extract node id
        """
        m=re.search(r'NID:(\w+)',msgStr);
        if(m):
                nid=m.group(1)
                print nid

        """
        store various measurement result into databse
        """
        Results=re.findall(r'#(\w+):(\d+[.]?[\d]?[\d]?[\d]?)',msgStr)
        for result in Results:
                print result[0] #quantity
                print result[1] #value
                d.store(nid,80,result[0],result[1],result)

#Termination
ser.close()
