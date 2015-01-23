

Digi Cloud SCI Call (Based on [XIG](https://code.google.com/p/xig/wiki/UserDocumentation#E._Setting_or_Getting_Remote_XBee_AT_Settings_via_Device_Cloud_R))

```html
<!-- 
See http://www.digi.com/wiki/developer/index.php/Rci for
an example of a python implementation on a NDS device to
handle this SCI request
-->
<sci_request version="1.0">
  <send_message>
    <targets>
      <device id="00000000-00000000-B827EBFF-FFB548F2"/>
    </targets>
    <rci_request version="1.1">
      <do_command target="xig">
        <at hw_address="00:13:A2:00:40:76:DB:2D!" command="D4" value="3" />
        <at hw_address="00:13:A2:00:40:76:DB:2D!" command="IR" value="30000" />
        <at hw_address="00:13:A2:00:40:76:DB:2D!" command="IC" value="0x000C" />
        <at hw_address="00:13:A2:00:40:76:DB:2D!" command="WR" apply="True" />
        <at hw_address="00:13:A2:00:40:76:DB:2D!" command="AP" />
      </do_command>
    </rci_request>
  </send_message>
</sci_request>

```
