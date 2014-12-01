## RTD Temperature Sensor
[What is RTD Temperature Sensor -- Wikipedia](http://en.wikipedia.org/wiki/Resistance_thermometer)

Here we will design a circuit to read the resistance of the PT100 RTD temperature sensor and convert the reading to the temperature.
>  By far the most common devices used in industry have a nominal resistance of 100 ohms at 0 °C, and are called Pt100 sensors ('Pt' is the symbol for platinum, 100 for the resistance in ohm at 0 °C). The sensitivity of a standard 100 ohm sensor is a nominal 0.385 ohm/°C. RTDs with a sensitivity of 0.375 and 0.392 ohm/°C as well as a variety of others are also available.

#### Amplifier Circuit and Simultaion Results

[LTspice](https://github.com/xianlin/WSN/tree/master/Sensors/Temperature-Sensor/LTspice) folder contains the circuit file in LTspice format and the simulation results. Based on the results, the linear trend formula is obtained by using excel:
```
Vout = 0.0408 x Resistance(PT100) - 3.08
```

We have the below PT100 [4-Wired connection](http://en.wikipedia.org/wiki/Resistance_thermometer#Four-wire_configuration) diagram and configuration.


