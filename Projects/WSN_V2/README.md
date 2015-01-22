

Digi Cloud SCI Call

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
        <at hw_address="00:13:A2:00:40:76:DB:2D!" command="AP" />
      </do_command>
    </rci_request>
  </send_message>
</sci_request>

```
