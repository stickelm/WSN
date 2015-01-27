import paho.mqtt.client as mqtt
import time, datetime
import json
import config
import sys
from sqliteClient import SQLiteClient

#sys.stdout = open('ibm_iot.log', 'w', 1)


##########################
# Needed python libaries:#
# sqllite                #
# paho-mqtt              #
##########################
# Install with :         #
# pip install <lib name> #
##########################

#############################
# Variables to change :     #
# - saveLocal               #
# - sendToCloud             #
# - location                #
# - mqttSettings            #
# - analogSensors           #
#############################

#TODO:
# Add functions for digital and I2C sensors
# If connection lost retry and send bulk information since last send

#Read all sensor values
def readSensors():
    sensorValues["temperature"] = 30.0
    sensorValues["humidity"] = 60
    sensorValues['timestamp'] = datetime.datetime.utcnow().isoformat()
    lastMeasurementTime = datetime.datetime.utcnow().isoformat()

def processData():
    global sensorValues
    if config.saveLocal == True :
        sqliteClient.addValues(sensorValues)
    if config.sendToCloud == True:
        sensorValues = {'d':sensorValues}
        print sensorValues
        mqttClient.publish(config.mqttSettings['publishTopic'], json.dumps(sensorValues), 0)
    sensorValues = dict()

def sendBulkData():
    for valueSet in sqliteClient.getValuesAfter(lastMeasurementTime):
        sensorValues = valueSet
        processData()
    hasDisconnected = False

## MQTT Callbacks
def on_connect(client, userdata, flags, rc):
    if rc != 0:
        print "Connection failed. RC: {}".format(rc)
    else:
        print "Connected successfully"

def on_publish(client, userdata, mid):
    print "Message {} published.".format(mid)

def on_disconnect(client, userdata, rc):
    if rc != 0:
        print "Client disconnected unexpectedly, trying to reconnect."
        hasDisconnected = True
        mqttClient.reconnect()

#Init the sensors
sensorValues = dict()

lastMeasurementTime = 0

if config.sendAfterReconnect == True:
    hasDisconnected = False
if config.saveLocal == True:
    sqliteClient = SQLiteClient()
if config.sendToCloud == True:
    mqttClient = mqtt.Client(config.mqttSettings["clientID"])
    mqttClient.username_pw_set("use-token-auth","j+4bFXpvcnw+J588OD")
    mqttClient.on_connect = on_connect
    mqttClient.on_publish = on_publish
    mqttClient.on_disconnect = on_disconnect
    mqttClient.loop_start()
    mqttClient.connect(config.mqttSettings["server"], config.mqttSettings["port"])



while True:
    readSensors()
    print sensorValues
    processData()
    if config.sendAfterReconnect == True and hasDisconnected == True:
        sendBulkData()
    time.sleep(config.updateInterval * 1)
