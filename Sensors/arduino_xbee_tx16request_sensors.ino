/** Arduino sending sensor data via XBee Series 1 (16bit addressing) to the base XBee
* 
*/
#include <XBee.h>
#include <SoftwareSerial.h>

// Connect XBee Dout to Arduino Pin 7, Din to Pin 8
uint8_t ssRX = 7;
uint8_t ssTX = 8;
SoftwareSerial nss(ssRX, ssTX);

XBee xbee = XBee();

unsigned long start = millis();

// allocate two bytes for to hold a 10-bit analog reading
uint8_t payload[] = { 0, 0 };

// 16-bit addressing: Enter address of remote XBee, typically the coordinator
Tx16Request tx = Tx16Request(0x00FF, payload, sizeof(payload));

TxStatusResponse txStatus = TxStatusResponse();

// LDR sensor ADC value
int ldr = 0;

void setup() { 
  // start soft serial
  nss.begin(9600);
  xbee.begin(nss);

  Serial.begin(9600);  
  // Startup delay to wait for XBee radio to initialize.
  // you may need to increase this value if you are not getting a response
  delay(5000);
}

void loop() {

      ldr = analogRead(A0);
      payload[0] = ldr >> 8 & 0xff;
      payload[1] = ldr & 0xff;
      
      xbee.send(tx);
  
    // after sending a tx request, we expect a status response
    // wait up to 5 seconds for the status response
    if (xbee.readPacket(1000)) {
        // got a response!

        // should be a znet tx status            	
    	if (xbee.getResponse().getApiId() == TX_STATUS_RESPONSE) {
    	   xbee.getResponse().getZBTxStatusResponse(txStatus);
    		
    	   // get the delivery status, the fifth byte
           if (txStatus.getStatus() == SUCCESS) {
            	// success.  time to celebrate
             	Serial.println("Tx success");
           } else {
            	// the remote XBee did not receive our packet. is it powered on?
             	Serial.println("Tx failed");;
           }
        }      
    } else if (xbee.getResponse().isError()) {
      Serial.print("Error reading packet.  Error code: ");  
      Serial.println(xbee.getResponse().getErrorCode());
      // or flash error led
    } else {
      // local XBee did not provide a timely TX Status Response.  Radio is not configured properly or connected
      Serial.println("No Response At All, Check your configuration...");
    }
    
    delay(2000);
}
