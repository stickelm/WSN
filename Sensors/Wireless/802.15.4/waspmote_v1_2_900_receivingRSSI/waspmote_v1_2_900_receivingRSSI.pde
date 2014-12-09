#include <WaspXBee900.h>
// variable to store RSSI
int rssi;
void setup()
{ 
  // Init USB port
  USB.ON();
  USB.println(F("Receiving"));
 
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
         
        // get RSSI signal and make conversion to -dBm
        xbee900.getRSSI(); 
        if( !xbee900.error_AT )
        {
          rssi=xbee900.valueRSSI[0];
          rssi*=-1;
          USB.print(F("RSSI(dBm): "));
          USB.println(rssi,DEC);
        } 
         
        USB.println("");
        // Once a packet has been read it is necessary to
        // free the allocated memory for this packet
        // free memory
        free(xbee900.packet_finished[xbee900.pos-1]);
         
        // free pointer
        xbee900.packet_finished[xbee900.pos-1]=NULL;
         
        // decrement the received packet counter
        xbee900.pos--;
      }
      Utils.setLED(LED1, LED_ON);
      delay(1000);
      Utils.setLED(LED1, LED_OFF);
    } else {
      Utils.setLED(LED1, LED_ON);
      delay(1000);
      Utils.setLED(LED1, LED_OFF);
    }
  }
}
