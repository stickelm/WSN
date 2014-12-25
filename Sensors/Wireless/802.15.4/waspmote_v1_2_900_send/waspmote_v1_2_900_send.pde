#include <WaspXBee900.h>
#include <WaspFrame.h>
//Pointer to an XBee packet structure
packetXBee* packet;
// Destination MAC address
//////////////////////////////////////////
char* MAC_ADDRESS="0013A20040A81815";
//////////////////////////////////////////
void setup()
{
  // Init USB port
  USB.ON();
  USB.println(F("Sending"));
   
  // Show the remaining battery level
  USB.print(F("Battery Level: "));
  USB.print(PWR.getBatteryLevel(),DEC);
  USB.print(F(" %"));
     
  // Show the battery Volts
  USB.print(F(" | Battery (Volts): "));
  USB.print(PWR.getBatteryVolts());
  USB.println(F(" V"));
  // Powers XBee
  xbee900.ON();
   
}
void loop()
{ 
  ///////////////////////////////////////////
  // 1. Create ASCII frame
  /////////////////////////////////////////// 
   
  // 1.1. Create new frame
  frame.createFrame(ASCII, "WASPMOTE_XBEE"); 
   
  // 1.2. add frame fields
  frame.addSensor(SENSOR_STR, "XBee frame");
  frame.addSensor(SENSOR_BAT, PWR.getBatteryLevel());
  
  ///////////////////////////////////////////
  // 2. Send packet
  /////////////////////////////////////////// 
   
  // 2.1. Set parameters to packet:
  packet=(packetXBee*) calloc(1,sizeof(packetXBee)); // Memory allocation
  packet->mode=UNICAST; // Choose transmission mode: UNICAST or BROADCAST
   
  // 2.2. Set destination XBee parameters to packet
  xbee900.setDestinationParams( packet, MAC_ADDRESS, frame.buffer, frame.length); 
   
  // 2.3. Send XBee packet
  xbee900.sendXBee(packet);
   
  // 2.4. Check TX flag
  if( xbee900.error_TX == 0)
  {
    USB.println(F("ok"));
    Utils.setLED(LED0, LED_ON);
    delay(500);
    Utils.setLED(LED0, LED_OFF);
    Utils.setLED(LED1, LED_ON);
    delay(500);
    Utils.setLED(LED1, LED_OFF);
  }
  else
  {
    USB.println(F("error"));
    Utils.blinkLEDs(1000);
  }
   
  // 2.5. Free variables
  free(packet);
  packet=NULL;
  // 2.6. Wait for five seconds
  delay(5000);
}
