#include <WaspXBee900.h>
#include <WaspXBee802.h>
#include <WaspFrame.h>

#define key_access "SERIS001"
#define id_mote "WASPMOTE_YSTCM01"

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
  
  // Write Authentication Key to EEPROM memory
  Utils.setAuthKey(key_access);
  
  // Write Mote ID to EEPROM memory
  Utils.setID(id_mote);
  
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
  USB.println(F("-------------------"));
  
  // Powers XBee 900 S3B on socket 0
  xbee900.ON();
  delay(500);
  USB.println("XBee 900 S3B Info...");
  
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
  
  // Powers XBee 802 On socket 1
  xbee802.ON(SOCKET1);
  delay(500);
  USB.println("XBee 802.15.4 Info...");
  
  if(!xbee802.getPAN()) 
  {
    USB.print(F("PANID: "));
    USB.printHex(xbee802.PAN_ID[0]);
    USB.printHex(xbee802.PAN_ID[1]);
    USB.println();
  }
  else 
  {
    USB.println(F("error"));  
  }
  
  if(!xbee802.getChannel()) 
  {
    USB.print(F("Channel: "));
    USB.printHex(xbee802.channel);
    USB.println();
  }
  else 
  {
    USB.println(F("error"));  
  }

  if(!xbee802.getEncryptionMode()) 
  {
    USB.print(F("Encryption mode: "));
    USB.printHex(xbee802.encryptMode);
    USB.println();
  }
  else 
  {
    USB.println(F("error"));  
  }

  if(!xbee802.getOwnMacLow()) 
  {
    USB.print(F("MAC Low: "));
    USB.printHex(xbee802.sourceMacLow[0]);
    USB.printHex(xbee802.sourceMacLow[1]);
    USB.printHex(xbee802.sourceMacLow[2]);
    USB.printHex(xbee802.sourceMacLow[3]);
    USB.println();
  }
  else 
  {
    USB.println(F("error"));  
  }
  
  if(!xbee802.getOwnMacHigh()) 
  {
    USB.print(F("MAC High: "));
    USB.printHex(xbee802.sourceMacHigh[0]);
    USB.printHex(xbee802.sourceMacHigh[1]);
    USB.printHex(xbee802.sourceMacHigh[2]);
    USB.printHex(xbee802.sourceMacHigh[3]);
    USB.println();
  }
  else 
  {
    USB.println(F("error"));  
  }

  USB.println(F("-------------------"));
  
  // CheckNewProgram is mandatory in every OTA program
  xbee802.checkNewProgram();  
  
}


void loop()
{    
  ///////////////////////////////////////////
  // 1. Create ASCII frame
  ///////////////////////////////////////////  
  
  // 1.1. Create new frame
  frame.createFrame(ASCII, "WASPMOTE_XBEE");  
  
  // 1.2. add frame fields
  frame.addSensor(SENSOR_STR, "S3B_Frame");
  frame.addSensor(SENSOR_BAT, PWR.getBatteryLevel());
  frame.addSensor(SENSOR_TCA, RTC.getTemperature());

  
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
    // start OTAP window to 10 seconds
    otap(10000);
  }
  else
  { 
    USB.print(F("error : "));
    USB.println(RTC.getTime());
    // red LED on
    Utils.setLED(LED0, LED_ON);
    delay(500);
    Utils.setLED(LED0, LED_OFF);
    // Wait for two seconds and resend packet
    delay(2000);
  }
  
  // 2.5. Free variables
  free(packet);
  packet=NULL;

}

// XBee 802.15.4 OTAP
void otap(unsigned long otaTimeout)
{
    unsigned long previous = millis();
    
    while (millis() - previous < otaTimeout)
    {
    // Do OTA stuff, check if new data is available
    if( xbee802.available() )
    {
      xbee802.treatData();
      // Keep inside this loop while a new program is being received
      while( xbee802.programming_ON  && !xbee802.checkOtapTimeout() )
      {
        if( xbee802.available() )
        {
          xbee802.treatData();
        }
      }
    }
    // Blink LED1 while messages are not received
    Utils.setLED(LED1,LED_ON);
    delay(100);
    Utils.setLED(LED1,LED_OFF);
    delay(100);
    //avoid millis overflow problem
    if( millis() < previous ) previous = millis(); 
    }
}
