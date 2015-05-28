# Internet Of Things Platform #

First, IoT (Internet Of Things) can be narrowed down to 4 tiers:



## 4 Tiers of IoT Application ##

1. **Edge Devices**
> The starting point for Internet of Things applications are the things themselves. These edge devices typically have no screen (although that's not always the case), a low-power processor, some sort of embedded operating system and a way of communicating (usually wirelessly) using one or more communication protocols. The things may connect directly to the Internet, to neighboring things or to an Internet gateway device.


2.  **Ingestion Tier**
> The next tier of the system, an **ingestion tier**, is software and infrastructure that runs in a corporate data center or in the cloud and receives and organizes the streams of data coming from the things. Software running in the ingestion tier is usually also responsible for managing things and updating their firmware when necessary.


3. **Analytics Tier**
> this takes the organized data and processes it.

4. **End-User Tier**
> the application that the end user actually sees and interacts with. This may be an enterprise application, a Web app or, perhaps, a mobile app.



To build an IoT Application, it will mostly happen on Tier 3 and 4.

To build an IoT Platform, it will mostly happen on Tier 2.

To build an IoT device, it will mostly happen on Tier 1.



## Principles of IoT Platform ##

To build or choose an IoT platform, we must adhere to the below principles:

* Follow the Open Standards/Protocols
* Abstract lower layer and provide flexibility
* Security Implementation
* Restful APIs/SOA Architecture
* Data Intensive and Real Time (user case dependant)
* Grow and Scale Up
* Do Not Reinvent The Wheels
* Easy Integration with Enterprise Systems or Other Systems



## IoT Platform Example ##

The gateway devices and the servers will be the 2nd Tier (**Ingestion Layer**) of IoT and they can be powered by the below components and services:
(these are chosen because they followed the above principles)

* Smart Gateway
  * Software
    * Kura (Java)
    * Node.js (Javascript)
  * Hardware
    * Raspberry Pi
      * WiFi/LAN
      * Zigbee/BLE/Other Radio Technologies
      * GPIO (Sensors/Actuators)
    * Arduino or its variants
      * LAN (Arudino YUN)
      * WiFi (WiFi Shield)
* Server/Cloud
  * Pub/Sub MQTT Broker
    * mosca-mqtt (node.js)
    * mosquitto (eclipse)
    * Authentication/Authorization
  * Database
    * Document DB (MongoDB)
    * Time Series DB (InfluxDB)
    * Memeory Like DB (Redis)
  * Web Backend
    * Node.js (APIs)
    * Socket.io (Web Socket)
    * Node-Red (Data Flow/Control)
    * Authentication/Authorization
  * Web Frontend (Admin GUI/Dashboard)
    * Angular Js
    * Bootstrap
    * PowerBI
    * Freeboard
  * Dev/Ops
    * Docker
    * Git
    * Cloud Services (Heroku, Digital Ocean, Microsoft Azure Storage)
    * External APIs


Currently there are many IoT Platform providers with different configurations and setups to fullfill the various needs of connecting things to the internet and doing the data storage/analysis. Big companies like Apple, Samsung and Google are designing and building their own standards/protocols, including some successfully launched new products in the hope that the market will adopt those standards.

### Open Source IoT Platform ###

Open sourced IoT solutions are easy to deploy on premise and organizations and companies can have a good control of their own data, hence increase the sense of security. However, the open source project vendor may not stick around if the project failed.

[Kaa IoT Platform](http://www.kaaproject.org/)
> Kaa is a highly flexible open-source platform for building, managing, and integrating connected software in the Internet of Everything. Kaa introduces standardized methods for achieving integration and interoperation across connected products. On top of this, Kaa’s powerful back-end functionality greatly speeds up product development, allowing vendors to concentrate on maximizing their product’s unique value to the consumer.

> The Kaa platform is comprised of the server component and endpoint SDK that integrates with client applications. For more information on the Kaa server architecture, please refer to the [Design reference](https://docs.kaaproject.org/display/KAA/Design+reference)


[ThingSpeak@Github](https://github.com/iobridge/ThingSpeak)
> ThingSpeak is an open source Internet of Things application and API to store and retrieve data from things using HTTP over the Internet or via a Local Area Network. With ThingSpeak, you can create sensor logging applications, location tracking applications, and a social network of things with status updates.
https://thingspeak.com 


[Zetta@Github](https://github.com/zettajs/zetta)
> Zetta® is an open source platform built on Node.js for creating Internet of Things servers that run across geo-distributed computers and the cloud. Zetta combines REST APIs, WebSockets and reactive programming – perfect for assembling many devices into data-intensive, real-time applications. http://zettajs.org


[Meshblu](https://github.com/octoblu/meshblu)
> Meshblu is an open source machine-to-machine instant messaging network and API. Our API is available on HTTP REST, realtime Web Sockets via RPC (remote procedure calls), MQTT, and CoAP. We seamlessly bridge all of these protocols. For instance, an MQTT device can communicate with any CoAP or HTTP or WebSocket connected device on Meshblu.

> Meshblu auto-assigns 36 character UUIDs and secret tokens to each registered device connected to the network. These device "credentials" are used to authenticate with Meshblu and maintain your device's JSON description in the device directory.

> Meshblu allows you to discover/query devices such as drones, hue light bulbs, weemos, insteons, raspberry pis, arduinos, server nodes, etc. that meet your criteria and send IM messages to 1 or all devices.

> You can also subscribe to messages being sent to/from devices and their sensor activities.

> Meshblu offers a Node.JS NPM module called Meshblu and a meshblu.js file for simplifying Node.JS and mobile/client-side connectivity to Meshblu. http://developer.octoblu.com


[Connect tiny devices to Microsoft Azure services to build IoT solutions](https://github.com/msopentech/connectthedots)
> [Monitoring sensors with Power BI](http://blogs.microsoft.com/iot/2015/05/05/monitoring-sensors-with-power-bi/)



### Non-Open Source ###

Companies or organizations wish to host their own IoT cloud (on premise cloud) may need to pay a very high cost compared to open source solutions.


[IBM Internet of Things Foundation (IoT Foundation)](https://internetofthings.ibmcloud.com/)
> A fully managed, cloud-hosted service that is designed to simplify and derive the value from your IoT devices. Check the capabilities list to see the key features. 

>Internet of Things Foundation is available through Bluemix and the IBM Marketplace.


[ThingWorx](http://www.thingworx.com/)
> The company employs one developer to write connectors in JavaScript that allow new sensors to communicate with the ThingWorx platform as manufacturers develop them. This code resides in ThingWorx. "Customer come to us and say they want to use a particular sensor with our solution," Donny says. "We form a relationship with the vendor and get [its] API, which is often poorly documented, and build a connector to ThingWorx for that product."



### Conclusion ###

To build an IoT platform from scratch is tedious and prone to error.

Depend on the user case, hosting an open sourced platform on premise for the sensitive corporate data and business flow makes sense. While the platform is open sourced, developer can modify the code to suit the organization's needs easily.

On the other hand, the IoT platform public cloud approach which manage and handle non-critical business operations appears to be cost efficient and leads to short development time. 

In the big organization, this hybrid approach towards IoT platform will be the most ideal solution.

Based on the comparison among those open source IoT solutions, we decide to give **Meshblu** a try and tweak/customised it along the way.



## Function and ToDo List ##

###Server###
* Backend
  * Create custom POSTs and GETs to access other APIs or webpages and retrieve data
  * Write your own code for custom data analysis
  * Perform actions when conditions are met by the data in your channels (e.g. send twitter message, turn off light)
* Frontend
  * Write code to generate graphs and plots with multiple time series
  * Write code to create custom visualizations for your channel data
  * Use Google Gauge API for data visualization

### API ###
* Authentication (token based)
* Device Permission
  * Discover
  * Send Msg
  * Receive Msg
  * Config(Update)
* Network Status (GET /status)
* List Devcies (GET /devices)
* List Device By ID (GET /devices/UUID)
* Device Registration (POST /device)
* Update Device By ID (PUT /device/UUID)
* Delete Device By ID (DELETE /device/UUID)

More APIs please refer to [Meshblu APIs](https://github.com/octoblu/meshblu)


...and many more...
