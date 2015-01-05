#include <WaspXBee900.h>
#include <WaspXBee802.h>
#include <WaspFrame.h>

#define key_access "LIBELIUM"
#define id_mote "WASPMOTE00000001"

//Pointer to an XBee packet structure 
packetXBee* packet; 

void setup()
{
  // Init USB port
  USB.ON();
  USB.println(F("Dual Radio OTAP example"));
  
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
  USB.println(F("Creating an ASCII frame"));

  // Create new frame (ASCII)
  frame.createFrame(ASCII); 

  // set frame fields (String - char*)
  frame.addSensor(SENSOR_STR, (char*) "this_is_a_string");
  // set frame fields (Battery sensor - uint8_t)
  frame.addSensor(SENSOR_BAT, (uint8_t) PWR.getBatteryLevel());
  // set frame fields (Temperature in Celsius sensor - float)
  frame.addSensor(SENSOR_IN_TEMP, (float) RTC.getTemperature());

  // Prints frame "#387235205#WASPMOTE00000001#1#STR:this_is_a_string#BAT:56#IN_TEMP:31.00#"
  frame.showFrame();
  
  delay(5000);
  
  otap(5000);
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
