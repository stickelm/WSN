## General Setup

To achieve successful OTAP , there are some requirements/preparations need to be done:

Using X-CTU software to configure the XBee for the Gateway:

* Load the default firmware settings, change the `PANID`, `Channel` and other parameters if necessary.

* Baud Rate: 38400 

	This is the speed at which the java program `otap.jar` talk to the serial-USB port on your PC. On your computer, the USB to serial port baud rate configuration has to be set to `38400`, use windows device manager to change it if you are using Windows.

* API Mode: `1` 

	Easy to use, but doesn't provide the added reliability of using the `AP=2`, escape character sequence, same here, the java program didn't use the AP=2 mode so you have to set this to `AP=1`.

In the OTAP program directory, find the `XBee.conf` configuration file and edit. The content need to be set to the same `PAN ID`, `Channel` etc as both the XBee USB Dongle and Waspmote XBee module. 

Example of `XBee.conf`:

	# port where the xbee moduel is connected
	port = COM6
	# auth key of network
	auth_key = LIBELIUM
	# pan ID of network
	panID = 0x3332
	# xbee model
	xbeeModel = 802.15.4
	# channel number
	channel = 0x0C
	# encryption of network
	encryption = off
	# encryption key of network
	encryptionKey = 1234567890123456
	# name of the file where discarded data goes
	#discardedDataFile = data.txt
	# Waspmote version
	WaspmoteVersion = 12
	
The Baud rate also need to be set correctly according to your OS (Windows/Linux).

Take out the XBee module on the Waspmote and use X-CTU to set the below parameters:

* PANID, CH and other parameters set to the same as the Gateway Xbee;
* Baud Rate: `115200` (NOT `38400` because this Xbee is talking to MCU on the Waspmote at `115200` bps)
* AP=`2` (API mode is `2` as it supports escape character sequence)

Now put the XBee module back to the Waspmote and try the example code provided by Libelium under OTAP section.

Connect the Waspmote with battery and turn on the switch, connect the USB dongle to your PC USB port and under windows command line, type OTAP command:

    .\otap -scan_nodes --mode BROADCAST

You should be able to see your Waspmote node ID appeared.


## Raspberry PI

If you want to run `otap.jar` on Raspberry Pi, you need to install serial port Java module `sudo apt-get install librxtx-java`.

This command will install the raspberry pi compatible `librxtxSerial.so` inside `/usr/lib/jni` and a `RXTXcomm.jar` inside `/usr/share/java/RXTXcomm.jar`.

When you run your application you need to tell java that it needs to use `/usr/lib/jni` as the `java.library.path` by passing the java command line option `-Djava.library.path=/usr/lib/jni`.

Change the `otap` script to the below code to include the raspberry pi compatible `librxtxSerial.so` library:
 
    vim otap
    java -Djava.library.path=/usr/lib/jni/ -jar otap.jar $@



