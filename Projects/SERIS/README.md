## SERIS Project

### About

This project is to design a wireless sensor with sun solar irradiance and ambient teperature reading. The device will be placed on the roof top and transimit the measurement data to a remote base station inside a building about 300 meters away.

This project is part of the [NUS SERIS Solar Irradiance Map Project](http://www.solar-repository.sg/irr-map.cfm).

### Components

* Solar Irradiance Sensor ([Si-02-Pt100](http://www.imt-solar.com/products/solar-irradiance-sensor/si-sensor.html))

Wirings of the sensor:

 Color | Description
--- | --- | ---
Orange | 55, 3 mV/(1000W/qm)
Yellow, Red | PT100
Green, Brown | PT100
Black | 0 VDC

* [Libelium Waspmote V1.2](http://www.libelium.com/products/waspmote/)

* Digi XBee-PRO 900HP (900 MHz RF Module  [XBP9B-DPST-041](http://www.digi.com/products/wireless-wired-embedded-solutions/zigbee-rf-modules/point-multipoint-rfmodules/xbee-pro-900hp#overview))

* Various Electronic Components (BOM)
    * 100k, 2k resistors (0.1%)
    * LM324N IC (DIP14)
    * 0.1uF Ceramic Capacitor
    * MAX31865 IC RTD Temperature Digital Converter Board
    * Pinhead and Female pinheader connector
    * [Screw terminal blocks](https://www.sparkfun.com/products/10571) (2-pin, 2.54mm pitch)
    * Double side prototype board (5cm x 7cm)


## Circuit Design

It is based on the code and circuit in the folder [Solar Irradiance Sensor](https://github.com/xianlin/WSN/tree/master/Sensors/Solar%20Irradiance) and [RTD Temperature Sensor](https://github.com/xianlin/WSN/tree/master/Sensors/Temperature). The prototype board/shield can be directly plugged onto the Libelium waspmote device.


## Software


### Xbee Frame

XBee received frame details
```
Receive Packet (API 2)

7E 00 4C 90 00 7D 33 A2 00 40 C3 69 3F FF FE 41 3C 3D 3E 80 04 23 33 38 37 32 33 35 31 36 34 23 4E 30 31 23 31 35 33 23 54 49 4D 45 3A 31 34 2D 34 39 2D 33 33 23 42 41 54 3A 36 39 23 54 43 41 3A 33 35 2E 39 31 23 50 41 52 3A 38 2E 36 35 23 E4

    - Start delimiter: 7E
    - Length: 00 4C (76)
    - Frame type: 90 (Receive Packet)
    - 64-bit source address: 00 13 A2 00 40 C3 69 3F
    - 16-bit source address: FF FE
    - Receive options: 41
    - Received data: 3C 3D 3E 80 04 23 33 38 37 32 33 35 31 36 34 23 4E 30 31 23 31 35 33 23 54 49 4D 45 3A 31 34 2D 34 39 2D 33 33 23 42 41 54 3A 36 39 23 54 43 41 3A 33 35 2E 39 31 23 50 41 52 3A 38 2E 36 35 23
    - Checksum: E4
```

Received data convert from Hex to ASCII
```
    <=>#387235164#N01#153#TIME:14-49-33#BAT:69#TCA:35.91#PAR:8.65#
```

[Python scripts](https://github.com/xianlin/WSN/blob/master/Projects/SERIS/pylink.py) read serial port and upload data to the cloud

```
# run script at background when restart raspberry pi
sudo vi /etc/rc.local
nohup python /home/pi/scripts/python/pylink.py >/tmp/output.txt 2>&1 &
```
