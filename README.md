# Libelium WSN

The project consisits of the below components:

* Waspmote Sensor
  * Sensor Box
  * Waspmote V1.1
  * Waspmote V1.2
  * Smart City Board
  * Various Sensors
    * Temperature
    * Luminosity
    * Humidity
    * Battery
    * Audio
  * XBee Module (802.15.4)
  * Waspmote Code (V1.1 and V1.2)

* Meshlium Gateway
  * Java Parsing Program
  * Database
  * ArcGIS Restful API
  * XBee Module (802.15.4)

* XBee USB Dongle
  * OTAP

More details about Libelium Products please go to [Libelium Website](http://www.libelium.com/)


## Waspmote Sensor

### Sensor Box

### Waspmote V1.1

### Waspmote V1.2
There is a [difference](http://www.libelium.com/forum/viewtopic.php?f=23&t=9729) between V1.2 and V1.1

### Smart City Board

### Event Board

### Various Sensors
* Temperature
* Luminosity
* Humidity
* Battery
* Audio
The audio sensor reading need to calibrate with [an array of 11 coefficient values](https://github.com/xianlin/WSN/blob/master/Waspmote/audio_coefs) to produce relatively accurate measurement results in unit of dB. 

The calibration method is done by writing the 11 coefficient values into MCU EEPROM memory at the address of #164. The detailed [program code](https://github.com/xianlin/WSN/blob/master/Waspmote/load_calibration_coefficients_for_audio.pde) will achieve the value writing and loading task.

### XBee Module (802.15.4)

### Waspmote Code  (V1.1 and V1.2)
The [V1.2 Code](https://github.com/xianlin/WSN/blob/master/Waspmote/default_waspmote_v1.2.pde) and [V1.1 Code]() are different in coding but they have the same function that it is sending the data frames to the Meshlium gateway periodically while having a time window to listen to the OTAP command. The output of the frames (readings on the gateway):

    <=>#382540822#A09#30#STR:senor reading#TCA:25.80#DUST:0.014#MCP:53.#LUM:73.704#HUMA:63.1#BAT:93#
        
## Meshlium Gateway

### Java Parsing Program
The java program [/bin/sensorParser.jar](https://github.com/xianlin/WSN/blob/master/Meshlium/sensorParser.jar) automatically capture the readings from serial port `/dev/ttyS0` with which the the XBee module is connected, it then parse the readings (sensor IDs and measurements etc.) into database table `MeshliumDB.sensorParser`. 

To start the java parsing program and run it in the background:

    remountrw
    java -jar /bin/sensorParser.jar >/dev/null 2>&1 &

To end the program:
    
    killall java

### DataBase
The [default DB schema file](https://github.com/xianlin/WSN/blob/master/Meshlium/MeshliumDB_3.1.3.sql) shows the detailed default schema with Gateway Firmware Version 3.1.3. The table `sensorParser` contains all the sensor reading data and the table `currentSensors` will only be updated by calling [ArcGIS API]() and it will be filled up with current sensor reading data from `sensorParser` table.

    meshlium:~# mysql -u root -p
    mysql> show databases;
    +--------------------+
    | Database           |
    +--------------------+
    | information_schema |
    | MeshliumDB         |
    | mysql              |
    | tavico             |
    +--------------------+
    mysql> use MeshliumDB;
    mysql> show tables;
    +----------------------+
    | Tables_in_MeshliumDB |
    +----------------------+
    | bluetoothData        |
    | currentSensors       |
    | encryptionData       |
    | gpsData              |
    | meshlium             |
    | sensorParser         |
    | sensors              |
    | tokens               |
    | users                |
    | waspmote             |
    | wifiScan             |
    | zigbeeData           |
    +----------------------+

Some of the sample data are listed here:

    mysql> select * from currentSensors;
    +----------+------------+-----------------------+---------------------+-------------------------------+------------+-------------+---------------+-------+---------------+------------------+------------------+
    | OBJECTID | waspmoteid | name                  | description         | sensorReading                 | sensorType | sensorValue | extendedValue | units | timestamp     | x                | y                |
    +----------+------------+-----------------------+---------------------+-------------------------------+------------+-------------+---------------+-------+---------------+------------------+------------------+
    |       65 |          0 | String_0              | String              | String senor reading N/A      | STR        |           0 | senor reading | N/A   | 1402564304000 | 103.771750156284 | 1.29892731856736 |
    |       12 |          0 | Temperature Celsius_0 | Temperature Celsius | Temperature Celsius 27.41 oC | TCA        |       27.41 |               | ?C    | 1402564304000 | 103.771750156284 | 1.29892731856736 |
    |       29 |          0 | Dust_0                | Dust                | Dust 0.030 mg/m3              | DUST       |        0.03 |               | mg/m3 | 1402564304000 | 103.771750156284 | 1.29892731856736 |
    |       25 |          0 | Microphone_0          | Microphone          | Microphone 71. dBA            | MCP        |          71 |               | dBA   | 1402564304000 | 103.771750156284 | 1.29892731856736 |
    |       22 |          0 | Luminosity_0          | Luminosity          | Luminosity 45.454 Ohms        | LUM        |      45.454 |               | Ohms  | 1402564304000 | 103.771750156284 | 1.29892731856736 |
    |       14 |          0 | Humidity_0            | Humidity            | Humidity 74.1 %RH             | HUMA       |        74.1 |               | %RH   | 1402564304000 | 103.771750156284 | 1.29892731856736 |
    |       52 |          0 | Battery_0             | Battery             | Battery 93 %                  | BAT        |          93 |               | %     | 1402564304000 | 103.771750156284 | 1.29892731856736 |
    +----------+------------+-----------------------+---------------------+-------------------------------+------------+-------------+---------------+-------+---------------+------------------+------------------+

    mysql> select * from sensorParser order by timestamp DESC limit 10;
    +-------+---------+-----------+------------+--------------+--------+---------------+---------------------+------+------+
    | id    | id_wasp | id_secret | frame_type | frame_number | sensor | value         | timestamp           | sync | raw  |
    +-------+---------+-----------+------------+--------------+--------+---------------+---------------------+------+------+
    | 61209 | A03     | 382539418 |        253 |           94 | STR    | senor reading | 2014-06-13 16:38:54 |    0 | NULL |
    | 61210 | A03     | 382539418 |        253 |           94 | TCA    | 28.38         | 2014-06-13 16:38:54 |    0 | NULL |
    | 61211 | A03     | 382539418 |        253 |           94 | DUST   | 0.030         | 2014-06-13 16:38:54 |    0 | NULL |
    | 61212 | A03     | 382539418 |        253 |           94 | MCP    | 60.           | 2014-06-13 16:38:54 |    0 | NULL |
    | 61213 | A03     | 382539418 |        253 |           94 | LUM    | 46.041        | 2014-06-13 16:38:54 |    0 | NULL |
    | 61214 | A03     | 382539418 |        253 |           94 | HUMA   | 87.3          | 2014-06-13 16:38:54 |    0 | NULL |
    | 61215 | A03     | 382539418 |        253 |           94 | BAT    | 93            | 2014-06-13 16:38:54 |    0 | NULL |
    | 61202 | A07     | 382550088 |        253 |           74 | STR    | senor reading | 2014-06-13 16:38:49 |    0 | NULL |
    | 61203 | A07     | 382550088 |        253 |           74 | TCA    | 26.45         | 2014-06-13 16:38:49 |    0 | NULL |
    | 61204 | A07     | 382550088 |        253 |           74 | DUST   | 0.084         | 2014-06-13 16:38:49 |    0 | NULL |
    +-------+---------+-----------+------------+--------------+--------+---------------+---------------------+------+------+

    mysql> select * from meshlium;
    +----------+------------+-----------------------+------------------+------------------+------------------+---------------------+
    | objectid | name       | description           | x                | y                | spatialReference | timestamp           |
    +----------+------------+-----------------------+------------------+------------------+------------------+---------------------+
    |        1 | meshlium-a | Meshlium at Section A | 103.771723334194 | 1.29895949680477 |             4326 | 2014-06-09 17:15:47 |
    +----------+------------+-----------------------+------------------+------------------+------------------+---------------------+

    mysql> select * from waspmote;
    +----------+------+-------------+------------------+------------------+------------------+---------------------+-------------+------------+
    | OBJECTID | name | description | x                | y                | spatialReference | timestamp           | sensorCount | meshliumid |
    +----------+------+-------------+------------------+------------------+------------------+---------------------+-------------+------------+
    |        0 | A03  | A03 Node    | 103.771750156284 | 1.29892731856736 |             4326 | 2014-06-09 17:16:54 |           7 | meshlium-a |
    +----------+------+-------------+------------------+------------------+------------------+---------------------+-------------+------------+
    
    mysql> select * from sensors limit 5;
    +----+---------------------------+---------------------------+----------+---------+-------+
    | id | name                      | description               | id_ascii | units   | value |
    +----+---------------------------+---------------------------+----------+---------+-------+
    |  0 | Carbon Monoxide           | Carbon Monoxide           | CO       | voltage |     2 |
    |  1 | Carbon Dioxide            | Carbon Dioxide            | CO2      | voltage |     2 |
    |  2 | Oxygen                    | Oxygen                    | O2       | voltage |     2 |
    |  3 | Methane                   | Methane                   | CH4      | voltage |     2 |
    |  4 | Liquefied Petroleum Gases | Liquefied Petroleum Gases | LPG      | voltage |     2 |
    +----+---------------------------+---------------------------+----------+---------+-------+

### ArcGIS API
The [php code](https://github.com/xianlin/WSN/tree/master/Meshlium/ESRI-ArcGIS-API) for the restful API is used to integrate the sensor and measured data into ArcGIS map services

### XBee Module (802.15.4)
The configuration parameter is as the below:
        
        sample text

## XBee USB Dongle
Another very useful feature the Libelium product offer is the OTAP (Over the Air Programming). The USB dongle with XBee chip connected to your comuter can send files/commands to the remote waspmote node over the air by using a java program `otap.jar` in command line mode. To acheive successful OTAP via 802.15.4 protocol, there are some requirments/preparations need to be done:

* USB Dongle Gateway

Using X-CTU software to configure the XBee before installing on the USB dongle:

Baud Rate: 38400 

This is the speed at which the java program `otap.jar` talk to the serial-USB port on your PC. On your computer, the USB to serial port baud rate configuration has to be set to `38400`, use windows device manager to change it if you are using Windows.

API Mode: 1 

Easy to use, but doesn't provide the added reliability of using the AP=2, escape character sequence, same here, the java program didn't use the AP=2 mode so you have to set this to AP=1.

Xbee.conf

There is a configuration file `Xbee.conf` in the OTAP software directory. The content need to be set to the same `PAN ID`, `Channel` etc as both the XBee USB Dongle and Waspmote Node XBee module. The `xbeeModel` has to be the same as your Gateway Xbee module firmware reading, in this case it is `802.15.41. You can use X-CTU software to change/update firmware of Xbee if necessary.

* Waspmote Node with XBee Module

Take out the Xbee module on the waspmote and use X-CTU to set the below parameters:
PANID, CH set to the same as the Gateway Xbee;
Firmware: set the same as your USB Dongle, in my case it's 802.15.4.
Baud Rate: 115200 (NOT 38400 because this Xbee is talking to MCU on the waspmote at 115200 bps)
AP=2 (API mode is 2 as it supports escape character sequence)

Now put back the Xbee module onto the waspmote and upload the example code `OTA_03_802`. (in your Waspmote IDE example folder and `OTA` subfolder), you can change the "id_mote" as you want, but don't change the key_access as it must be the same key in `Xbee.conf` file on your PC.

Now connect the waspmote with battery and turn on the switch, connect the USB dongle to your PC USB port and under windows command line, type OTAP command:

    .\otap -scan_nodes --mode BROADCAST

You are Done with OTAP broadcast discovery.




