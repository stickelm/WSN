## RTD Temperature Sensor
[What is RTD Temperature Sensor -- Wikipedia](http://en.wikipedia.org/wiki/Resistance_thermometer)

>  By far the most common devices used in industry have a nominal resistance of 100 ohms at 0 °C, and are called Pt100 sensors ('Pt' is the symbol for platinum, 100 for the resistance in ohm at 0 °C). The sensitivity of a standard 100 ohm sensor is a nominal 0.385 ohm/°C. RTDs with a sensitivity of 0.375 and 0.392 ohm/°C as well as a variety of others are also available.

### Amplifier Circuit and Simultaion Results

Here we will design a circuit to read the resistance of the PT100 RTD temperature sensor and convert the reading to the temperature.

The PT100 Sensor [4-Wired connection](http://en.wikipedia.org/wiki/Resistance_thermometer#Four-wire_configuration) diagram and configuration.

[LTspice](https://github.com/xianlin/WSN/tree/master/Sensors/Temperature/LTspice) folder contains the circuit file in LTspice format and the simulation results. Based on the results, the linear trend formula is obtained by using excel:
```
Vout = 0.0408 x Resistance(PT100) - 3.08
```

### IC Solution

The above amplifier circuit will yield errors and the best way to convert RTD resistance to temperature is by using ICs such as [MAX31865](http://www.maximintegrated.com/en/products/analog/sensors-and-sensor-interface/MAX31865.html). 

The MAX31865 PT-100 RTD to Digital Breakout Board can be purchased [online](http://playingwithfusion.com/productview.php?pdid=25) with downloadable schematic and [arduino sketch files](https://github.com/xianlin/WSN/tree/master/Sensors/Temperature/PWFusion_MAX31865). Just copy the sketch into arduino library folder and upload the example sketch onto Arduino Uno.



