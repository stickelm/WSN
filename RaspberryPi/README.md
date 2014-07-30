## Package Installation On Raspberry Pi

* install python mysql module `sudo apt-get install python-mysqldb`
* install minicom serial port tool `sudo apt-get install minicom`
* install serial port python module `sudo apt-get install librxtx-java`

    This command will install the raspberry pi compatible `librxtxSerial.so` inside `/usr/lib/jni` and a `RXTXcomm.jar` inside `/usr/share/java/RXTXcomm.jar`.

    When you run your application you need to tell java that it needs to use `/usr/lib/jni` as the `java.library.path` by passing the java command line option `-Djava.library.path=/usr/lib/jni`.

Other optional package installation on pi:

* install dyndns ddclient and configurartion
* install vim


## OTAP SETUP

Download OTAP program zip file from Libelium website and unzip the file.

The example configuration of the `xbee.conf` file in the OTAP directory:

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

Change the `otap` script to the below code to include the raspberry pi compatible `librxtxSerial.so` library:
 
    vim otap
    java -Djava.library.path=/usr/lib/jni/ -jar otap.jar $@

Now you can run the `otap` command:

    chmod a+x otap
    ./otap -scan_nodes --mode BROADCAST

Get boot list from remote waspmote, first scan for the available waspmote and obtain their mac addresses:

    ./otap -scan_nodes --mode BROADCAST
    
Then obtain the program list on that waspmote:

    ./otap -get_boot_list --mode UNICAST --mac "remote_sensor_mac"

Send File & Delete File:

    ./otap -send --mode UNICAST --mac "remote_sensor_mac" --pid "7_digit_pid_name" --file "file_name.hex"
    ./otap -delete_program --mode UNICAST --mac "remote_sensor_mac" --pid "7_digit_pid_name"

[Scripts](https://github.com/xianlin/WSN/blob/master/RaspberryPi/otap_del.sh) automatically scan and delete all programs on all scanned waspmote


## Waspmote Code

Change the [waspmote example code](https://github.com/xianlin/WSN/blob/master/Waspmote/default_waspmote_v1.2.pde) as per your waspmote ID and OTAP window length:

    
    define id_mote "WASPMOTE00000A09"
    ...
    frame.createFrame(ASCII, "A09");
    ...
    frame.addSensor(SENSOR_STR, "#NID:A09");
    ...
    # Optional: Change OTAP window time slot, change the number
    for(i=0; i<250; i++)

In case you want to check the serial number of the XBee connnected to the pi, you can use `minicom` program to enter the [XBee command mode](http://www.digi.com/support/kbase/kbaseresultdetl?id=2205) and issue [AT commands](http://examples.digi.com/wp-content/uploads/2012/07/XBee_ZB_ZigBee_AT_Commands.pdf):

    ATSH
    15A300
    ATSL
    579256E9

Compile the code and find the compiled HEX file at `C:\Users\user\Documents\Waspmote\OTA-FILES` directory (you can change the Hex file name), copy to otap directory on the pi using `pscp.exe`:
    
    .\pscp.exe file.hex pi@domain.com:/home/pi/otap/


## Python Parse and Update to MySQL DB

    vim python_parse

    #!/bin/bash
    #
    nohup python /mnt/user/sensorparser_python/main.py >/dev/null 2>&1 &

    chmod a+x python_parse
