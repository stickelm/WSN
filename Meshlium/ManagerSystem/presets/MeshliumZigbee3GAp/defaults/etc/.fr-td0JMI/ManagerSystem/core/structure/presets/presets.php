<?php
exec("cat /mnt/lib/cfg/initialConf", $initConf);

?>


<div id="present_presets">
    <div style="width: 276px;height:617px; margin-right: 15px; border-right: 1px dashed #dedede; float: left;" >
        <h1 onclick="$('#preset_content').html($('#hidden_meshlium_ap').html())" class="preset">Meshlium AP</h1>
        <?php
        if(strstr($initConf['0'], "gprs"))
        {
        ?>
            <h1 onclick="$('#preset_content').html($('#hidden_meshlium_gprs_ap').html())" class="preset">Meshlium GPRS AP</h1>
        <?php
        }
        ?>
        <?php
        if(strstr($initConf['0'], "wifiMesh"))
        {
        ?>
            <h1 onclick="$('#preset_content').html($('#hidden_meshlium_mesh_ap').html())" class="preset">Meshlium Mesh AP</h1>
            <h1 onclick="$('#preset_content').html($('#hidden_meshlium_mesh_ap_gw').html())" class="preset">Meshlium Mesh AP GW</h1>
            <?php
            if(strstr($initConf['0'], "gprs"))
            {
            ?>
                <h1 onclick="$('#preset_content').html($('#hidden_meshlium_mesh_ap_gprs_gw').html())" class="preset">Meshlium Mesh GPRS AP (GW)</h1>
            <?php
            }
        }
        ?>

        <?php
        if(strstr($initConf['0'], "zigbee"))
        {
        ?>
            <br><hr><br>
            <h1 onclick="$('#preset_content').html($('#hidden_meshlium_zb_ap').html())" class="preset">Meshlium ZigBee AP</h1>
            <?php
            if(strstr($initConf['0'], "gprs"))
            {
            ?>
                <h1 onclick="$('#preset_content').html($('#hidden_meshlium_zb_gprs_ap').html())" class="preset">Meshlium ZigBee GPRS AP</h1>
            <?php
            }
            ?>
            <?php
            if(strstr($initConf['0'], "wifiMesh"))
            {
            ?>
                <h1 onclick="$('#preset_content').html($('#hidden_meshlium_zb_mesh_ap').html())" class="preset">Meshlium ZigBee Mesh AP</h1>
                <h1 onclick="$('#preset_content').html($('#hidden_meshlium_zb_mesh_ap_gw').html())" class="preset">Meshlium ZigBee Mesh AP GW</h1>
                <?php
                if(strstr($initConf['0'], "gprs"))
                {
                ?>
                    <h1 onclick="$('#preset_content').html($('#hidden_meshlium_zb_mesh_ap_gprs_gw').html())" class="preset">Meshlium ZigBee Mesh GPRS AP (GW)</h1>
                <?php
                }
            }
            ?>
        <?php

        }
        ?>
    </div>

    <div id="preset_content" style="overflow: auto; float:left;height:596px;margin-top:10px;width:672px; background: white;-moz-border-radius: 5px;position:relative;">

    <div id="not_selected_plugin">
            <table cellspacing=10><tbody><tr>
                <td><img alt="" src="core/structure/presets/icono-i.png" /></td>
                <td style="color: #343434;">Click on a preset to load</td>
            </tr></tbody></table></div>

    </div>

</div>


<div id="hidden_meshlium_ap" class="hidden">
    <h2>Meshlium AP</h2>
    <div class="ifacesBar">
        <b style="font-size: 11px;color:#DEDEDE;">INTERFACES: </b><span style="color:#DEDEDE;">ETHERNET + WIFI 2'4GHZ</span>
    </div>
    <div class="meshlium_ap_dia"></div><br>
    <div class="submitSet" onclick="setPresetAjax('meshlium_ap')">Set Preset</div><br class="cleaner"><br>
    <div style="padding: 0pt 25px;" >
        <p>
            Clients can connect to Meshlium via Wifi with laptops and smart phones and get access to the Internet. In order to give Internet access Meshlium uses the Ethernet connection. Just connect it to your hub or switch and it will get automatically an IP from your network using DHCP.
       <br><br> </p>
    </div>
</div>

<div id="hidden_meshlium_gprs_ap" class="hidden">
    <h2>Meshlium GPRS AP</h2>
    <div class="ifacesBar">
        <b style="font-size: 11px;color:#DEDEDE;">INTERFACES: </b><span style="color:#DEDEDE;">ETHERNET + WIFI 2'4GHZ + GPRS</span>
    </div>
    <div class="meshlium_gprs_ap_dia"></div><br>
    <div class="submitSet" onclick="setPresetAjax('meshlium_gprs_ap')">Set Preset</div><br class="cleaner"><br>
    <div  style="padding: 0pt 25px;">
        <p>
            Clients can connect to Meshlium via Wifi with laptops and smart phones and get access to the Internet. In order to give Internet access Meshlium uses the GPRS connection.
       <br><br> </p>
    </div>
</div>

<div id="hidden_meshlium_mesh_ap" class="hidden">
    <h2>Meshlium Mesh AP</h2>
    <div class="ifacesBar">
        <b style="font-size: 11px;color:#DEDEDE;">INTERFACES: </b><span style="color:#DEDEDE;">ETHERNET + WIFI 2'4GHZ + WIFI 5GHZ</span>
    </div>
    <div class="meshlium_mesh_ap_dia"></div><br>
    <div class="submitSet" onclick="setPresetAjax('meshlium_mesh_ap')">Set Preset</div><br class="cleaner"><br>
    <div  style="padding: 0pt 25px;">
        <p>
            Meshlium can work as a Mesh node. This means we can interconnect several Meshliums in order to share a common resource as an Internet connection. This way, the clients connected to a certain node can access to the Internet connection of a third node which is some hops far away the actual connecting point.
            <br><br>
            - Meshlium Mesh AP allows clients to connect via Wifi and creates links with other nodes using a second Wifi radio which operates in the 5GHz band.
       <br><br> </p>
    </div>
</div>

<div id="hidden_meshlium_mesh_ap_gw" class="hidden">
    <h2>Meshlium Mesh AP GW</h2>
    <div class="ifacesBar">
        <b style="font-size: 11px;color:#DEDEDE;">INTERFACES: </b><span style="color:#DEDEDE;">ETHERNET + WIFI 2'4GHZ + WIFI 5GHZ</span>
    </div>
    <div class="meshlium_mesh_ap_gw_dia"></div><br>
    <div class="submitSet" onclick="setPresetAjax('meshlium_mesh_ap_gw')">Set Preset</div><br class="cleaner"><br>
    <div  style="padding: 0pt 25px;">
        <p>
            Meshlium can work as a Mesh node. This means we can interconnect several Meshliums in order to share a common resource as an Internet connection. This way, the clients connected to a certain node can access to the Internet connection of a third node which is some hops far away the actual connecting point.
            <br><br>
            - Meshlium Mesh AP GW (gateway) is the node which shares its Internet connection with the rest of the network. It takes the Internet connection from the Ethernet interface. There is only one GW in the mesh network.
       <br><br> </p>
    </div>
</div>

<div id="hidden_meshlium_mesh_ap_gprs_gw" class="hidden">
    <h2>Meshlium Mesh AP GPRS GW</h2>
    <div class="ifacesBar">
        <b style="font-size: 11px;color:#DEDEDE;">INTERFACES: </b><span style="color:#DEDEDE;">ETHERNET + WIFI 2'4GHZ + WIFI 5GHZ + GPRS</span>
    </div>
    <div class="meshlium_mesh_gprs_ap_dia"></div><br>
    <div class="submitSet" onclick="setPresetAjax('meshlium_mesh_ap_gprs_gw')">Set Preset</div><br class="cleaner"><br>
    <div  style="padding: 0pt 25px;">
        <p>
            Meshlium can work as a Mesh node. This means we can interconnect several Meshliums in order to share a common resource as an Internet connection. This way, the clients connected to a certain node can access to the Internet connection of a third node which is some hops far away the actual connecting point.
            <br><br>
            - Meshlium Mesh GPRS AP (GPRS gateway) is the node which shares its Internet connection with the rest of the network. It takes the Internet connection from the GPRS interface. There is only one GW in the mesh network.
       <br><br> </p>
    </div>
</div>




<div id="hidden_meshlium_zb_ap" class="hidden">
    <h2>Meshlium Zigbee AP</h2>
    <div class="ifacesBar">
        <b style="font-size: 11px;color:#DEDEDE;">INTERFACES: </b><span style="color:#DEDEDE;">ETHERNET + WIFI 2'4GHZ</span>
    </div>
    <div class="meshlium_zb_ap_dia"></div>
    <div class="submitSet" onclick="setPresetAjax('meshlium_zb_ap')">Set Preset</div><br class="cleaner"><br>
    <div style="padding: 0pt 25px;" >
        <p>
            Meshlium can take the sensor data which comes from a Wireless Sensor Network (WSN) made with Waspmote sensor devices* and send it to the Internet using the Ethernet interface. Users can also connect directly to Meshlium using the Wifi interface to control it and access to the sensor data.
            <br><br>
            * http://www.libelium.com/waspmote
       <br><br> </p>
    </div>
</div>

<div id="hidden_meshlium_zb_gprs_ap" class="hidden">
    <h2>Meshlium Zigbee GPRS AP</h2>
    <div class="ifacesBar">
        <b style="font-size: 11px;color:#DEDEDE;">INTERFACES: </b><span style="color:#DEDEDE;">ETHERNET + WIFI 2'4GHZ + GPRS</span>
    </div>
    <div class="meshlium_zb_gprs_ap_dia"></div>
    <div class="submitSet" onclick="setPresetAjax('meshlium_zb_gprs_ap')">Set Preset</div><br class="cleaner"><br>
    <div  style="padding: 0pt 25px;">
        <p>
            Meshlium can take the sensor data which comes from a Wireless Sensor Network (WSN) made with Waspmote sensor devices* and send it to the Internet using the GPRS interface. Users can also connect directly to Meshlium using the Wifi interface to control it and access to the sensor data.
            <br><br>* http://www.libelium.com/waspmote
       <br><br> </p>
    </div>
</div>

<div id="hidden_meshlium_zb_mesh_ap" class="hidden">
    <h2>Meshlium Zigbee Mesh AP</h2>
    <div class="ifacesBar">
        <b style="font-size: 11px;color:#DEDEDE;">INTERFACES: </b><span style="color:#DEDEDE;">ETHERNET + WIFI 2'4GHZ + WIFI 5GHZ</span>
    </div>
    <div class="meshlium_zb_mesh_ap_dia"></div>
    <div class="submitSet" onclick="setPresetAjax('meshlium_zb_mesh_ap')">Set Preset</div><br class="cleaner"><br>
    <div  style="padding: 0pt 25px;">
        <p>
Meshlium can work as a ZigBee Mesh node. This means we can interconnect several nodes in order to share a common resource as an Internet connection. This way, the sensor nodes connected to a node via ZigBee can send the information to the Internet link set on a third node which is some hops far away the actual  point. In this hybrid ZigBee
<br><br>
- Meshlium ZigBee Mesh AP allows the sensor devices to connect via ZigBee and creates links with other nodes using a Wifi radio which operates in the 5GHz band. Users can also connect directly to Meshlium using the 2.4GHz Wifi interface to control it and access to the sensor data.
<br><br>
        </p>
    </div>
</div>

<div id="hidden_meshlium_zb_mesh_ap_gw" class="hidden">
    <h2>Meshlium Zigbee Mesh AP GW</h2>
    <div class="ifacesBar">
        <b style="font-size: 11px;color:#DEDEDE;">INTERFACES: </b><span style="color:#DEDEDE;">ETHERNET + WIFI 2'4GHZ + WIFI 5GHZ</span>
    </div>
    <div class="meshlium_zb_mesh_ap_gw_dia"></div>
    <div class="submitSet" onclick="setPresetAjax('meshlium_zb_mesh_ap_gw')">Set Preset</div><br class="cleaner"><br>
    <div  style="padding: 0pt 25px;">
        <p>
Meshlium can work as a ZigBee Mesh node. This means we can interconnect several nodes in order to share a common resource as an Internet connection. This way, the sensor nodes connected to a node via ZigBee can send the information to the Internet link set on a third node which is some hops far away the actual  point. In this hybrid ZigBee
<br><br>
- Meshlium ZigBee Mesh AP GW (gateway) is the node which shares its Internet connection with the rest of the network. It takes the Internet connection from the Ethernet interface. There is only one GW in the mesh network.
      <br><br>  </p>
    </div>
</div>

<div id="hidden_meshlium_zb_mesh_ap_gprs_gw" class="hidden">
    <h2>Meshlium Zigbee Mesh AP GPRS GW</h2>
    <div class="ifacesBar">
        <b style="font-size: 11px;color:#DEDEDE;">INTERFACES: </b><span style="color:#DEDEDE;">ETHERNET + WIFI 2'4GHZ + WIFI 5GHZ + GPRS</span>
    </div>
    <div class="meshlium_zb_mesh_gprs_ap_dia"></div>
    <div class="submitSet" onclick="setPresetAjax('meshlium_zb_mesh_ap_gprs_gw')">Set Preset</div><br class="cleaner"><br>
    <div  style="padding: 0pt 25px;">
        <p>
Meshlium can work as a ZigBee Mesh node. This means we can interconnect several nodes in order to share a common resource as an Internet connection. This way, the sensor nodes connected to a node via ZigBee can send the information to the Internet link set on a third node which is some hops far away the actual  point. In this hybrid ZigBee
<br><br>
- Meshlium ZigBee Mesh AP GW (gateway) is the node which shares its Internet connection with the rest of the network. It takes the Internet connection from the GPRS interface. There is only one GW in the mesh network.
       <br><br> </p>
    </div>
</div>
