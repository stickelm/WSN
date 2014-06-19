<?php

function showChangesPresets($ret)
{
         echo "<div style='margin-left: 15px;'>";

            echo "<h3>Actions:</h3>";
            foreach ($ret as $line) {
                echo "<p>".$line."</p>";
            }


            echo "<h3>Interfaces:</h3>";
            unset($ret);
            $return = exec('cat  /etc/network/interfaces', $ret);
            foreach ($ret as $number_line => $line)
            {
                if (strstr($line, "auto") && (!strstr($line, "up") || $number_line!='0'))
                    echo "<br>";
                echo "<p>".$line."</p>";
            }


            echo "<h3>DHCP range:</h3>";
            unset($ret);
            $return = exec('cat  /etc/dnsmasq.more.conf', $ret);
            foreach ($ret as $line) {
                echo "<p>".$line."</p>";
            }

            echo "<h3>Default gw:</h3>";
            unset($ret);
            $return = exec('sudo route', $ret);
            foreach ($ret as $line) {
                echo "<p>".$line."</p>";
            }

            echo "<h3>Bridges:</h3>";
            unset($ret);
            $return = exec('cat /var/www/ManagerSystem/plugins/a_interfaces/e0_join/data/join.conf', $ret);
            foreach ($ret as $line) {
                echo "<p>".$line."</p>";
            }

        echo "</div>";   
}


switch ($_POST['set']) {
    case 'meshlium_ap':
        $return = exec('sh /var/www/ManagerSystem/presets/MeshliumAp/setter/set_MeshliumAp.sh', $ret);
        echo "<br><h2>Meshlium has been configured as Meshlium AP</h2>";
        //showChangesPresets($ret);
        break;
    case 'meshlium_gprs_ap':
        $return = exec('sh /var/www/ManagerSystem/presets/MeshliumGprsAp/setter/set_MeshliumGprsAp.sh', $ret);
        echo "<h2>Meshlium has been configured as Meshlium GPRS AP</h2>";
        //showChangesPresets($ret);
        break;
    case 'meshlium_mesh_ap':
        $return = exec('sh /var/www/ManagerSystem/presets/MeshliumMeshAp/setter/set_MeshliumMeshAp.sh', $ret);
        echo "<h2>Meshlium has been configured as Meshlium Mesh AP</h2>";
        //showChangesPresets($ret);
        break;
    case 'meshlium_mesh_ap_gw':
        $return = exec('sh /var/www/ManagerSystem/presets/MeshliumMeshApGw/setter/set_MeshliumMeshApGw.sh', $ret);
        echo "<h2>Meshlium has been configured as Meshlium Mesh AP GW</h2>";
        //showChangesPresets($ret);
        break;
    case 'meshlium_mesh_ap_gprs_gw':
        $return = exec('sh /var/www/ManagerSystem/presets/MeshliumMeshGprsAp/setter/set_MeshliumMeshGprsAp.sh', $ret);
        echo "<h2>Meshlium has been configured as Meshlium Mesh AP GPRS GW</h2>";
        //showChangesPresets($ret);
        break;
    
    
    case 'meshlium_zb_ap':
        $return = exec('sh /var/www/ManagerSystem/presets/MeshliumZigbeeAp/setter/set_MeshliumZigbeeAp.sh', $ret);
        echo "<br><h2>Meshlium has been configured as Meshlium Zigbee AP</h2>";
        //showChangesPresets($ret);
        break;
    case 'meshlium_zb_gprs_ap':
        $return = exec('sh /var/www/ManagerSystem/presets/MeshliumZigbeeGprsAp/setter/set_MeshliumZigbeeGprsAp.sh', $ret);
        echo "<h2>Meshlium has been configured as Meshlium Zigbee GPRS AP</h2>";
        //showChangesPresets($ret);
        break;
    case 'meshlium_zb_mesh_ap':
        $return = exec('sh /var/www/ManagerSystem/presets/MeshliumZigbeeMeshAp/setter/set_MeshliumZigbeeMeshAp.sh', $ret);
        echo "<h2>Meshlium has been configured as Meshlium Zigbee Mesh AP</h2>";
        //showChangesPresets($ret);
        break;
    case 'meshlium_zb_mesh_ap_gw':
        $return = exec('sh /var/www/ManagerSystem/presets/MeshliumZigbeeMeshApGw/setter/set_MeshliumZigbeeMeshApGw.sh', $ret);
        echo "<h2>Meshlium has been configured as Meshlium Zigbee Mesh AP GW</h2>";
        //showChangesPresets($ret);
        break;
    case 'meshlium_zb_mesh_ap_gprs_gw':
        $return = exec('sh /var/www/ManagerSystem/presets/MeshliumZigbeeMeshGprsAp/setter/set_MeshliumZigbeeMeshGprsAp.sh', $ret);
        echo "<h2>Meshlium has been configured as Meshlium Zigbee Mesh AP GPRS GW</h2>";
        //showChangesPresets($ret);
        break;


    default:
        break;
}
?>
