/*  
*   
*  Circuit:
*    Waspmote V1.2      -->     RTD MAX31865 Convert Board  
*    DIGITAL1: pin S12  -->     SS: pin 2
*    MOSI: pin A11      -->     MOSI: pin 4  -->  SDI (must not be changed for hardware SPI)
*    MISO: pin A12      -->     MISO: pin 6  -->  SDO (must not be changed for hardware SPI)
*    SCK:  pin A7       -->     SCK:  pin 8  -->  SCLK (must not be changed for hardware SPI)
*    GND: pin A6        -->     GND: pin 9 or 10
*    5V SENSOR POWER: pin S22  -->  VCC: pin 11 or 12 -->  Voltage 5V
*/

#include <WaspXBee900.h>
#include <WaspFrame.h>
#include <WaspSensorPrototyping_v20.h>

#define key_access "LIBELIUM"
#define id_mote "YSTCM_Mote"

// Pointer an XBee packet structure 
packetXBee* packet; 

// Destination MAC address
// Source MAC address: 0013A20040C3693F
char* MAC_ADDRESS="0013A20040C369C2";

// Node identifier
char* NODE_ID="YSTCM01";

// Current Time When Upload Hex File
char* currentTime = "14:12:26:06:14:30:00"; 

// Sleeping time DD:hh:mm:ss
char* sleepTime = "00:00:00:10";  
char* nightsleepTime = "00:12:00:00";  

// sensor variable declaration
float temp;
float solar;

int8_t CS_PIN = DIGITAL1;


struct var_seris_sensor{
    uint16_t rtd_res_raw;		// RTD IC raw resistance register
    uint8_t  status;			// RTD status - full status code
    uint8_t  conf_reg;			// Configuration register readout
    int16_t  HFT_val;			// High fault threshold register readout
    int16_t  LFT_val;			// Low fault threshold register readout
    uint8_t  RTD_type;			// RTD type. 1 = PT100; 2 = PT1000
};


void SERIS_SENSOR_config(void) 
{
  // take the chip select low to select the device:
  digitalWrite(CS_PIN, LOW);

  // Write config to MAX31865 chip
  SPI.transfer(0x80);    // Send config register location to chip
                            // 0x8x to specify 'write register value' 
                            // 0xx0 to specify 'configuration register'
  SPI.transfer(0xC2);    // Write config to IC, 0xC2 --> 1100 0010
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


void SERIS_SENSOR_full_read(struct var_seris_sensor *seris_sensor)
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

  seris_sensor->conf_reg = SPI.transfer(0x00);	// read 1st 8 bits

  _rtd_res = SPI.transfer(0x00);		    // read 2nd 8 bits
  _rtd_res <<= 8;				       		// shift data 8 bits left
  _rtd_res |= SPI.transfer(0x00);  			// read 3rd 8 bits
  seris_sensor->rtd_res_raw = _rtd_res >> 1;		// store data after 1-bit right shift

  _temp_u16 = SPI.transfer(0x00);			// read 4th 8 bits
  _temp_u16 <<= 8;							// shift data 8 bits left
  _temp_u16 |= SPI.transfer(0x00);			// read 5th 8 bits
  seris_sensor->HFT_val = _temp_u16 >> 1;		// store data after 1-bit right shift

  _temp_u16 = SPI.transfer(0x00);			// read 6th 8 bits
  _temp_u16 <<= 8;							// shift data 8 bits left
  _temp_u16 |= SPI.transfer(0x00);			// read 7th 8 bits
  seris_sensor->LFT_val = _temp_u16;				// store data after 1-bit right shift

  seris_sensor->status = SPI.transfer(0x00);		// read 8th 8 bits

  digitalWrite(CS_PIN, HIGH);					// set CS high to finish read
  
  // re-write config if no valid read or a fault present
  // keep in mind some faults re-set immediately (HFT/LFT)
  if((0 == _rtd_res) || (0 != seris_sensor->status))  
  {
     SERIS_SENSOR_config();				// call config function
  }
}


void setup()
{
  // 0. Init USB port for debugging
  USB.ON();
  USB.println(F("SERIS SENSOR SETUP ..."));
  
  /*// Powers RTC up, init I2C bus and read initial values
  USB.println(F("Init RTC"));
  // Turn on the RTC
  RTC.ON();*/
  //////////// ONLY FIRST TIME /////////
  //RTC.ON();
  // Setting time. YY-MM-DD-XX-HH-MM-SS
  //RTC.setTime("12:02:02:05:10:25:00");
  //USB.println(RTC.getTime());
  /////////////////////////////////////
  SPI.begin();
}

void loop()
{

  ////////////////////////////////////////////////
  // 3. Measure corresponding values
  ////////////////////////////////////////////////
  USB.println(F("Measuring sensors..."));

  SensorProtov20.ON();
  
  // setup for the the SPI library:
  SPI.begin();                            // begin SPI
  SPI.setClockDivider(SPI_CLOCK_DIV16);   // SPI speed to SPI_CLOCK_DIV16 (1MHz)
  SPI.setDataMode(SPI_MODE3);             // MAX31865 works in MODE1 or MODE3
  // give the SPI time to set up
  delay(200);
  
  // initalize the chip select pin
  pinMode(CS_PIN, OUTPUT);
  SERIS_SENSOR_config();
  
  // give the sensor time to set up
  delay(200);
  
  static struct var_seris_sensor SERIS;
  double tmp;
  
  struct var_seris_sensor *seris_sensor;
  seris_sensor = &SERIS;
  SERIS_SENSOR_full_read(seris_sensor);          // Update SERIS SENSOR readings 
  
  // ******************** Print RTD Sensor Information ********************
  USB.println("RTD Sensor:");              // Print RTD header
  
  if(0 == SERIS.status)                       // no fault, print info to serial port
  {
    // calculate RTD resistance
    tmp = (double)SERIS.rtd_res_raw * 400 / 32768;
    USB.print("Rrtd = ");                  // print RTD resistance heading
    USB.print(tmp);                        // print RTD resistance
    USB.println(" ohm");
    // calculate RTD temperature (simple calc, +/- 2 deg C from -100C to 100C)
    // more accurate curve can be used outside that range
    tmp = ((double)SERIS.rtd_res_raw / 32) - 256;
	temp = tmp;
    USB.print("Trtd = ");                    // print RTD temperature heading
    USB.print(tmp);                          // print RTD resistance
    USB.println(" deg C");                   // print RTD temperature heading
  }  // end of no-fault handling
  else 
  {
    USB.print("RTD Fault, register: ");
    USB.print(SERIS.status);
    if(0x80 & SERIS.status)
    {
      USB.println("RTD High Threshold Met");  // RTD high threshold fault
    }
    else if(0x40 & SERIS.status)
    {
      USB.println("RTD Low Threshold Met");   // RTD low threshold fault
    }
    else if(0x20 & SERIS.status)
    {
      USB.println("REFin- > 0.85 x Vbias");   // REFin- > 0.85 x Vbias
    }
    else if(0x10 & SERIS.status)
    {
      USB.println("FORCE- open");             // REFin- < 0.85 x Vbias, FORCE- open
    }
    else if(0x08 & SERIS.status)
    {
      USB.println("FORCE- open");             // RTDin- < 0.85 x Vbias, FORCE- open
    }
    else if(0x04 & SERIS.status)
    {
      USB.println("Over/Under voltage fault");  // overvoltage/undervoltage fault
    }
    else
    {
      USB.println("Unknown fault, check connection"); // print RTD temperature heading
    }
  }  // end of fault handling
  
    // ******************** Print Solar Irradiance Sensor Information ********************
    USB.println("Solar Irradiance Sensor:");       // Print Solar Irradiance Reading header
  
    // calculate solar irradiance voltage
    tmp = (double) analogRead(ANALOG1) / 1024 * 3.3 * 1000;
    USB.print("ADC Voltage Reading = ");
    USB.print(tmp);
    USB.println(" mV");
    if (tmp < 99.97)
    {
        tmp = 0;
    } else
    {
        tmp = (tmp - 99.97) / 49.152;         // Linear regression formula
    }
	solar = tmp;
    USB.print("Calculated Irradiance = ");     
    USB.print(tmp);                        // print solar irradiance voltage
    USB.println(" mV");
    
//    // In case you want to know the irradiance input voltage (not accurate due to ADC error)
//    tmp = (double) analogRead(ANALOG3) / 1024 * 3.3 * 1000;
//    USB.print("Input Irradiance (With Error)= ");     
//    USB.print(tmp);                        // print solar irradiance voltage
//    USB.println(" mV");

  SensorProtov20.OFF();
  
  RTC.ON();
  USB.print("Current Time Stamp = ");
  USB.println(RTC.getTime()); 

  ////////////////////////////////////////////////
  // 4. Message composition
  ////////////////////////////////////////////////

  // 4.1 Create new frame
  frame.createFrame();  

  // 4.2 Add frame fields
  frame.addSensor(SENSOR_TIME, RTC.hour, RTC.minute, RTC.second );
  frame.addSensor(SENSOR_BAT, PWR.getBatteryLevel() );
  frame.addSensor(SENSOR_TCA, temp );
  frame.addSensor(SENSOR_PAR, solar );
  
  // 4.3 Print frame
  // Example:  <=>?#35689391#N01#1#STR:-4.50#TIME:18-11-22#BAT:47#
  frame.showFrame();

  ////////////////////////////////////////////////
  // 5. Send message
  ////////////////////////////////////////////////
  
  // 2.1 Power XBee
  xbee900.ON();
  delay(500);
  
  // 5.2 Memory allocation
  packet = (packetXBee*) calloc(1,sizeof(packetXBee));

  // 5.3 Choose transmission mode: UNICAST or BROADCAST
  packet -> mode = UNICAST;

  // 5.4 Set destination XBee parameters to packet
  xbee900.setDestinationParams( packet, MAC_ADDRESS, frame.buffer, frame.length);  

  // 5.5 Send XBee packet
  xbee900.sendXBee(packet);

  // 5.6 Check TX flag
  if( xbee900.error_TX == 0 ) 
  {
    USB.println(F("ok"));
  }
  else 
  {
    USB.println(F("error"));
  }

  // 5.7 Free variables
  free(packet);
  packet=NULL;
  
  
  // Check if new data is available, then do OTAP
  if( xbee900.available() )
  {
    xbee900.treatData();
    // Keep inside this loop while a new program is being received
    while( xbee900.programming_ON  && !xbee900.checkOtapTimeout() )
    {
      if( xbee900.available() )
      {
        xbee900.treatData();
      }
    }
  }    
  
  

  ////////////////////////////////////////////////
  // 6. Entering Deep Sleep mode at night
  ////////////////////////////////////////////////
  /*// Setting Alarm1
  RTC.setAlarm1("13:12",RTC_ABSOLUTE,RTC_ALM1_MODE3);
  
  // Getting Alarm1
  USB.print(F("Alarm1: "));
  USB.println(RTC.getAlarm1());  */
  
  
  // Setting Waspmote to Low-Power Consumption Mode
  // Night Sleep begin at 19:00hrs and sleep for 12 hours
  if ((RTC.hour == 19)) 
  {
    USB.println(RTC.getTime());
    USB.println(F("Night sleep starts for 12 hours..."));
    USB.println();
    PWR.deepSleep(nightsleepTime, RTC_OFFSET, RTC_ALM1_MODE1, ALL_OFF);
  } 
  //Entering Deep Sleep mode every 10 seconds
  else 
  {   
      USB.println(F("Going to sleep..."));
      USB.println();
      PWR.deepSleep(sleepTime, RTC_OFFSET, RTC_ALM1_MODE1, ALL_OFF);
  }
  
  USB.println(F("Waspmote wakes up!"));
  
}