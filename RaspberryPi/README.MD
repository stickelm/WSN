# optional install
# dyndns ddclient installed and configured
# sudo apt-get install vim

sudo apt-get install python-mysqldb

sudo apt-get install librxtx-java
# This command will install the raspberry pi compatible librxtxSerial.so inside /usr/lib/jni and a RXTXcomm.jar inside /usr/share/java/RXTXcomm.jar
# When you run your application you need to tell java that it needs to use /usr/lib/jni as the java.library.path by passing the java command line option -Djava.library.path=/usr/lib/jni


### OTAP SETUP

# copy OTAP program zip file over and unzip

# xbee.conf file in the OTAP directory
port = /dev/ttyUSB0
auth_key = LIBELIUM
panID = 0x3332
xbeeModel = 802.15.4
channel = 0x0C
encryption = off
encryptionKey = 1234567890123456
# name of the file where discarded data goes
#discardedDataFile = data.txt
# Waspmote version
WaspmoteVersion = 12

vim otap # change to the below code
java -Djava.library.path=/usr/lib/jni/ -jar otap.jar $@

chmod a+x otap
./otap -scan_nodes --mode BROADCAST


### Waspmote Code

# Change below lines for each sensor ID
define id_mote "WASPMOTE00000A09"
...
frame.createFrame(ASCII, "A09");
...
frame.addSensor(SENSOR_STR, "#NID:A09");
...

# Optional: Change OTAP window time slot, change the number
for(i=0; i<250; i++)

# Compile the code and find hex file at 'C:\Users\user\Documents\Waspmote\OTA-FILES' directory (you can change the Hex file name), copy to otap directory on the pi.
.\pscp.exe file.hex pi@domain.com:/home/pi/otap/


# Get boot list from remote sensor, first scan for the available sensor and mac address
./otap -scan_node --mode BROADCAST
./otap -get_boot_list --mode UNICAST --mac "remote_sensor_mac"

# Send File & Delete File
./otap -send --mode UNICAST --mac "remote_sensor_mac" --pid "7_digit_pid_name" --file "file_name.hex"
./otap -delete_program --mode UNICAST --mac "remote_sensor_mac" --pid "7_digit_pid_name"

# Run File


### Python Parse and Update to MySQL DB

vim python_parse

#!/bin/bash
#
nohup python /mnt/user/sensorparser_python/main.py >/dev/null 2>&1 &

chmod a+x python_parse
