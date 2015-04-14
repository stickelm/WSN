/**
Remote XBee Setting:
CH: 10      ID: 1234
DL: FF      MY: 31
NI: Xbee01  BD: 115200
AP: 1       D0: 2(ADC)
D1: 2(ADC)  D2: 3(DI)

Base XBee Setting:
CH: 10      ID: 1234
DL: 00      MY: FF
NI: Gateway BD: 57600
AP: 1

Remote XBee Circuit:
Vcc -- 3.3V
DIO0 -- LDR sensor output
DIO1 -- Temperature sensor output
DIO2 -- PIR sensor output

Sensor Circuit:
Vcc -- 3.3V
LDR Pull-up Resistor -- 1K ohm
PIR output resistor -- 220 ohm
*/

var util = require('util')
var SerialPort = require("serialport").SerialPort
var xbee_api = require('xbee-api');

var C = xbee_api.constants;

var xbeeAPI = new xbee_api.XBeeAPI({
  api_mode: 1
});

var serialPort = new SerialPort("/dev/ttyUSB0", {
  baudrate: 57600,
  parser: xbeeAPI.rawParser()
});

// All frames parsed by the XBee will be emitted here
xbeeAPI.on("frame_object", function(frame) {
    // console.log(">>", util.inspect(frame.data));
    
    var ldrV = frame.data.analogSamples[0].ADC0 / 1023.0 * 3.3;
    var l = Math.round(250 / ( 1 * ((3.3 - ldrV) / ldrV)));
    var t = Math.round((frame.data.analogSamples[0].ADC1 / 1023 * 3.3 - 0.5) * 100 * 100) / 100;
    
    var data = {
        'temperature':  t+ ' deg',
        'luminosity': l + ' lx'
    };
    console.log(data);
});
