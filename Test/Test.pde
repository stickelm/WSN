#include <WaspXBee802.h>
#include <WaspFrame.h>
#include <WaspSensorCities.h>

//Pointer to an XBee packet structure 
packetXBee* packet; 

// Destination MAC address
char* MAC_ADDRESS="0013A2004093ADCA";

// Variable to store the read value
float noiseFloatValue;

void setup()
{
  // init USB port
  USB.ON();
  USB.println(F("Setup Started"));

  // init XBee
  xbee802.ON();
  
  // Turn on the sensor board
  SensorCities.ON();
  
  // Turn on the RTC
  RTC.ON();
  
}


void loop()
{
  // LED Indication
  USB.println(F("Start Measuring Audio..."));
  Utils.setLED(LED0, LED_ON);
  delay(1000);
  Utils.setLED(LED0, LED_OFF);
  
  // Part 1: Sensor reading
  // Turn on the sensor and wait for stabilization and response time
  SensorCities.setSensorMode(SENS_ON, SENS_CITIES_AUDIO);
  delay(2000);
  
  // Read the audio sensor 
  noiseFloatValue = SensorCities.readValue(SENS_CITIES_AUDIO);
  
  // Turn off the sensor
  SensorCities.setSensorMode(SENS_OFF, SENS_CITIES_AUDIO);
  
  // Part 2: USB printing
  // Print the sound level value through the USB
  USB.print(F("Sound pressure: "));
  USB.print(noiseFloatValue);
  USB.println(F("dBA"));
  
  delay(1000);
  
  
  ///////////////////////////////////////////
  // 1. Create ASCII frame
  ///////////////////////////////////////////  

  frame.setID("N1");
  // create new frame
  frame.createFrame(ASCII, "XBee Frame");  
  
  // add frame fields
  frame.addSensor(SENSOR_STR, "Audio Reading");
  frame.addSensor(SENSOR_MCP, noiseFloatValue);
  frame.addSensor(SENSOR_BAT, PWR.getBatteryLevel()); 
  
  frame.showFrame();
  
  ///////////////////////////////////////////
  // 2. Send packet
  ///////////////////////////////////////////  

  // set parameters to packet:
  packet=(packetXBee*) calloc(1,sizeof(packetXBee)); // Memory allocation
  packet->mode=UNICAST; // Choose transmission mode: UNICAST or BROADCAST
  
  // set destination XBee parameters to packet
  xbee802.setDestinationParams(packet, MAC_ADDRESS, frame.buffer, frame.length, MAC_TYPE);   
  
  // send XBee packet
  xbee802.sendXBee(packet);
  
  // check TX flag
  if( xbee802.error_TX == 0 )
  {
    USB.println(F("XBee Sending Packet OK"));
  // LED Indication
    Utils.setLED(LED1, LED_ON);
    delay(1000);
    Utils.setLED(LED1, LED_OFF);
  }
  else 
  {
    USB.println(F("error"));
  }
  
  // free variables
  free(packet);
  packet=NULL;

  // wait for 1 minute
  delay(60000);
}
