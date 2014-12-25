/*  
 *  This example shows how to get the remaining battery 
 *  level
 */
 
void setup()
{
  // Open the USB connection
  USB.ON();
}

void loop()
{
  // Show the remaining battery level
  USB.print(F("Battery Level: "));
  USB.print(PWR.getBatteryLevel(),DEC);
  USB.print(F(" %"));
  
  // Show the battery Volts
  USB.print(F(" | Battery (Volts): "));
  USB.print(PWR.getBatteryVolts());
  USB.println(F(" V"));
  
  Utils.setLED(LED0, LED_ON);
  delay(1000);
  Utils.setLED(LED0, LED_OFF);
  
  delay(3000);
}
