## XBee As Sensor

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

[Use XBee API mode with Python]([Use XBee API mode with Python](https://github.com/serdmanczyk/XBee_802.15.4_APIModeTutorial)
