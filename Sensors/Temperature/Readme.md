## Temperature Sensor
[List of temperature sensors - Wikipedia](http://en.wikipedia.org/wiki/List_of_temperature_sensors)

### Resistance Thermometer
[Resistance thermometer - Wikipedia](http://en.wikipedia.org/wiki/Resistance_thermometer)

#### PT100 Sensor
By far the most common devices used in industry have a nominal resistance of 100 ohms at 0 °C, and are called Pt100 sensors ('Pt' is the symbol for platinum, 100 for the resistance in ohm at 0 °C). The sensitivity of a standard 100 ohm sensor is a nominal 0.385 ohm/°C. RTDs with a sensitivity of 0.375 and 0.392 ohm/°C as well as a variety of others are also available.

#### How To Measure the PT100 Sensor
Here we will show 2 ways to measure the resistance of the PT100 RTD temperature sensor and convert the reading to the temperature.

##### Amplifier Solution

Based on the [Example of 4x Multiplexed RTD Temperature sensor module](http://openenergymonitor.org/emon/buildingblocks/rtd-temperature-sensing), we modified the Amplifier circuit to get the sensor reading. The [LTspice](https://github.com/xianlin/WSN/tree/master/Sensors/Temperature/LTspice) folder contains the circuit file in LTspice format and the simulation results. 

Based on the results, the linear trend formula is obtained by using excel:
```
Vout = 0.0408 x Resistance(PT100) - 3.08
```


##### IC Solution

The above amplifier circuit will yield errors and the best way to convert RTD resistance to temperature is by using ICs such as [MAX31865](http://www.maximintegrated.com/en/products/analog/sensors-and-sensor-interface/MAX31865.html). 

The MAX31865 PT-100 RTD to Digital Breakout Board can be purchased [online](http://playingwithfusion.com/productview.php?pdid=25) with downloadable schematic and [arduino sketch files](https://github.com/xianlin/WSN/tree/master/Sensors/Temperature/PWFusion_MAX31865). Just copy the sketch into arduino library folder and upload the example sketch onto Arduino Uno.

The connection from arduino to the sensor is called [4-Wired connection](http://en.wikipedia.org/wiki/Resistance_thermometer#Four-wire_configuration). It will give very accurate result.

### Other Temperature Sensors

[MCP9700A-Cheap Sensor](http://www.microchip.com/wwwproducts/Devices.aspx?dDocName=en027103) is a cheap and simple temperature sensor. [Arduino and MCP9700A](http://starter-kit.nettigo.eu/2010/how-to-measure-temperature-with-arduino-and-mcp9700/) explains how to use it with arduino.

[One Wire Digital Temperature Sensor - DS18B20](https://www.sparkfun.com/products/245) is more accurate than MCP9700A and it only use one port pin for communication.


### Calibration

You need a ground truth to do the calibration. It could be an expensive temperature sensor or boilding water or cold ice.

If you are using MCP9700A sensor, it is best to calibrate it at 25°C.

[Simple Calibration on MCP9700A](http://www.instructables.com/id/How-to-calibrate-a-cheap-temperature-sensor/?ALLSTEPS)


## Reference



