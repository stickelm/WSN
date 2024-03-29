#! /usr/bin/python

"""
receive_samples_xbee.py
This example continuously reads the serial port and processes IO data received from a remote XBee and upload the data to the cloud

Reference: http://www.brettdangerfield.com/post/raspberrypi_tempature_monitor_project/

{'rssi': ',', 'source_addr': '\x00\x05', 'id': 'rx_io_data', 'samples': [{'adc-0': 739, 'adc-1': 240}], 'options': '\x00'}
{'rssi': '1', 'source_addr': '\x00\x05', 'id': 'rx_io_data', 'samples': [{'adc-0': 744, 'adc-1': 241}], 'options': '\x00'}
{'rssi': '-', 'source_addr': '\x00\x05', 'id': 'rx_io_data', 'samples': [{'adc-0': 742, 'adc-1': 240}], 'options': '\x00'}

lewei50 website API information:
http://www.lewei50.com/api/v1/gateway/updatesensors/你的网关号
		
URL：http://www.lewei50.com/api/V1/gateway/UpdateSensors/01
Method：post
--Header：userkey:API_KEY
POST Data：
[
	{
		"Name":"1",
		"Value":"30"
	},
	{
		"Name":"2",
		"Value":"60"
	}
]
"""

import serial, time, datetime, sys
from xbee import XBee
import time
import httplib, socket, json

# Get the current temp from a list of voltage readings
def get_humidity(data):
    #iterate over data elements
    readings = []
    for item in data:
        readings.append(item.get('adc-0'))

    #start by averaging the data
    volt_average = sum(readings)/float(len(readings))

    #now calculate the proper mv
    #we are using a 3.3v usb explorer so the formula is slightly different
    humidity = (volt_average*3300/1023 - 500)/31

    return humidity


# Get the current temp from a list of voltage readings
def get_temperature(data, format="C"):
    #iterate over data elements
    readings = []
    for item in data:
        readings.append(item.get('adc-1'))

    #start by averaging the data
    volt_average = sum(readings)/float(len(readings))

    #now calculate the proper mv
    #we are using a 3.3v usb explorer so the formula is slightly different
    temperature = ((volt_average*3.297) - 500) / 10.0    

    if format=="F":
        #convert to farenheit
        temperature = (temperature * 1.8) + 32

    return temperature

	
#save the reading to lewei50 website
def save_reading_lewei50(time_st, temp, hum, API_KEY):
    try:
	headers_lewei50 = {"userkey":API_KEY}
	data = [{"Name":"1","Value":str(temp)},{"Name":"2","Value":str(hum)}]
        conn_lewei50 = httplib.HTTPConnection("www.lewei50.com")
        conn_lewei50.request("POST", "/api/V1/gateway/UpdateSensors/01", json.dumps(data), headers=headers_lewei50)
        #r = conn_lewei50.getresponse()
        #string = r.read()
        #print string
    except httplib.BadStatusLine:
        print "Httplib Badstatus "+time_st
        pass
    except (httplib.HTTPException, socket.error) as ex:
        # In case the domain is not reacheable, API server is down
        print "Socket error "+time_st
        pass

    if not conn_lewei50.close():
        conn_lewei50.close()


SERIAL_PORT = "/dev/ttyAMA0"    # the com/serial port the XBee is connected to
BAUD_RATE = 57600      # the baud rate we talk to the xbee
API_KEY = ""

# Open serial port
ser = serial.Serial(SERIAL_PORT, BAUD_RATE)

# Create API object
xbee = XBee(ser)

f = open('/tmp/output', 'w')

print "Starting Up Temperature/Humidity Monitor"
# Continuously read and print packets
while True:
    try:
        response = xbee.wait_read_frame()
        #print response
        temperature = get_temperature(response['samples'], format="C")
        humidity = get_humidity(response['samples'])
        #print our timestamp and temperature to standard_out
	time_stamp = time.strftime("%Y-%m-%d") + "T" + time.strftime("%H:%M:%S")
        #print "{0}, {1}, {2}".format(time_stamp, temperature, humidity)
		
        #save the temperature to the lewei50 website
        save_reading_lewei50(time_stamp, temperature, humidity, API_KEY)

    except KeyboardInterrupt:
        break

ser.close()
