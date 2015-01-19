#! /usr/bin/python

import sys, string
import serial, re, time
import httplib, socket, json

# Domain you want to post to
domain_1 = "emoncms.org"
domain_2 = "api.yeelink.net"

# API key of the server
apikey_1 = "API_KEY_1"
apikey_2 = "API_KEY_2"

# EmonCMS.org specified parameters:
# Node id youd like the node to appear as in emoncms.org
emon_nodeid = 5

# Yeelink.net specified parameters:
yeelink_headers = {"U-ApiKey": apikey_2}
yeelink_upload_counter = 0

#Initialization of serial port to read the sensor reading
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
            # print battery,temperature,solar,yeelink_upload_counter
        try:
            # Send data to the cloud
            # Emoncms Example:  http://emoncms.org/input/post.json?apikey=API_KEY&node=5&json={BAT:69,TCA:35.91,PAR:8.65}
            conn_emoncms = httplib.HTTPConnection(domain_1)
            conn_emoncms.request("GET", "/input/post.json?apikey="+apikey_1+"&node="+str(emon_nodeid)+"&json={BAT:"+battery+",TCA:"+temperature+",PAR:"+solar+"}")
            # Yeelink Example: curl --request POST --data-binary @datafile.txt --header "U-ApiKey: YOUR_API_KEY_HERE" http://api.yeelink.net/v1.0/device/12/sensor/3/datapoints
            
            if yeelink_upload_counter > 0:
                try:
                    yeelink_upload_counter = 0;
                    
                    conn_yeelink = httplib.HTTPConnection(domain_2)
                    time_stamp = time.strftime("%Y-%m-%d") + "T" + time.strftime("%H:%M:%S")
                    yeelink_data = {"timestamp":time_stamp, 
                        "value":float(solar) * 16.6666}
                    conn_yeelink.request("POST","/v1.0/device/17468/sensor/30333/datapoints", json.dumps(yeelink_data), headers=yeelink_headers)
                    response = conn_yeelink.getresponse()
                    string = response.read()
                    print string
                
                except httplib.BadStatusLine:
                    continue
                except (httplib.HTTPException, socket.error) as ex:
                    # In case the domain is not reacheable, API server is down
                    time.sleep(10) 
                    continue
                conn_yeelink.close()
            else:
                yeelink_upload_counter += 1
        
            # response = conn.getresponse()
            # string = response.read()
            # print string
        except httplib.BadStatusLine:
            continue
        except (httplib.HTTPException, socket.error) as ex:
            # In case the domain is not reacheable, API server is down
            # Because of 10 seconds sample rate, after 6s + 4s, serial port will read the next value
            time.sleep(6) 
            continue
        conn_emoncms.close()
