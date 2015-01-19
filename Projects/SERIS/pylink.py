#! /usr/bin/python

import sys, string
import serial, re, time
import httplib, socket, json

# Domain you want to post to
domain_1 = "emoncms.org"
domain_2 = "api.yeelink.net"

# API key of the server
#apikey_1 = "API_KEY_1"
#apikey_2 = "API_KEY_2"
apikey_1 = "API_KEY1"
apikey_2 = "API_KEY2"

# Counter for the upload frequency (10s is the minimus interval to call the upload API)
upload_counter = 0

# EmonCMS.org specified parameters:
# Node id youd like the node to appear as in emoncms.org
emon_nodeid = 5

# Yeelink.net specified parameters:
yeelink_headers = {"U-ApiKey": apikey_2}
yeelink_bat = []
yeelink_temp = []
yeelink_solar = []

#Initialization of serial port to read the sensor reading
ser = serial.Serial('/dev/ttyUSB0', 57600)

def emoncms_upload(b,t,s):
    try:
        # Single upload Example:
        # http://emoncms.org/input/post.json?node=5&json={BAT:96,TCA:35.91,PAR:8.65}
        # Bulk upload Example: 
        # http://emoncms.org/input/bulk.json?node=5&data=[[-20,5,96,31.09,8.65],[-10,5,96,30.09,10.15],[0,5,92,21.09,23.12]]
        conn_emoncms = httplib.HTTPConnection(domain_1)
        conn_emoncms.request("GET", "/input/post.json?apikey="+apikey_1+"&node="+str(emon_nodeid)+"&json={BAT:"+b+",TCA:"+t+",PAR:"+s+"}")
        r = conn_emoncms.getresponse()
        string = r.read()
        print string
    except httplib.BadStatusLine:
        print "Httplib Badstatus"
        pass
    except (httplib.HTTPException, socket.error) as ex:
        # In case the domain is not reacheable, API server is down
        print "socket error"
        pass
            
    if not conn_emoncms.close():
        conn_emoncms.close()

    
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
        time_stamp = time.strftime("%Y-%m-%d") + "T" + time.strftime("%H:%M:%S")
        Results = re.findall(r'#(.*)#(.*)#(.*)#TIME:(.*)#BAT:(.*)#TCA:(.*)#PAR:(.*)#',msgStr)
        for result in Results:
            battery = result[4]
            temperature = result[5]
            solar = result[6]
            # print battery,temperature,solar,upload_counter
            
        # upload to EmonCMS website
        emoncms_upload(battery,temperature,solar)
            
        if upload_counter == 3:
            upload_counter = 0
            # Send data to the cloud when the time interval hits 3 x 10s
            try:
                # Yeelink Example: curl --request POST --data-binary @datafile.txt --header "U-ApiKey: YOUR_API_KEY_HERE" http://api.yeelink.net/v1.0/device/12/sensor/3/datapoints
                conn_yeelink = httplib.HTTPConnection(domain_2)                
                # Basically, each httplib.HTTPConnection.request must be paired with a .getresponse() call.
                # If that pairing is interrupted by another request operation, the second request will produce the CannotSendRequest error
                conn_yeelink.request("POST","/v1.0/device/17468/sensor/30334/datapoints", json.dumps(yeelink_bat), headers=yeelink_headers)
                # print yeelink_bat
                r = conn_yeelink.getresponse()
                conn_yeelink.request("POST","/v1.0/device/17468/sensor/30344/datapoints", json.dumps(yeelink_temp), headers=yeelink_headers)
                # print yeelink_temp
                r = conn_yeelink.getresponse()
                conn_yeelink.request("POST","/v1.0/device/17468/sensor/30333/datapoints", json.dumps(yeelink_solar), headers=yeelink_headers)
                # print yeelink_solar
                r = conn_yeelink.getresponse()
            except httplib.BadStatusLine:
                print "Httplib Badstatus"
                continue
            except (httplib.HTTPException, socket.error) as ex:
            # In case the domain is not reacheable, API server is down
                print "socket error"
                continue
                
            
            if not conn_yeelink.close():
                conn_yeelink.close()
                
            yeelink_bat[:] = []
            yeelink_temp[:] = []
            yeelink_solar[:] = []
            
        else:
            # Continue to store sensor data into array of list objects
            yeelink_bat.extend([{"timestamp":time_stamp, "value":battery}])
            yeelink_temp.extend([{"timestamp":time_stamp, "value":temperature}])
            # print "temperatue_list: "+str(yeelink_temp)
            yeelink_solar.extend([{"timestamp":time_stamp, "value":float(solar) * 16.6666}])
            upload_counter += 1
