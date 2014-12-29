#include <WaspXBee900.h>
#include <WaspFrame.h>

//Pointer to an XBee packet structure 
packetXBee* packet; 

// Destination MAC address
//////////////////////////////////////////
char* MAC_ADDRESS="0013A20040BEA8EC";
//////////////////////////////////////////


void setup()
{
  // Init USB port
  USB.ON();
  USB.println(F("900_S3B Sending example"));
  
  // Show the remaining battery level
  USB.print(F("Battery Level: "));
  USB.print(PWR.getBatteryLevel(),DEC);
  USB.print(F(" %"));
  
  // Show the battery Volts
  USB.print(F(" | Battery (Volts): "));
  USB.print(PWR.getBatteryVolts());
  USB.println(F(" V"));
  
  RTC.ON();
  //RTC.setTime("14:12:29:02:12:15:00");
  USB.println(RTC.getTime());
  
  // Powers XBee
  xbee900.ON();
  delay(500);
  
  /////////////////////////////////////
  // 1. get PAN ID
  /////////////////////////////////////
  if(!xbee900.getPAN()) 
  {
    USB.print(F("PANID: "));
    USB.printHex(xbee900.PAN_ID[0]);
    USB.printHex(xbee900.PAN_ID[1]);
    USB.println();
  }
  else 
  {
    USB.println(F("error"));  
  }


  /////////////////////////////////////
  // 2. get Encryption mode
  /////////////////////////////////////
  if(!xbee900.getEncryptionMode()) 
  {
    USB.print(F("Encryption mode: "));
    USB.printHex(xbee900.encryptMode);
    USB.println();
  }
  else 
  {
    USB.println(F("error"));  
  }
  

  /////////////////////////////////////
  // 3. Get the 32 lower bits of my MAC address
  /////////////////////////////////////
  if(!xbee900.getOwnMacLow()) 
  {
    USB.print(F("MAC Low: "));
    USB.printHex(xbee900.sourceMacLow[0]);
    USB.printHex(xbee900.sourceMacLow[1]);
    USB.printHex(xbee900.sourceMacLow[2]);
    USB.printHex(xbee900.sourceMacLow[3]);
    USB.println();
  }
  else 
  {
    USB.println(F("error"));  
  }
  
  
  /////////////////////////////////////
  // 4. Get the 32 lower bits of my MAC address
  /////////////////////////////////////
  if(!xbee900.getOwnMacHigh()) 
  {
    USB.print(F("MAC High: "));
    USB.printHex(xbee900.sourceMacHigh[0]);
    USB.printHex(xbee900.sourceMacHigh[1]);
    USB.printHex(xbee900.sourceMacHigh[2]);
    USB.printHex(xbee900.sourceMacHigh[3]);
    USB.println();
  }
  else 
  {
    USB.println(F("error"));  
  }

  USB.println(F("-------------------")); 
  
}


void loop()
{    
  ///////////////////////////////////////////
  // 1. Create ASCII frame
  ///////////////////////////////////////////  
  
  // 1.1. Create new frame
  frame.createFrame(ASCII, "WASPMOTE_XBEE");  
  
  // 1.2. add frame fields
  frame.addSensor(SENSOR_STR, "S3B frame");
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
    USB.print(F("ok : "));
    USB.println(RTC.getTime());
    // green LED on
    Utils.setLED(LED1, LED_ON);
    delay(500);
    Utils.setLED(LED1, LED_OFF);
    // Wait for ten seconds
    delay(10000);
  }
  else
  { 
    USB.print(F("error : "));
    USB.println(RTC.getTime());
    // red LED on
    Utils.setLED(LED0, LED_ON);
    delay(500);
    Utils.setLED(LED0, LED_OFF);
    // Wait for two seconds
    delay(2000);
  }
  
  // 2.5. Free variables
  free(packet);
  packet=NULL;

}