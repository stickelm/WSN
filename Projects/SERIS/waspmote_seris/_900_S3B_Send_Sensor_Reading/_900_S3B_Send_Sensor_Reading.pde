#include <WaspXBee900.h>
#include <WaspFrame.h>
#include <WaspSensorPrototyping_v20.h>

// Pointer an XBee packet structure 
packetXBee* packet; 

// Destination MAC address
char* MAC_ADDRESS="0013A20040C369C2";

// Node identifier
char* NODE_ID="YSTCM01";

// Sleeping time DD:hh:mm:ss
char* sleepTime = "00:00:00:10";    

// sensor variable declaration
float ADCValue;
char ADCValueString[10];


void setup()
{
  // 0. Init USB port for debugging
  USB.ON();
  USB.println(F("900_S3B_Send_Sensor_Reading"));


  ////////////////////////////////////////////////
  // 1. Initial message composition
  ////////////////////////////////////////////////

  // 1.1 Set mote Identifier (16-Byte max)
  frame.setID(NODE_ID);	
  
  // 1.2 Set Frame Size (Link encryp disabled, AES encryp disabled)
  frame.setFrameSize(XBEE_900, DISABLED, DISABLED);
  USB.print(F("\nframe size (900, UNICAST_64B, XBee encryp Disabled, AES encryp Disabled):"));
  USB.println(frame.getFrameSize(),DEC);  
  USB.println(); 

  // 1.3 Create new frame
  frame.createFrame();  

  // 1.4 Set frame fields (String - char*)
  frame.addSensor(SENSOR_STR, (char*) "C_03 Example");

  // 1.5 Print frame
  frame.showFrame();


  ////////////////////////////////////////////////
  // 2. Send initial message
  ////////////////////////////////////////////////

  // 2.1 Power XBee
  xbee900.ON();

  // 2.2 Memory allocation
  packet = (packetXBee*) calloc(1,sizeof(packetXBee));

  // 2.3 Choose transmission mode: UNICAST or BROADCAST
  packet -> mode = UNICAST;

  // 2.4 Set destination XBee parameters to packet
  xbee900.setDestinationParams( packet, MAC_ADDRESS, frame.buffer, frame.length);  

  // 2.5 Send XBee packet
  xbee900.sendXBee(packet);

  if( xbee900.error_TX == 0 ) 
  {
    USB.println(F("ok"));
  }
  else 
  {
    USB.println(F("error"));
  }

  // 2.6 Free variables
  free(packet);
  packet=NULL;

  // 2.7 Communication module to OFF
  xbee900.OFF();
  delay(100);

}

void loop()
{

  ////////////////////////////////////////////////
  // 3. Measure corresponding values
  ////////////////////////////////////////////////
  USB.println(F("Measuring sensors..."));

  SensorProtov20.ON();
  delay(100);

  ADCValue = SensorProtov20.readADC();

  Utils.float2String(ADCValue, ADCValueString, 2);

  SensorProtov20.OFF();
  
  // 3.2 Turn on the RTC
  RTC.ON();
  RTC.getTime(); 

  ////////////////////////////////////////////////
  // 4. Message composition
  ////////////////////////////////////////////////

  // 4.1 Create new frame
  frame.createFrame();  

  // 4.2 Add frame fields
  frame.addSensor(SENSOR_STR, ADCValueString);
  frame.addSensor(SENSOR_TIME, RTC.hour, RTC.minute, RTC.second );
  frame.addSensor(SENSOR_BAT, PWR.getBatteryLevel() );
  
  // 4.3 Print frame
  // Example:  <=>#35689391#N01#1#STR:-4.50#TIME:18-11-22#BAT:47#
  frame.showFrame();


  ////////////////////////////////////////////////
  // 5. Send message
  ////////////////////////////////////////////////

  // 5.1 Power XBee
  xbee900.ON();

  // 5.2 Memory allocation
  packet = (packetXBee*) calloc(1,sizeof(packetXBee));

  // 5.3 Choose transmission mode: UNICAST or BROADCAST
  packet -> mode = UNICAST;

  // 5.4 Set destination XBee parameters to packet
  xbee900.setDestinationParams( packet, MAC_ADDRESS, frame.buffer, frame.length);  

  // 5.5 Send XBee packet
  xbee900.sendXBee(packet);

  // 5.6 Check TX flag
  if( xbee900.error_TX == 0 ) 
  {
    USB.println(F("ok"));
  }
  else 
  {
    USB.println(F("error"));
  }

  // 5.7 Free variables
  free(packet);
  packet=NULL;

  // 5.8 Communication module to OFF
  xbee900.OFF();
  delay(100);


  ////////////////////////////////////////////////
  // 6. Entering Deep Sleep mode
  ////////////////////////////////////////////////
  USB.println(F("Going to sleep..."));
  USB.println();
  PWR.deepSleep(sleepTime, RTC_OFFSET, RTC_ALM1_MODE1, ALL_OFF);



}