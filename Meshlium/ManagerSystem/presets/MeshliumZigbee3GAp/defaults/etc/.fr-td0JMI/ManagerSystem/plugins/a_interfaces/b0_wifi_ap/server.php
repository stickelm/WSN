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
 *  Author: Octavio Benedí  
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
include_once $API_core.'form_fields_check.php';
include_once $API_core.'save_interfaces.php';
include_once $API_core.'parser_interfaces.php';
include_once $API_core.'modify_mac_filter.php';
include_once $API_core.'auto_code_generators.php';
include_once $base_plugin.'php/display_mac_filter.php';
include_once $base_plugin.'php/display_wifi_info.php';



/* Returns  ath0_conf
 * Use      static_conf
 * Create   temp_interfaces ath0_conf_tmp
 * Remove   temp_interfaces ath0_conf_tmp
 */
function save_ath0($postData, $file="/etc/network/interfaces")
{
    global $base_plugin;

    exec("cp ".$base_plugin."data/ath0_conf ".$base_plugin."data/ath0_conf.confirmation");

    $writepath=$base_plugin.'data/temp_interfaces';
    $fp=fopen($writepath,"w");

    fwrite($fp, "\n");
    fwrite($fp, "\n");
    fwrite($fp, $postData['address']."\n");
    fwrite($fp, $postData['netmask']."\n");
    fwrite($fp, $postData['DNS1']." ".$postData['DNS2']."\n");
    fwrite($fp, $postData['broadcast']."\n");
    fwrite($fp, "\n");
    fwrite($fp, $postData['essid']."\n");
    if (isset ($postData['hide']))
        fwrite($fp, "1"."\n");
    if (!isset ($postData['hide']))
        fwrite($fp, "0"."\n");
    fwrite($fp, "\n");
    fwrite($fp, $postData['channel2']."\n");
    if (trim($postData['mode-abg']) == '1')
        fwrite($fp, "11b \n");
    if (trim($postData['mode-abg']) == '2')
        fwrite($fp, "11g \n");
    fwrite($fp, $postData['tx_power']."\n");
    fwrite($fp, $postData['rate']."\n");
    fwrite($fp, $postData['tx_power']."\n");
    fwrite($fp, $postData['rate']."\n");

    fclose($fp);

    exec("paste ".$base_plugin."data/static_conf ".$base_plugin."data/temp_interfaces > ".$base_plugin."data/ath0_conf_tmp;
          cat ".$base_plugin."data/ath0_conf_tmp | tr '\t' ' ' > ".$base_plugin."data/ath0_conf;
          rm ".$base_plugin."data/ath0_conf_tmp;
          rm ".$base_plugin."data/temp_interfaces");
}

/* Case 'wep'
 * Modify  ath0_conf
 * Return  wep_pass
 * Use     addWEP_conf
 * Create  ath0_conf_tmp
 * Remove  ath0_conf_tmp 
 * 
 * Case 'wpa' 
 * Returns   hostapd_ath0.conf
 * Use       hostapd_ath0.conf_conf
 * Create    temp_wpa ath0_conf_tmp
 * Remove    temp_wpa ath0_conf_tmp
 */
function set_security($postData)
{
    global $base_plugin;

    if(file_exists($base_plugin."data/hostapd_ath0.conf"))
    {
        exec("rm ".$base_plugin."data/hostapd_ath0.conf");
    }

    if(file_exists($base_plugin."data/wep_pass"))
    {
        exec("rm ".$base_plugin."data/wep_pass");
    }

    if(file_exists($base_plugin."data/wpa_pass"))
    {
        exec("rm ".$base_plugin."data/wpa_pass");
    }

    if($postData['protocol'] == 'wep')
    {
        $writepath=$base_plugin.'data/wep_pass';
        $fp=fopen($writepath,"w");

        fwrite($fp, "\n");
        fwrite($fp, $postData['wep_pass']);
        fclose($fp);

        exec("paste ".$base_plugin."data/addWEP_conf ".$base_plugin."data/wep_pass > ".$base_plugin."data/ath0_conf_tmp;
              cat ".$base_plugin."data/ath0_conf_tmp | tr '\t' ' ' >> ".$base_plugin."data/ath0_conf ;
              rm ".$base_plugin."data/ath0_conf_tmp");
        
        
    }
    elseif($postData['protocol'] == 'wpa')
    {
        $pass = exec("wpa_passphrase ".$postData['essid']." ".$postData['psk_pass'], $ret);
        $psk=explode("psk=", $ret['3']);

        $writepath=$base_plugin.'data/wpa_pass';
        $fp=fopen($writepath,"w");

        fwrite($fp, "\n");
        fwrite($fp, $postData['psk_pass']);
        fclose($fp);

        $writepath=$base_plugin.'data/temp_wpa';
        $fp=fopen($writepath,"w");

        fwrite($fp, "\n");
        fwrite($fp, "\n");
        fwrite($fp, $postData['essid']."\n");
        if ($postData['mode-abg'] == '1')
            fwrite($fp, "b\n");
        if ($postData['mode-abg'] == '2')
            fwrite($fp, "g\n");
        fwrite($fp, $postData['channel2']."\n");
        fwrite($fp, "\n");
        fwrite($fp, "\n");
        fwrite($fp, "\n");
        fwrite($fp, "\n");
        fwrite($fp, "\n");
        fwrite($fp, "\n");
        fwrite($fp, "\n");
        fwrite($fp, "\n");
        fwrite($fp, "\n");
        fwrite($fp, "\n");
        fwrite($fp, "\n");
        fwrite($fp, "\n");
        fwrite($fp, "\n");
        fwrite($fp, "\n");
        fwrite($fp, "\n");
        fwrite($fp, $psk['1']."\n");

        fclose($fp);

        exec("cat ".$base_plugin."data/addWPA_conf >> ".$base_plugin."data/ath0_conf");
        exec("echo '' >> ".$base_plugin."data/ath0_conf");

        exec("paste ".$base_plugin."data/hostapd_ath0.conf_conf ".$base_plugin."data/temp_wpa > ".$base_plugin."data/ath0_conf_tmp;
              cat ".$base_plugin."data/ath0_conf_tmp | tr -d '\t' > ".$base_plugin."data/hostapd_ath0.conf;
              rm ".$base_plugin."data/ath0_conf_tmp;
              rm ".$base_plugin."data/temp_wpa");
    }
}

/* Returns tmp_interfaces file */
function generate_interfaces_file()
{
    global $base_plugin;
    
    exec("cat ".$base_plugin."../a0_ethernet/data/lo_conf > ".$base_plugin."/data/tmp_interfaces", $ret );
    exec("echo '' >> ".$base_plugin."data/tmp_interfaces");
    exec("cat ".$base_plugin."../a0_ethernet/data/eth0_conf >> ".$base_plugin."/data/tmp_interfaces", $ret );
    exec("echo '' >> ".$base_plugin."data/tmp_interfaces");
    exec("cat ".$base_plugin."/data/ath0_conf >> ".$base_plugin."/data/tmp_interfaces", $ret );
    exec("echo '' >> ".$base_plugin."data/tmp_interfaces");

    exec("cat /mnt/lib/cfg/initialConf", $initConf);
    //if (strstr($initConf, "wifiMesh"))
    if(file_exists($base_plugin."../c0_wifi_mesh/data/ath1_conf"))
    {
        exec("cat ".$base_plugin."../c0_wifi_mesh/data/ath1_conf >> ".$base_plugin."/data/tmp_interfaces", $ret );
    }

}

/* Copy files to System */
function set_interfaces($postData)
{
    /*
     * FALTA BORRAR ARCHIVO DE OTRAS POSIBLES CONFIGURACIONES
     */

    global $base_plugin;
    if($postData['protocol'] == 'wpa')
    {
        exec("sudo cp ".$base_plugin."/data/hostapd_ath0.conf  /etc/hostapd/hostapd_ath0.conf", $ret );
    }
    exec("sudo cp ".$base_plugin."/data/tmp_interfaces  /etc/network/interfaces", $ret );
    exec("sudo cp ".$base_plugin."/data/temp_dnsmasq  /etc/dnsmasq.more.conf", $ret );
}

/* Returns temp_dnsmasq file */
function save_dnsmasq_ath0($postData, $file="/etc/dnsmasq.more.conf")
{
    global $base_plugin;

    $writepath=$base_plugin.'data/temp_dnsmasq';
    $fp=fopen($writepath,"w");

    fwrite($fp, "dhcp-range=ath0,".$postData['dhcp_start_ath0'].",".$postData['dhcp_end_ath0'].",".$postData['dhcp_expire_ath0']."h\n");
    fwrite($fp, "dhcp-option=6,".$postData['DNS1'].",".$postData['DNS2']."\n");
    fwrite($fp, "dhcp-leasefile=/var/tmp/dnsmasq.leases");

    fclose($fp);
}

/* Get ath0 conf from /etc/network/interfaces file*/
function get_ath0_conf($file="/etc/network/interfaces")
{
    global $base_plugin;

    $writepath=$base_plugin.'data/ath0Conf';
    $fp=fopen($writepath,"w");

    $ath0_line = exec('cat /etc/network/interfaces | grep -n "auto ath0" | cut -d: -f1', $ath0Line);
    $ath1_line = exec('cat /etc/network/interfaces | grep -n "auto ath1" | cut -d: -f1', $ath1Line);

    if ($ath1_line=="")
        $ath1_line = 1000;
    else
        $ath1_line--;

    $ath0_conf = exec("cat /etc/network/interfaces | sed -n '".$ath0_line.",".$ath1_line."p'", $ath0Conf);

    foreach ($ath0Conf as $line) {
        fwrite($fp, $line."\n");
    }

    fclose($fp);
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

        if(file_exists($base_plugin."../a0_ethernet/data/simpleInfo"))
        {
            exec('cat '.$base_plugin."../a0_ethernet/data/simpleInfo > ".$base_dir.'core/structure/confirmation/data/notConfirm ');
        }
        if(file_exists($base_plugin."data/simpleInfo"))
        {
            exec('cat '.$base_plugin."data/simpleInfo >> ".$base_dir.'core/structure/confirmation/data/notConfirm ');
        }
        if(file_exists($base_plugin."../c0_wifi_mesh/data/simpleInfo"))
        {
            exec('cat '.$base_plugin."../c0_wifi_mesh/data/simpleInfo >> ".$base_dir.'core/structure/confirmation/data/notConfirm ');
        }



        if(file_exists($base_plugin."../a0_ethernet/data/simpleInfo"))
        {
            exec('cat '.$base_plugin."../a0_ethernet/data/simpleInfo > ".$base_dir.'core/structure/confirmation/data/toConfirm ');
        }

        exec('echo "<br>" >> '.$base_dir.'core/structure/confirmation/data/toConfirm');
        exec('echo "<b>Interfaz: </b> Wifi AP" >> '.$base_dir.'core/structure/confirmation/data/toConfirm');
        exec('echo "<b>Mode: </b> Manager" >> '.$base_dir.'core/structure/confirmation/data/toConfirm');
        exec('echo "<b>IP: </b> '.$postData['address'].'" >> '.$base_dir.'core/structure/confirmation/data/toConfirm');

        exec('echo "<br>" > '.$base_plugin."/data/simpleInfo");
        exec('echo "<b>Interfaz: </b> Wifi AP" >> '.$base_plugin."/data/simpleInfo");
        exec('echo "<b>Mode: </b> Manager" >> '.$base_plugin."/data/simpleInfo");
        exec('echo "<b>IP: </b> '.$postData['address'].'" >> '.$base_plugin."/data/simpleInfo");

        if(file_exists($base_plugin."../c0_wifi_mesh/data/simpleInfo"))
        {
            exec('cat '.$base_plugin."../c0_wifi_mesh/data/simpleInfo >> ".$base_dir.'core/structure/confirmation/data/toConfirm ');
        }

}*/



if ($_POST['type']=="complex")
{
    exec("sudo remountrw");
    switch($_POST['action'])
    {
        case 'default':
            $post_data=jsondecode($_POST['form_fields']);

             $fields_check_types = Array (
                'address'  => Array ('ms_ip','ms_mandatory'),
                'netmask'  => Array ('ms_ip','ms_mandatory'),
                'DNS1'  => Array ('ms_ip','ms_mandatory'),
                'DNS2'  => Array ('ms_ip','ms_mandatory'),
                'broadcast'  => Array ('ms_ip','ms_mandatory'),
                'essid' => Array ('ms_mandatory'),
                'dhcp_start_ath0' => Array ('ms_ip','ms_mandatory'),
                'dhcp_end_ath0' => Array ('ms_ip','ms_mandatory'),
                'dhcp_expire_ath0' => Array ('ms_numerical','ms_mandatory')
                );

            if(are_form_fields_valid ($post_data, $fields_check_types))
            {
                //exec("sudo remountrw");

                save_ath0($post_data);          // OK
                set_security($post_data);       // OK
                save_dnsmasq_ath0($post_data);  // OK
                generate_interfaces_file();     // OK
                exec('sudo cp /etc/network/interfaces /etc/network/interfaces.confirmation');     // OK
               // prepareConfirmation($post_data);
                set_interfaces($post_data);     // OK

                //exec("sudo remountro");

                response_additem("script", 'endnotify()');
        response_additem("script", 'notify("save", "Restart the machine to take effect<br><br>Once you restart you have to confirm the changes in the next 5 minutes.")');
                response_additem("script", 'fadenotify()');
            }
            break;
    }
    exec("sudo remountro");


    response_return();
}

/* ------------------------------------------------------------------------ */

include_once $API_core.'conf_file.php';
include_once $base_plugin.'php/paths.php';

/* ------------------------------------------------------------------------ */

function execute ($cmd)
/* ------------------------------------------------------------------------ */
{
    //global $output;
    exec ("sudo ".$cmd, $return);
    //$output = array_merge ($output, $return); // DEBUG LOG
}
/* ------------------------------------------------------------------------ */

function save_msg ($msg)
/* ------------------------------------------------------------------------ */
{
    //response_additem ("html", "<fieldset><h2>'.$msg.'</h2></fieldset>" ,"output");
    response_additem ("script", "alert('".$msg."')");
}
/* ------------------------------------------------------------------------ */

function error_msg ($msg)
/* ------------------------------------------------------------------------ */
{
    //response_additem ("html", "<fieldset><h2>'.$msg.'</h2></fieldset>" ,"output");
    response_additem ("script", "alert('".$msg."')");
}
/* ------------------------------------------------------------------------ */

function save_security_config ($iface, $post_data, $mode)
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $API_core;

    include_once $API_core.'parser_interfaces.php';
    $interfaces = parse_interfaces ($paths['interfaces']);

    $hostapd_file = $paths['hostapd']."_".$iface.".conf";
    response_additem ("script", "alert('".$hostapd_file."')");
    response_additem ("script", "alert('".$paths['security']."')");

    $security = load_conf_file ($paths['security']);
    $security[$iface] = array();
    $security[$iface]['protocol'] = $post_data['protocol'];

    $valid_request = false;

    switch ($post_data['protocol'])
    {
        case 'none':
            $interfaces=remove_wep ($iface, $interfaces, $mode);
            $interfaces=remove_wpa ($iface, $hostapd_file, $interfaces, $mode);
            include_once $API_core.'write_interfaces.php';
            write_interfaces ($paths['interfaces'], $interfaces);
            restart_interface ($iface);
            $valid_request = true;
            break;

        case 'wep':
            if ( is_wep_pass_valid ($post_data) )
            {
                response_additem ("script", "alert('he entrado en wep')");
                $interfaces=remove_wpa ($iface, $hostapd_file, $interfaces, $mode);
                $security = add_wep ($iface, $post_data, $security, $interfaces, $mode);
                restart_interface ($iface);
                $valid_request = true;
            }
            break;

        case 'wpa':
            if ( is_wpa_form_valid ($iface, $post_data, $interfaces) )
            {
                $interfaces=remove_wep ($iface, $interfaces, $mode);
                $security = add_wpa ($iface, $post_data, $security, $hostapd_file, $interfaces, $mode);
                $valid_request = true;
            }
            break;
    }

    if ($valid_request)
    {
        save_conf_file ($paths['security'], $security);
        return true;
    }
    return false;
}
/* ------------------------------------------------------------------------ */

function is_wpa_pass_valid ($post_data)
/* ------------------------------------------------------------------------ */
{
    $len_wpa_pass = strlen ($post_data['psk_pass']);
    return ($len_wpa_pass >= 8 && $len_wpa_pass <= 63);
}
/* ------------------------------------------------------------------------ */

function is_wpa_psk_form_valid ($post_data)
/* ------------------------------------------------------------------------ */
{
    $is_valid = false;

    if ( !is_wpa_pass_valid ($post_data) )
    {
        error_msg("Invalid WPA PSK password long.");
    }
    elseif ( $post_data['psk_pass'] != $post_data['cnf_psk_pass'] )
    {
        error_msg("WPA PSK password missmatch.");
    }
    else
    {
        $is_valid = true;
    }

    return $is_valid;
}
/* ------------------------------------------------------------------------ */

function is_wpa_form_valid ($iface, $post_data, $interfaces)
/* ------------------------------------------------------------------------ */
{
    $is_valid = false;

    $wpa_checked = ($post_data['wpa_psk_ckb'] || $post_data['wpa_eap_ckb']);

    if ( !$wpa_checked )
    {
        error_msg("At least one WPA method must be selected.");
    }
    elseif ( $post_data['wpa_psk_ckb'] && !is_wpa_psk_form_valid ($post_data) )
    {}
    elseif ( $post_data['wpa_eap_ckb'] && $post_data['radius_connection'] == "remote" &&
             !is_wpa_eap_remote_form_valid ($post_data) )
    {}
    else
    {
        $is_valid = true;
    }

    return $is_valid;
}
/* ------------------------------------------------------------------------ */

function add_iface_data ($iface, $interfaces, $hostapd)
/* ------------------------------------------------------------------------ */
{
    $essid = $interfaces[$iface]['pre-up']['iwconfig']['essid'];
    $hw_mode = $interfaces[$iface]['up']['iwpriv']['mode'];

    switch ($hw_mode)
    {
        case 1: $hw_mode = 'b'; break;
        case 2: $hw_mode = 'g'; break;
        case 3: $hw_mode = 'a'; break;
    }
    $channel = $interfaces[$iface]['pre-up']['iwconfig']['channel'];

    $hostapd['interface'] = $iface;
    $hostapd['ssid'] = $essid;
    $hostapd['hw_mode'] = $hw_mode;
    $hostapd['channel'] = $channel;

    return $hostapd;
}
/* ------------------------------------------------------------------------ */

function restart_hostapd ($hostapd_file)
/* ------------------------------------------------------------------------ */
{
    exec ("sudo ps ax | grep ".$hostapd_file." | grep -v grep | awk '{print $1;}'", $pids);
    if ( count($pids) > 0 )
    {
        execute ('kill -1 '.$pids[0]);
    } else {
        execute ('/usr/sbin/hostapd -B '.$hostapd_file);
    }
}

/* ------------------------------------------------------------------------ */

function add_wpa_psk ($iface, $post_data, $security, $hostapd)
/* ------------------------------------------------------------------------ */
{
    $hostapd['wpa_key_mgmt'] = "WPA-PSK ";
    // wpa_passphrase essid password
    exec('wpa_passphrase '.$hostapd['ssid'].' '.$post_data['psk_pass'], $return);
    $hostapd['wpa_psk'] = substr ($return[3], 5);

    $security[$iface]['wpa_psk'] = $post_data['psk_pass'];
    $security[$iface]['wpa_mgmt'][] = 'psk';

    return array ($security, $hostapd);
}
/* ------------------------------------------------------------------------ */

function add_wpa ($iface, $post_data, $security, $hostapd_file, $interfaces, $mode)
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $API_core;

    include_once $API_core.'parser_hostapd.php';
    $hostapd = parse_hostapd ($paths['hostapd_skeleton']);

    $hostapd = add_iface_data ($iface, $interfaces, $hostapd);

    if ($post_data['wpa_psk_ckb'])
    {
        list ($security, $hostapd) = add_wpa_psk ($iface, $post_data, $security, $hostapd);
    }

    // Write hostapd configuration file
    include_once $API_core.'write_hostapd.php';
    write_hostapd ($hostapd_file, $hostapd);

    //Write interfaces
    $interfaces[$iface]['post-up']['hostapd'] = '-B '.$hostapd_file;
    include_once $API_core.'write_interfaces.php';
    write_interfaces ($paths['interfaces'], $interfaces);

    // Apply changes
    stop_wpa_supplicant('/etc/wpa_supplicant_'.$iface);
    stop_hostapd($hostapd_file);
    restart_interface($iface);

    return $security;
}
/* ------------------------------------------------------------------------ */

function is_wep_pass_valid ($post_data)
/* ------------------------------------------------------------------------ */
{
    $len_pass = strlen($post_data['wep_pass']);

    switch ($post_data['key_size'])
    {
        case "40":
            $is_valid =  $len_pass == 5;
            break;

        case "104":
            $is_valid = $len_pass == 13;
            break;

        default:
            $is_valid = false;
    }

    if ( !$is_valid )
    {
        error_msg("Invalid WEP password long.");
    }
    else
    {
        if ( $post_data['wep_pass'] != $post_data['cnf_wep_pass'] )
        {
            error_msg("WEP password missmatch.");
        }
    }

    return $is_valid;
}
/* ------------------------------------------------------------------------ */

function add_wep ($iface, $post_data, $security, $interfaces, $mode)
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $API_core;

    $interfaces[$iface]['up']['iwpriv']['authmode'] = 1;
    $interfaces[$iface]['pre-up']['iwconfig']['enc'] = $post_data['wep_pass'];
    unset($interfaces[$iface]['pre-up']['iwconfig']['key']);

    include_once $API_core.'write_interfaces.php';
    write_interfaces ($paths['interfaces'], $interfaces);

    $security[$iface]['wep_pass'] = $post_data['wep_pass'];

    return $security;
}
/* ------------------------------------------------------------------------ */

function remove_wep ($iface, $interfaces, $mode)
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $API_core;

    if ( isset ($interfaces[$iface]['up']['iwpriv']['authmode']) )
    {
        unset ($interfaces[$iface]['up']['iwpriv']['authmode']);
        unset ($interfaces[$iface]['pre-up']['iwconfig']['enc']);
        unset ($interfaces[$iface]['pre-up']['iwconfig']['key']);
    }
    return $interfaces;
}
/* ------------------------------------------------------------------------ */

function remove_wpa ($iface, $hostapd_file, $interfaces, $mode)
/* ------------------------------------------------------------------------ */
{
    global $paths;
    global $API_core;

    // Remove hostapd instances
    exec ("sudo ps ax | grep ".$hostapd_file." | grep -v grep | awk '{print $1;}'", $pids);

    foreach ($pids as $pid)
    {
        execute ('kill -9 '.$pid);
    }

    unset($interfaces[$iface]['post-up']['hostapd']);

    // Remove wpa_supplicant instances
    exec ("sudo ps ax | grep wpa_supplicant_$iface | grep -v grep | awk '{print $1;}'", $pids);

    foreach ($pids as $pid)
    {
        execute ('kill -9 '.$pid);
    }
    unset($interfaces[$iface]['post-up']['wpa_supplicant']);

    return $interfaces;
}
/* ------------------------------------------------------------------------ */

function restart_interface ($iface)
/* ------------------------------------------------------------------------ */
{
    execute ('ifdown '.$iface);
    execute ('wlanconfig '.$iface.' destroy');
    execute ('ifup '.$iface.' >/dev/null 2>&1 &');
}
/* ------------------------------------------------------------------------ */
/* ------------------------------------------------------------------------ */

function stop_hostapd ($hostapd_file)
/* ------------------------------------------------------------------------ */
{
    exec ("sudo ps ax | grep ".$hostapd_file." | grep -v grep | awk '{print $1;}'", $pids);
    foreach ($pids as $pid)
    {
        execute ('kill -9 '.$pid);
    }
}
/* ------------------------------------------------------------------------ */

function stop_wpa_supplicant ($wpa_supplicant_file)
/* ------------------------------------------------------------------------ */
{
    exec ("sudo ps ax | grep ".$wpa_supplicant_file." | grep -v grep | awk '{print $1;}'", $pids);
    foreach ($pids as $pid)
    {
        execute ('kill -9 '.$pid);
    }
}
?>
