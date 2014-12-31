#include <WaspXBee900.h>
  
// PAN (Personal Area Network) Identifier
uint8_t  PANID[2]={0x7F,0xFF};
// 16-byte Encryption Key
char*  KEY="WaspmoteLinkKey!";
// 64-bit MAC Address
char* macHigh="           ";
char* macLow="           ";
   
void setup()
{
  // init USB
  USB.ON();
  USB.println(F("900MHz Radio Setup and Battery Info"));
   
  // Show the remaining battery level
  USB.print(F("Battery Level: "));
  USB.print(PWR.getBatteryLevel(),DEC);
  USB.print(F(" %"));
    
  // Show the battery Volts
  USB.print(F(" | Battery (Volts): "));
  USB.print(PWR.getBatteryVolts());
  USB.println(F(" V"));
    
  // init XBee
  xbee900.ON();     
          
  // Wait for a second
  delay(1000);
    
    
  /////////////////////////////////////
  // 1. set Channel (from 0x00 to 0x0B)
  /////////////////////////////////////
  xbee900.setChannel(0x0A);
    
  // check the AT commmand execution flag
  if( xbee900.error_AT == 0 )
  {
    USB.println(F("Channel set OK"));
  }
  else
  {
    USB.println(F("Error while changing channel"));
  }
    
   
  /////////////////////////////////////
  // 2. set PANID
  /////////////////////////////////////
  xbee900.setPAN(PANID);
    
  // check the AT commmand execution flag
  if( xbee900.error_AT == 0 )
  {
    USB.println(F("PANID set OK"));
  }
  else
  {
    USB.println(F("Error setting PANID"));
  }
    
  /////////////////////////////////////
  // 3. set encryption mode (1:enable; 0:disable)
  /////////////////////////////////////
  xbee900.setEncryptionMode(0);
    
  // check the AT commmand execution flag
  if( xbee900.error_AT == 0 )
  {
    USB.println(F("Security enabled"));
  }
  else
  {
    USB.println(F("Error while enabling security"));
  }
    
   
  /////////////////////////////////////
  // 4. set encryption key
  /////////////////////////////////////
  xbee900.setLinkKey(KEY);
    
  // check the AT commmand execution flag
  if( xbee900.error_AT == 0 )
  {
    USB.println(F("Key set OK"));
  }
  else
  {
    USB.println(F("Error while setting Key"));
  }
    
   
  /////////////////////////////////////
  // 5. write values to XBee module memory
  /////////////////////////////////////
  xbee900.writeValues();
    
  // check the AT commmand execution flag
  if( xbee900.error_AT == 0 )
  {
    USB.println(F("Changes stored OK"));
  }
  else
  {
    USB.println(F("Error while storing values"));
  }
  USB.println(F("-------------------------------"));
    
  delay(3000);
}
void loop()
{
  /////////////////////////////////////
  // 1. get channel
  /////////////////////////////////////
  if(!xbee900.getChannel())
  {
    USB.print(F("channel: "));
    USB.printHex(xbee900.channel);
    USB.println();
  }
  else
  {
    USB.println(F("error"));
  }
    
  /////////////////////////////////////
  // 2. get PAN ID
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
  // 3. get Encryption mode
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
  // 4. get Mac Address
  /////////////////////////////////////
  if(!xbee900.getOwnMacHigh())
  {
    USB.print(F("Mac High: "));
    Utils.hex2str(xbee900.sourceMacHigh,macHigh,4);
    USB.println(macHigh);
  }
  else
  {
    USB.println(F("error"));
  }
    
  if(!xbee900.getOwnMacLow())
  {
    USB.print(F("Mac Low: "));
    Utils.hex2str(xbee900.sourceMacLow,macLow,4);
    USB.println(macLow);
  }
  else
  {
    USB.println(F("error"));
  }
  USB.println(F("-------------------"));
    
  Utils.setLED(LED0, LED_ON);
  delay(1000);
  Utils.setLED(LED0, LED_OFF);
    
  delay(4000);
}
