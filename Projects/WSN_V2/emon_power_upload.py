#! /usr/bin/python
#
# How to decode the RFM12B packet
# http://jeelabs.org/2010/12/07/binary-packet-decoding/
# 
# Structure of Emon RFM12B packet (each value is 2 bytes. e.g. 32 2 => 2*256+32=544W power
# typedef struct {
#	 int power1, power2, power3, power4, Vrms, temperature; 
# } PayloadTX;
    
import sys, string
import serial, re, time
import httplib, socket, json

emon_nodeid = 10
API_KEY = "API_KEY"

#Initialization of serial port to read the sensor reading
ser = serial.Serial('/dev/ttyAMA0', 9600)

def emoncms_upload(data_list,time_st):
    try:
        # Single upload Example:
        # http://emoncms.org/node/set.json?nodeid=10&data=20,20,20,20&apikey=API_KEY
        conn_emoncms = httplib.HTTPConnection("emoncms.org")
        conn_emoncms.request("GET", "/node/set.json?nodeid="+str(emon_nodeid)+"&data="+",".join(data_list)+"&apikey="+API_KEY)
        r = conn_emoncms.getresponse()
        string = r.read()
        print string
    except httplib.BadStatusLine:
        print "Emoncms Httplib Badstatus "+time_st
        pass
    except (httplib.HTTPException, socket.error) as ex:
        # In case the domain is not reacheable, API server is down
        print "Emoncms socket error "+time_st
        pass

    if not conn_emoncms.close():
        conn_emoncms.close()
        
while True:
    results = ser.readline().split()
    results.pop(0)
    print results
    # Measge Format in the RFM12B packet via serial port:
    # 10 32 2 10 2 131 2 0 0 0 0 0 0
    # 10 31 2 6 2 129 2 0 0 0 0 0 0
    # 10 34 2 10 2 133 2 0 0 0 0 0 0
    # rfm12_data_list = map(int, ser.readline().split())
    # print rfm12_data_list
    # power1 = rfm12_data_list[1] + rfm12_data_list[2]*256
    # power2 = rfm12_data_list[3] + rfm12_data_list[4]*256 
    # power3 = rfm12_data_list[5] + rfm12_data_list[6]*256
    # power4 = rfm12_data_list[7] + rfm12_data_list[8]*256
    # vrms = rfm12_data_list[9] + rfm12_data_list[10]*256
    # temp = rfm12_data_list[11] + rfm12_data_list[12]*256
    # results = [power1,power2,power3,power4,vrms,temp]
    # print results
    time_stamp = time.strftime("%Y-%m-%d") + "T" + time.strftime("%H:%M:%S")
    emoncms_upload(results,time_stamp)
