### XBee Chat

[Basic XBee 802.15.4 (Series 1) Chat](http://examples.digi.com/get-started/basic-xbee-802-15-4-chat/)


### XBee As Sensor

You may need to read the below guide on how to start using XBee as a sensor:

[Digital and Analog Sampling Using XBee Radios](http://www.digi.com/support/kbase/kbaseresultdetl?id=3522)

The [Analog to Digital Conversion on the XBee 802.15.4](http://www.digi.com/support/kbase/kbaseresultdetl?id=2180) explains on ADC of XBee.

The 
[SparkFun XBee Explorer Regulated Breakout Board](https://www.sparkfun.com/products/11373) will be come in handy for prototyping.
![Breakout Board Pinout - Backside](https://cdn.sparkfun.com//assets/parts/7/1/1/5/11373-03.jpg)
Please note that the *RES* pin on this breakout board is the *Vref* pin on XBee when you are doing ADC.

The online [DIGI API Frame Tool](http://ftp1.digi.com/support/utilities/digi_apiframes2.htm) would be nice if you want to interpret your HEX value of received data.

### XBee As Remote Control

[XBee 802.15.4 Digital Input/Output Line Passing - *ONLY for 802.15.4*](http://www.digi.com/support/kbase/kbaseresultdetl?id=2188)


### XBee Gateway

Using [Python XBee Library](https://code.google.com/p/python-xbee/).

Steps:

* Using X-CTU software load default firmware setting on XBee radio modules
* For the XBee connected to the gateway, the serial port must be in API mode, and the setting for escaping API communication between python and the host's XBee must match (the default is escaping disabled; the gateway's XBee would have AP=1)
* For the remote XBee connected to a sensor circuit, the API mode should match the gateway's XBee
* Optional Test: Using X-CTU to send a message with XBee API mode to the remote XBee radio and test the API mode is working fine. The remote XBee radio setting (e.g. the Channel Number and the PAN ID) should be the same as the gateway's XBee radio setting, and each radio should have a unique source address (MY)
* Gateway XBee send out "Remote AT Command" to change the default remote XBee radio setting.


### Reference

[Sparkfun XCTU and XBee Tutorial] (https://learn.sparkfun.com/tutorials/exploring-xbees-and-xctu#introduction)

[XBee-PRO 900HP Available Frequencies In Various Countries](http://www.digi.com/support/kbase/kbaseresultdetl?id=3417)

[The World of XBee](http://www.desert-home.com/p/the-world-of-xbee.html)

[Jeff's XBee Blog](https://jeffskinnerbox.wordpress.com/tag/xbee/)

[Use XBee API mode with Python](https://github.com/serdmanczyk/XBee_802.15.4_APIModeTutorial)
