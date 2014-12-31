<?php
/*
 *  Copyright (C) 2008 Libelium Comunicaciones Distribuidas S.L.
 *  http://www.libelium.com
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *  Version 0.1
 *  Author: Joaquin Ruiz  
 */

// Predefined variables:
// $section contains the section folder name.
// echo "section=".$section."<br>";
// $plugin contains the plugin folder name.
// echo "plugin=".$plugin."<br>";
// $section and $plugin can be used to make a link to this plugin by just reference
// echo "<a href=\"index.php?section=$section&plugin=$plugin\">This plugin</a>"."<br>";
// $base_plugin contains the path that must be used as start to includes for
// plugin includes that need the local path.
// example: include_once $base_plugin.'php/my_include.php';
// echo "base_plugin=".$base_plugin."<br>";
// $url_plugin contains the url base that must be used to include html items
// such as images.
// example: <img src="'.url_plugin.'images/my_image.png">
// echo "url_plugin=".$url_plugin."<br>";
// $API_core contains the path to the core API folder.
// example: include_once $API_core.'is_active.php';
// echo "API_core=".$API_core."<br>";

// Plugin server produced data will returned to the ajax call that made the
// request.
include_once $API_core.'complex_ajax_return_functions.php';
include_once $API_core.'json_api.php';
include_once $API_core.'save_interfaces.php';
include_once $API_core.'form_fields_check.php';
include_once $API_core.'parser_interfaces.php';


function save_eth0($postData, $file="/etc/network/interfaces")
{
    global $base_plugin;

    exec("cp ".$base_plugin."data/eth0_conf ".$base_plugin."data/eth0_conf.confirmation");

    if ($postData['iface_sel'] == "dhcp")
    {
        exec("cp ".$base_plugin."data/dhcp_conf ".$base_plugin."data/eth0_conf", $ret);
    }
    else
    {
        $writepath=$base_plugin.'data/temp_interfaces';
	$writepath2=$base_plugin.'data/ip6';
        $fp=fopen($writepath,"w");
	$fp2=fopen($writepath2,"w");

        fwrite($fp, "\n");
        fwrite($fp, "\n");
        fwrite($fp, "\n");
        fwrite($fp, $postData['address']."\n");
        fwrite($fp, $postData['netmask']."\n");
        fwrite($fp, $postData['gateway']."\n");
        fwrite($fp, $postData['DNS1']." ".$postData['DNS2']."\n");
        fwrite($fp, $postData['broadcast']."\n");
	if (strstr($postData['ipv6'],"on")){
	    fwrite($fp2,"on");
	    
	    fwrite($fp,"#Start IPV6 configuration\niface eth0 inet6 static\npre-up modprobe ipv6\naddress ".$postData['address6']."\n");
	    fwrite($fp,"netmask ".$postData['netmask6']."\ngateway ".$postData['gateway6']."\n");   
	}
	else{
	    fwrite($fp2,"off");
	}
	
	fclose($fp2);
        fclose($fp);

        exec("paste ".$base_plugin."data/static_conf ".$base_plugin."data/temp_interfaces > ".$base_plugin."data/eth0_conf_tmp;
              cat ".$base_plugin."data/eth0_conf_tmp | tr '\t' ' ' > ".$base_plugin."data/eth0_conf ;
              rm ".$base_plugin."data/eth0_conf_tmp;
              rm ".$base_plugin."data/temp_interfaces");
    }
}



function get_eth0_conf($file="/etc/network/interfaces")
{
    global $base_plugin;

    $writepath=$base_plugin.'data/eth0Conf';
    $fp=fopen($writepath,"w");

    $ath0_line = exec('cat /etc/network/interfaces | grep -n "auto ath0" | cut -d: -f1', $ath0Line);
    $eth0_line = exec('cat /etc/network/interfaces | grep -n "auto eth0" | cut -d: -f1', $eth0Line);
    $ath0_line--;

    $ath0_conf = exec("cat /etc/network/interfaces | sed -n '".$eth0_line.",".$ath0_line."p'", $ath0Conf);

    foreach ($ath0Conf as $line) {
        fwrite($fp, $line."\n");
    }

    fclose($fp);
}

function generate_interfaces_file()
{
    global $base_plugin;
    exec("cat ".$base_plugin."/data/lo_conf > ".$base_plugin."/data/tmp_interfaces", $ret );
    exec("cat ".$base_plugin."/data/eth0_conf >> ".$base_plugin."/data/tmp_interfaces", $ret );
    exec("cat ".$base_plugin."../b0_wifi_ap/data/ath0_conf >> ".$base_plugin."/data/tmp_interfaces", $ret );
    
    exec("cat /mnt/lib/cfg/initialConf", $initConf);
    //if (strstr($initConf, "wifiMesh"))
    if(file_exists($base_plugin."../c0_wifi_mesh/data/ath1_conf"))
    {
        exec("cat ".$base_plugin."../c0_wifi_mesh/data/ath1_conf >> ".$base_plugin."../a0_ethernet/data/tmp_interfaces", $ret );        
    }
    /*if (strstr($initConf, "wifiScan"))
    if(file_exists($base_plugin."../c0_wifi_mesh/data/ath1_conf"))
    {
        exec("cat ".$base_plugin."../c0_wifi_mesh/data/ath1_conf >> ".$base_plugin."../a0_ethernet/data/tmp_interfaces", $ret );        
    }*/

}

function set_interfaces()
{
    global $base_plugin;
    exec("sudo cp ".$base_plugin."/data/tmp_interfaces  /etc/network/interfaces", $ret );
}

/*function prepareConfirmation($postData)
{
    /*
     * There are 3 files with simple info:
     * 1) default: setting from presets (generated in presets)
     * 2) toConfirm: new configuration (generated below)
     * 3) notConfirm: previus conf (generated when interfaz is built)     *
     *
    global $base_plugin, $base_dir;

    /* Copy interfaces to interfaces.confirmation *
    exec('sudo cp /etc/network/interfaces /etc/network/interfaces.confirmation');

        if(file_exists($base_plugin."data/simpleInfo"))
        {
            exec('cat '.$base_plugin."data/simpleInfo > ".$base_dir.'core/structure/confirmation/data/notConfirm ');
        }
        if(file_exists($base_plugin."../b0_wifi_ap/data/simpleInfo"))
        {
            exec('cat '.$base_plugin."../b0_wifi_ap/data/simpleInfo >> ".$base_dir.'core/structure/confirmation/data/notConfirm ');
        }
        if(file_exists($base_plugin."../c0_wifi_mesh/data/simpleInfo"))
        {
            exec('cat '.$base_plugin."../c0_wifi_mesh/data/simpleInfo >> ".$base_dir.'core/structure/confirmation/data/notConfirm ');
        }


        
        exec('echo "<b>Interfaz: </b> Ethernet" > '.$base_dir.'core/structure/confirmation/data/toConfirm');
        if ($postData['iface_sel'] == "dhcp")
        {
            exec('echo "<b>IP: </b> DHCP" >> '.$base_dir.'core/structure/confirmation/data/toConfirm');
        }
        else
        {
            exec('echo "<b>IP: </b> '.$postData['address'].'" >> '.$base_dir.'core/structure/confirmation/data/toConfirm');
        }
        exec('cp '.$base_dir.'core/structure/confirmation/data/toConfirm '.$base_plugin."/data/simpleInfo");

        if(file_exists($base_plugin."../b0_wifi_ap/data/simpleInfo"))
        {
            exec('cat '.$base_plugin."../b0_wifi_ap/data/simpleInfo >> ".$base_dir.'core/structure/confirmation/data/toConfirm ');
        }

        if(file_exists($base_plugin."../c0_wifi_mesh/data/simpleInfo"))
        {
            exec('cat '.$base_plugin."../c0_wifi_mesh/data/simpleInfo >> ".$base_dir.'core/structure/confirmation/data/toConfirm ');
        }

}*/


if (($_POST['type']=="save"))
{
    $post_data=jsondecode($_POST['form_fields']);
    if($post_data['iface_sel']=='static')
    {
        $fields_check_types = Array (
            'address'  => Array ('ms_ip','ms_mandatory'),
            'netmask'  => Array ('ms_ip','ms_mandatory'),
            'gateway'  => Array ('ms_ip','ms_mandatory'),
            'DNS1'  => Array ('ms_ip','ms_mandatory'),
            'DNS2'  => Array ('ms_ip','ms_mandatory'),
            'broadcast'  => Array ('ms_ip','ms_mandatory'),
	    'ipv6' => Array(),
	    'address6' => Array()
            );
    }
    else
    {
        $fields_check_types = Array ();
    }
    if(are_form_fields_valid ($post_data, $fields_check_types))
    {
        exec("sudo remountrw");

        save_eth0($post_data);
        generate_interfaces_file();
        exec('sudo cp /etc/network/interfaces /etc/network/interfaces.confirmation');

       // prepareConfirmation($post_data);

        set_interfaces();

        exec("sudo remountro");
        response_additem("script", 'endnotify()');
        response_additem("script", 'notify("save", "Restart the machine to take effect<br><br>Once you restart you have to confirm the changes in the next 5 minutes.")');
        response_additem("script", 'fadenotify()');

    }
    response_return();
}
elseif (($_POST['type']=="showDHCPconf"))
{
   $input=parse_interfaces('/etc/network/interfaces');
   $ip = exec("sudo ifconfig eth0 | grep 'inet addr' | cut -d: -f2 | cut -d' ' -f1", $v_ip);
   $bc = exec("sudo ifconfig eth0 | grep 'inet addr' | cut -d: -f3 | cut -d' ' -f1", $v_bc);
   $nm = exec("sudo ifconfig eth0 | grep 'inet addr' | cut -d: -f4 | cut -d' ' -f1", $v_nm);
   $gw = exec("sudo route | grep eth0$ | grep -i default | egrep -o [0-9]\{1,3\}\.[0-9]\{1,3\}\.[0-9]\{1,3\}\.[0-9]\{1,3\} | head -n1", $v_gw);
   $dns1 = exec("sudo cat /etc/resolv.conf | grep nameserver | cut -d' ' -f2 | head -n1", $v_dns1);
   $dns2 = exec("sudo cat /etc/resolv.conf | grep nameserver | cut -d' ' -f2 | tail -n1", $v_dns2);
   $DHCPconf = "IP: ".$v_ip[0]."<br>";
   $DHCPconf .= "Broadcast: ".$v_bc[0]."<br>";
   $DHCPconf .= "Netmask: ".$v_nm[0]."<br>";
   $DHCPconf .= "Gateway: ".$v_gw[0];
   /**/
      $v_gw_v = explode(".", $v_gw[0]);
      $v_ip_v = explode(".", $v_ip[0]);
      if (($v_gw_v[0] != $v_ip_v[0]) || ($v_gw_v[1] != $v_ip_v[1]) || ($v_gw_v[2] != $v_ip_v[2]))
      {
         $DHCPconf .= " (other interfaz)";
      }
      $DHCPconf .= "<br>";
   /**/
   $DHCPconf .= "DNS: ".$v_dns1[0].", ".$v_dns2[0]."<br>";
   echo "<div style='color:white;float:left;line-height:19px;'>".$DHCPconf."</div>
         <div style='float: right; background: #dedede; color: #343434;padding: 1px 1px 0 1px;cursor: pointer;' onclick='endnotify()'>X</div>";
}
?>
