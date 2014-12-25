### Waspmote Power

Waspmote can be powered from the solar panel connector, but with the limitation of the charging current:

Waspmote powered only by USB --> max current is 100 mA. Not enough for radio modules
Waspmote powered only by Solar panel --> max current is 280 mA. Not enough for radio modules
Waspmote with battery --> enough current for all radio modules.


### Begin

The default message printed by waspmote via the USB to Serial console

    Current ASCII Frame: 
    Length:  57
    Frame Type (decimal): 128
    HEX:     3C 3D 3E 80 03 23 33 38 37 32 33 35 32 30 35 23 23 34 23 41 43 43 3A 2D 32 33 3B 2D 38 35 3B 31 30 34 30 23 49 4E 5F 54 45 4D 50 3A 32 39 2E 30 30 23 42 41 54 3A 37 31 23 
    String:  <=>#387235205##4#ACC:-23;-85;1040#IN_TEMP:29.00#BAT:71#
    ===============================
    the frame above is printed just by USB (it is not sent because no XBee is plugged)
    ===============================

### Solar Panel Specification

Maximum Power (Watt) 3
Production Tolerance ±3%
Maximum Power Voltage (V) 6.9
Maximum Power Current (A) 0.44
Open circuit voltage (V) 8.49
Short circuit current (A) 0.47
Size of module 234mmx160mmx17mm
Module efficiency (%) 12.8
frame (type, material and thickness) Anodized Aluminium frame, 17mm thickness
Size of cells (wide and high) 125mmx15mm

Test condition: 1000W/m2, AM1.5, 25℃