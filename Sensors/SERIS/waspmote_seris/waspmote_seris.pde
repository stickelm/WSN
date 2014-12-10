/*  
*   
*  Circuit:
*    Waspmote V1.2  -->  RTD MAX31865 Convert Board
*    CS: pin 10    -->  SS, CH1
*    MOSI: pin 11  MOSI: pin 51  -->  SDI (must not be changed for hardware SPI)
*    MISO: pin 12  MISO: pin 50  -->  SDO (must not be changed for hardware SPI)
*    SCK:  pin 13  SCK:  pin 52  -->  SCLK (must not be changed for hardware SPI)
*    GND           GND           -->  GND
*    5V            5V            -->  Vin (supply with same voltage as Arduino I/O, 5V) 
*/

#include <WaspSensorPrototyping_v20.h>

int8_t CS_PIN = DIGITAL1;

struct var_max31865{
    uint16_t rtd_res_raw;		// RTD IC raw resistance register
    uint8_t  status;			// RTD status - full status code
    uint8_t  conf_reg;			// Configuration register readout
    int16_t  HFT_val;			// High fault threshold register readout
    int16_t  LFT_val;			// Low fault threshold register readout
    uint8_t  RTD_type;			// RTD type. 1 = PT100; 2 = PT1000
};


void MAX31865_config(void) 
{
  // take the chip select low to select the device:
  digitalWrite(CS_PIN, LOW);

  // Write config to MAX31865 chip
  SPI.transfer(0x80);    // Send config register location to chip
                            // 0x8x to specify 'write register value' 
                            // 0xx0 to specify 'configuration register'
  SPI.transfer(0xC2);    // Write config to IC
                            // bit 7: Vbias -> 1 (ON)
                            // bit 6: conversion mode -> 1 (AUTO)
                            // bit 5: 1-shot -> 0 (off)
                            // bit 4: 3-wire select -> 0 (2/4 wire config)
                            // bit 3-2: fault detection cycle -> 0 (none)
                            // bit 1: fault status clear -> 1 (clear any fault)
                            // bit 0: 50/60 Hz filter select -> 0 (60 Hz)
  
  // take the chip select high to de-select, finish config write
  digitalWrite(CS_PIN, HIGH);


  // code if you need to configure High and Low fault threshold registers (4 total registers)
//  // take the chip select low to select the device:
//  digitalWrite(CS_PIN, LOW);
//  SPI.transfer(0x83);  // write cmd, start at HFT MSB reg (0x83)
//  SPI.transfer(0xFF);  // write cmd, start at HFT MSB reg (0x83)
//  SPI.transfer(0xFF);  // write cmd, start at HFT MSB reg (0x83)
//  SPI.transfer(0x00);  // write cmd, start at HFT MSB reg (0x83)
//  SPI.transfer(0x00);  // write cmd, start at HFT MSB reg (0x83)
//  // take the chip select high to de-select, finish config write
//  digitalWrite(CS_PIN, HIGH);
}


void MAX31865_full_read(struct var_max31865 *rtd_ptr)
{
  // Function to unpack and store MAX31865 data
  uint16_t _temp_u16, _rtd_res;
  uint32_t _temp_u32;

  digitalWrite(CS_PIN, LOW);			// must set CS low to start operation

  // Write command telling IC that we want to 'read' and start at register 0
  SPI.transfer(0x00);				// plan to start read at the config register

  // read registers in order:
	// configuration
	// RTD MSBs
	// RTD LSBs
	// High Fault Threshold MSB
	// High Fault Threshold LSB
	// Low Fault Threshold MSB
	// Low Fault Threshold LSB
	// Fault Status

  rtd_ptr->conf_reg = SPI.transfer(0x00);	// read 1st 8 bits

  _rtd_res = SPI.transfer(0x00);		    // read 2nd 8 bits
  _rtd_res <<= 8;				       		// shift data 8 bits left
  _rtd_res |= SPI.transfer(0x00);  			// read 3rd 8 bits
  rtd_ptr->rtd_res_raw = _rtd_res >> 1;		// store data after 1-bit right shift

  _temp_u16 = SPI.transfer(0x00);			// read 4th 8 bits
  _temp_u16 <<= 8;							// shift data 8 bits left
  _temp_u16 |= SPI.transfer(0x00);			// read 5th 8 bits
  rtd_ptr->HFT_val = _temp_u16 >> 1;		// store data after 1-bit right shift

  _temp_u16 = SPI.transfer(0x00);			// read 6th 8 bits
  _temp_u16 <<= 8;							// shift data 8 bits left
  _temp_u16 |= SPI.transfer(0x00);			// read 7th 8 bits
  rtd_ptr->LFT_val = _temp_u16;				// store data after 1-bit right shift

  rtd_ptr->status = SPI.transfer(0x00);		// read 8th 8 bits

  digitalWrite(CS_PIN, HIGH);					// set CS high to finish read
  
  // re-write config if no valid read or a fault present
  // keep in mind some faults re-set immediately (HFT/LFT)
  if((0 == _rtd_res) || (0 != rtd_ptr->status))  
  {
     MAX31865_config();				// call config function
  }
}

void setup()
{
  //Turn on the USB and print a start message
  USB.ON();
  USB.println(F("start"));
  
  //Turn on the sensor board
  SensorProtov20.ON();
  
  //Turn on the RTC
  RTC.ON();
  
  // setup for the the SPI library:
  SPI.begin();                            // begin SPI
  SPI.setClockDivider(SPI_CLOCK_DIV16);   // SPI speed to SPI_CLOCK_DIV16 (1MHz)
  SPI.setDataMode(SPI_MODE3);             // MAX31865 works in MODE1 or MODE3
  
  // initalize the chip select pin
  pinMode(CS_PIN, OUTPUT);
  MAX31865_config();
  
  // give the sensor time to set up
  delay(100);  
  
}
 
void loop()
{
  delay(2000);                                   // 1500ms delay... can be much faster
  
  static struct var_max31865 RTD_CH0;
  double tmp;
  double val;
  
  struct var_max31865 *rtd_ptr;
  rtd_ptr = &RTD_CH0;
  MAX31865_full_read(rtd_ptr);          // Update MAX31855 readings 
  
  // ******************** Print RTD 0 Information ********************
  USB.println("RTD Sensor 0:");              // Print RTD0 header
  
  if(0 == RTD_CH0.status)                       // no fault, print info to serial port
  {
    // calculate RTD resistance
    tmp = (double)RTD_CH0.rtd_res_raw * 400 / 32768;
    USB.print("Rrtd = ");                  // print RTD resistance heading
    USB.print(tmp);                        // print RTD resistance
    USB.println(" ohm");
    // calculate RTD temperature (simple calc, +/- 2 deg C from -100C to 100C)
    // more accurate curve can be used outside that range
    tmp = ((double)RTD_CH0.rtd_res_raw / 32) - 256;
    USB.print("Trtd = ");                    // print RTD temperature heading
    USB.print(tmp);                          // print RTD resistance
    USB.println(" deg C");                   // print RTD temperature heading
  }  // end of no-fault handling
  else 
  {
    USB.print("RTD Fault, register: ");
    USB.print(RTD_CH0.status);
    if(0x80 & RTD_CH0.status)
    {
      USB.println("RTD High Threshold Met");  // RTD high threshold fault
    }
    else if(0x40 & RTD_CH0.status)
    {
      USB.println("RTD Low Threshold Met");   // RTD low threshold fault
    }
    else if(0x20 & RTD_CH0.status)
    {
      USB.println("REFin- > 0.85 x Vbias");   // REFin- > 0.85 x Vbias
    }
    else if(0x10 & RTD_CH0.status)
    {
      USB.println("FORCE- open");             // REFin- < 0.85 x Vbias, FORCE- open
    }
    else if(0x08 & RTD_CH0.status)
    {
      USB.println("FORCE- open");             // RTDin- < 0.85 x Vbias, FORCE- open
    }
    else if(0x04 & RTD_CH0.status)
    {
      USB.println("Over/Under voltage fault");  // overvoltage/undervoltage fault
    }
    else
    {
      USB.println("Unknown fault, check connection"); // print RTD temperature heading
    }
  }  // end of fault handling
  
  // Measure Solar Irradiance Reading and Print to Serial Port
  val = (double)analogRead(ANALOG1) / 1024 * 3.3 * 1000 / 50;
  USB.print("Solar Irradiance = ");     // Print Solar Irradiance Reading in mV               
  USB.print(val);                          
  USB.println(" mV");                   
  
}


