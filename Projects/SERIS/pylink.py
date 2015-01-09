#! /usr/bin/python

import re, httplib, time
import serial, sys, string

# Domain you want to post to: localhost would be an emoncms installation on your own laptop
# this could be changed to emoncms.org to post to emoncms.org
domain = "emoncms.org"

# Location of emoncms in your server, the standard setup is to place it in a folder called emoncms
# To post to emoncms.org change this to blank: ""
emoncmspath = ""

# Write apikey of emoncms account
apikey = "api_key"

# Node id youd like the emontx to appear as
nodeid = 5

#Initialization
ser = serial.Serial('/dev/ttyAMA0', 115200)

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
                        # http://domain.org/emoncms/input/post.json?apikey=b53ec1abe610c66009b207d6207f2c9e&node=5&json={BAT:69,TCA:35.91,PAR:8.65}
                        conn = httplib.HTTPConnection(domain)
                        conn.request("GET", "/input/post.json?apikey="+apikey+"&node="+str(nodeid)+"&json={BAT:"+battery+",TCA:"+temperature+",PAR:"+solar+"}")
                        # print("/input/post.json?apikey="+apikey+"&node="+str(nodeid)+"&json={BAT:"+battery+",TCA:"+temperature+",PAR:"+solar+"}")
                        response = conn.getresponse()
                        string = response.read()
                        # print string
                except httplib.BadStatusLine:
                        continue
                conn.close()
