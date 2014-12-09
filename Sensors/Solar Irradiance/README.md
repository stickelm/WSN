## Solar Irradiance Sensor

#### Amplifier Circuit and Simultaion Results

[LTspice](https://github.com/xianlin/WSN/tree/master/Sensors/Solar Irradiance/LTspice) folder contains the circuit file in LTspice format and the simulation results. Based on the results, the linear trend formula is obtained by using excel:
```
Vout = 50.954 x Vin + 0.1084
(Vout and Vin are in V unit)
```

Here is the compare table of the simulation data and real measurement data based on the same circuit setup. (i.e. resistor value not perfect, the linear trend formula will vary than the above formula).

(Real World)Vin | (Real World)Vout | (Simulation)Vin | (Simulation)Vout
---------- | ----------- | ---------- | ----------- |
2.0	| 191.0 | 1.0	| 117.4
3.7	| 281.2 | | 
6.1	| 408.0 | | 
8.4	| 525.5 | | 
15.6	| 895.6 | | 11.0	| 574.5
26.9	| 1,401.0 | 21.0	| 1,083.2
34.7	| 1,799.0 | 31.0	| 1,592.0
40.0	| 2,069.0 | 41.0	| 2,100.7
51.6	| 2,663.0 | 51.0	| 2,609.4
61.0	| 3,163.0 | 60.0	| 3,067.2