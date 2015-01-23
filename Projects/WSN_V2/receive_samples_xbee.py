#! /usr/bin/python

"""
receive_samples_xbee.py

This example continuously reads the serial port and processes IO data
received from a remote XBee.

Reference: http://www.brettdangerfield.com/post/raspberrypi_tempature_monitor_project/

{'rssi': ',', 'source_addr': '\x00\x05', 'id': 'rx_io_data', 'samples': [{'adc-0': 739, 'adc-1': 240}], 'options': '\x00'}
{'rssi': '1', 'source_addr': '\x00\x05', 'id': 'rx_io_data', 'samples': [{'adc-0': 744, 'adc-1': 241}], 'options': '\x00'}
{'rssi': '-', 'source_addr': '\x00\x05', 'id': 'rx_io_data', 'samples': [{'adc-0': 742, 'adc-1': 240}], 'options': '\x00'}

"""

import serial, time, datetime, sys
from xbee import XBee
import time

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

"""
#save the reading to the sqllite db
def save_temp_reading (zonestr, temp):
    # I used triple quotes so that I could break this string into
    # two lines for formatting purposes
    curs.execute("INSERT INTO tempature_log values( (?), (?), (?) )", (int(time.time()), zonestr,temp))

    # commit the changes
    conn.commit()
"""

SERIAL_PORT = "/dev/ttyAMA0"    # the com/serial port the XBee is connected to
BAUD_RATE = 57600      # the baud rate we talk to the xbee
TEMPSENSE = 1       # which XBee ADC has current draw data
ROOM = "Room1"  # for now, when we add a second unit we will change this

# Open serial port
ser = serial.Serial(SERIAL_PORT, BAUD_RATE)

# Create API object
xbee = XBee(ser)

print 'Starting Up Temperature/Humidity Monitor'
# Continuously read and print packets
while True:
    try:
        response = xbee.wait_read_frame()
        #print response
        temperature = get_temperature(response['samples'], format="C")
        humidity = get_humidity(response['samples'])
        #print our timestamp and temperature to standard_out
		time_stamp = time.strftime("%Y-%m-%d") + "T" + time.strftime("%H:%M:%S")
        print "{0}, {1}, {2}".format(time_stamp, temperature, humidity)

        #save the temperature to the database
        #save_temp_reading(ROOM, tempature)

    except KeyboardInterrupt:
        break

ser.close()
