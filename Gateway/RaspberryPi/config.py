#Save sensor data locally?
saveLocal = False
#Send sensor data to the MQTTBroker?
sendToCloud = True
#Send the collected sensor data to the broker after a reconnect?
sendAfterReconnect = True

#Settings for the mqtt client
mqttSettings = dict(
    #Change this to something that identifies your Client
    clientID    = 'd:jn5jlc:iotsample-raspberrypi:b827ebb548f2',
    server      = 'jn5jlc.messaging.internetofthings.ibmcloud.com',
    port        = 1883,
    publishTopic    = 'iot-2/evt/status/fmt/json',
)


# Interval in which values should be stored or send
# Don't set this under 30
updateInterval = 30
