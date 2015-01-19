#! /usr/bin/python

import sys, string
import serial, re, time
import httplib, socket

# Domain you want to post to: localhost would be an emoncms installation on your own laptop
# this could be changed to emoncms.org to post to emoncms.org
domain = "emoncms.org"

# Location of emoncms in your server, the standard setup is to place it in a folder called emoncms
# To post to emoncms.org change this to blank: ""
emoncmspath = ""

# Write apikey of emoncms account
apikey = "API_KEY"

# Node id youd like the emontx to appear as
nodeid = 5

#Initialization
ser = serial.Serial('/dev/ttyUSB0', 57600)

while True:
        # Measge Format: <=>#387235164#N01#153#TIME:14-49-33#BAT:69#TCA:35.91#PAR:8.65#

        dataStr = ''
        msgStr = ''

        while(dataStr!='<'):
                response = ser.read()
                dataStr = str(response)
                msgStr = msgStr + dataStr
                #print dataStr

        # print msgStr

        m=re.search(r'=>',msgStr);
        if(m):
                Results = re.findall(r'#(.*)#(.*)#(.*)#TIME:(.*)#BAT:(.*)#TCA:(.*)#PAR:(.*)#',msgStr)
                for result in Results:
                        battery = result[4]
                        temperature = result[5]
                        solar = result[6]
                        # print battery,temperature,solar

                try:
                        # Send to emoncms
                        # http://amilab-emon.homelinux.org/emoncms/input/post.json?apikey=API_KEY&node=5&json={BAT:69,TCA:35.91,PAR:8.65}
                        conn = httplib.HTTPConnection(domain, timeout=60)
                        conn.request("GET", "/input/post.json?apikey="+apikey+"&node="+str(nodeid)+"&json={BAT:"+battery+",TCA:"+temperature+",PAR:"+solar+"}")
                        # print("/input/post.json?apikey="+apikey+"&node="+str(nodeid)+"&json={BAT:"+battery+",TCA:"+temperature+",PAR:"+solar+"}")
                        response = conn.getresponse()
                        string = response.read()
                        # print string
                except httplib.BadStatusLine:
                        continue
                except (httplib.HTTPException, socket.error) as ex:
                        # In case the domain is not reacheable, API server is down
                        # Because of 10 seconds sample rate, after 6s + 4s, serial port will read the next value
                        time.sleep(6) 
                        continue
                conn.close()

