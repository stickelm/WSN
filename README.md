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
