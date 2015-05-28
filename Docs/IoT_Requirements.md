##IoT Device/Gateway Requirements

* Power constrained and limited memory
* Multiple low power wireless protocol support
* Internet connection is not a must (need a gateway)
* Long distance communciation capablitiy
* Using open standards
* smart building/home application scenario

A device become IoT ready need to have the **Wireless Connections** either directly connect to the Internet or to the nearby Internet gateway device.

Some of the device may run on the **battery** hence require low-power solutions to interface with Non-IP wireless network or directly connect to the network using IP protocols.

Lastly, the device must be **developer friendly**, have public available SDK or IDE and very strong community or vendor support and we prefer the device originated from an open source project.


### Measurement Device
The devices should be able to measure the parameters such as:

* Environmental
	* lighting condition
	* temperate/humidity
	* audio level/noise
* Energy
	* Electrical power meter
	* Water flow meter
* People
	* Movements
	* Presence (PIR)
	* Crowd

#### Type of sensors

* Cameras
* Temperature/Humidity/Light
* Audio
* Electrical Current/Water Flow
* PIR/Motion
* more...

To be edit:
Place to put: Celing, Table, 
number of sensors: < 20
Number of Gateway: 2
Layout of the sensors + gateway (drawing)



### Actuator Device
The devices should be able to control various appliances such as:

* Lighting (On/Off, Dark/Light)
* Air-condition
* Door access (lock)
* Speaker/Projector (alert or play music/video)
* many more...

wall switch (?)

### Gateway Device
The devices should be able to handle import tasks such as:

* Routing (2 way routing) and upload/download data
* Display and local management (Dashboard/Control Panel)
* Cache data (when lost internet connection)
* Remote management
	*  It can be managed remotely via a web interface, web API or SSH command line interface. For scripting and automation, all parameters can also be set from a standard Linux shell and access
to all configuration interfaces is controllable by LDAP authentication.
* Security
	* It can be configured through the web and command line interfaces. MAC address whitelisting and blacklisting can be used to accept or deny access to known end devices (for 6Lowpan).
* Logging
	* A rich logging set enables easy diagnosis of a variety of connection issues including access failure, host lookup failures and packet drops. Logging can be configured to store them locally on the gateway or streamed to a server.


##IoT Platform Requirements

Connectivity that is **fast**, **secure** and **salable**. 

* It simply connects millions of devices at scale, so you can easily control access for every person, application, or thing trying to access your IoT data and plug directly into cloud-based services for your daily workflows.

Manage the IoT data

* **Capturing**, **managing** and **interpreting** your connected device. It should provide standard methods for defining and managing connected device users and their data.

Device support

* IoT enabled devices are making support more **efficient** and **instant** than ever before.
* It provides a single interface for your real-time device deployment as well as device usage and health, so you can remotely access and fix connected devices in real-time.



##IoT Application Requirements

While developing an application, there are some important features we want to include:

* Customer profiling
	* How many customers uses your app (or functions of your app).
	* Get better insight by seeing what your customers are doing on a granular level. Find out which of your features a particular customer uses most.
* Real time information display
	* Real-time feeds of user actions or device events with detailed profile as they happen. Don't wait for your data. It could be too late to act on by then.
* Customer/Event retention matrics
	* How often are your customers coming back? Do they come back after 1 day? 1 week? 1 month?
	* How often does an event happen? Does it happend every week? every day? every hour?
* Build detailed queries to your data
	* Data analysis shouldn't just be for your technical staff. Build queries with advanced filters and aggregate operations without prior technical knowledge.