# IoT Device #

We will only discuss design and build for the **power constrained** and **limited memory** IoT devices for smart building/home scenario here.


The devices/sensors should be able to monitor some basic environmental parameters such as lighting condition, temperate/humidity and also can measure building energy and people movements etc. 


A device become IoT ready need to have the **Wireless Connections** either directly connect to the Internet or to the nearby Internet gateway device.


Some of the device may run on the battery hence require low-power solutions to interface with Non-IP wireless network or directly connect to the network using IP protocols.


Lastly, the device must be "developer friendly", have public available SDK or IDE and very strong community or vendor support and we prefer the device originated from an open source project.


## List of IoT Devices ##

Let's look what are the available devices or components fulfilled the above requirements so we can use them as IoT devices or if not, how to build one to suit your own needs.


[TI Sensor Tag](http://www.ti.com/ww/en/wireless_connectivity/sensortag2015/)
> The CC2650 is a wireless MCU targeting Bluetooth Smart, ZigBee and 6LoWPAN, and ZigBee RF4CE remote control applications.

> The device is a member of the CC26xx family of cost-effective, ultra-low power, 2.4-GHz RF devices. Very low active RF and MCU current, and low-power mode current consumption provides excellent battery lifetime and allows operation on small coin cell batteries and in energy-harvesting applications.

* Multiple Wireless Protocol: 802.15.4/Zigbee, 6LoWPAN, BLE, WiFi
* Ultra Low Power (Run on coin cell battery for 2 years)
* Multiple on-board sensors
* Software Support/Download from TI website


[Libelium Waspmote](http://www.libelium.com/)
> Waspmote is an open source wireless sensor platform specially focused on the implementation of low consumption modes to allow the sensor nodes ( "motes" ) to be completely autonomous and battery powered, offering a variable lifetime between 1 and 5 years depending on the duty cycle and the radio used.

* Low power
* 70 Sensors
* 15 Radio Technology
* IDE/SDK download from website


[Arudino](http://www.arduino.cc/)
> Arduino is a tool for making computers that can sense and control more of the physical world than your desktop computer. It's an open-source physical computing platform based on a simple microcontroller board, and a development environment for writing software for the board.

* Open source
* Need customization
* Strong community support
* MCU only, need extra shield to use wireless


[Many More...](http://postscapes.com/internet-of-things-hardware)


For the easy of use, Libelium Waspmote will be suitable to quickly deploy on-site and easy to programming however it is an expensive solution based on the fact that we need to deploy across a wide area. 


TI sensor tag is very promising since it is tiny and cheap but it is not possible to add extra sensors and make it limited usage.


For the versatile usages, Arduino can be the best solution as it supports so many modules and codes can be easily found online. However it is more suitable to build a prototype, not a commercial product.

We also noticed there are many DIY/Crowd-sourcing hardware projects based on Arduino and many people choose to build their own customized IoT devices after prototyping stage. [Emon TX](http://wiki.openenergymonitor.org/index.php/EmonTx_V3.4) from [Open Energy Monitor](http://openenergymonitor.org/emon/) is a very good example.


Given the above considerations, we decide to use Arduino to do the prototyping and make our own IoT device after successful prototype stage.



## Function and ToDo List ##

### Wireless ###
* 802.15.4 (Point to Point)
* BLE (Point to Point)
* ZigBee (Mesh)
* LoRa (Long Distance)


### Firmware ###
* Schedule sending data/MQTT Client Publish
* Requests (e.g. sending messages or commands, run code) at predetermined times or based on event/message
* Add queued commands for your device to act upon


... and many more ...
