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
