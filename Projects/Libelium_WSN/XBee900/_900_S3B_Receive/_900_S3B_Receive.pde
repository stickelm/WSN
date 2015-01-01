#include <WaspXBee900.h>
#include <WaspXBee802.h>

#define key_access "SERIS001"
#define id_mote "WASPMOTE_YSTCM01"

int rssi;

void setup()
{  
  // Init USB port
  USB.ON();
  USB.println(F("900_S3B_Receive"));
  
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
  // Check available data in RX buffer
  if( xbee900.available() > 0 ) 
  {
    // Treat available bytes in order to parse the information as XBee packets
    xbee900.treatData(); 
    
    // Check RX flag after 'treatData'
    if( !xbee900.error_RX ) 
    {
      // Read available packets
      while( xbee900.pos>0 )
      {
        // Available information in 'xbee900.packet_finished' structure
        // HERE it should be introduced the User's packet treatment        
        // For example: show DATA field:
        USB.print(F("Data: "));             
        for(int i=0;i<xbee900.packet_finished[xbee900.pos-1]->data_length;i++)          
        {           
          USB.print(xbee900.packet_finished[xbee900.pos-1]->data[i],BYTE);          
        }
        USB.println("");
        USB.print("Time: ");
        USB.println(RTC.getTime());
        
        // get RSSI signal and make conversion to -dBm
        xbee900.getRSSI();  
        if( !xbee900.error_AT )
        {
          rssi=xbee900.valueRSSI[0];
          rssi*=-1;
          USB.print(F("RSSI(dBm): "));
          USB.println(rssi,DEC);
          USB.print("Time: ");
          USB.print(RTC.getTime());
          USB.println("");
        }  

        // Once a packet has been read it is necessary to 
        // free the allocated memory for this packet
        // free memory
        free(xbee900.packet_finished[xbee900.pos-1]); 
        
        // free pointer
        xbee900.packet_finished[xbee900.pos-1]=NULL; 
        
        // decrement the received packet counter
        xbee900.pos--;
        
        // green LED on
        Utils.setLED(LED1, LED_ON);
        delay(500);
        Utils.setLED(LED1, LED_OFF);
      }
    } 
  }
}