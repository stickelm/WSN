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
 *  Author: Manuel Calvo Catalán & Octavio Benedí
 */

include_once $API_core.'complex_ajax_return_functions.php';
include_once $API_core.'json_api.php';
include_once $API_core.'form_fields_check.php';
include_once $API_core.'save_interfaces.php';
include_once $API_core.'parser_interfaces.php';
include_once $API_core.'modify_mac_filter.php';
include_once $API_core.'auto_code_generators.php';
include_once $base_plugin.'php/display_wifi_info.php';


function save_ath1($postData, $file="/etc/network/interfaces")
{

    global $base_plugin;

    exec("cp ".$base_plugin."data/ath1_conf ".$base_plugin."data/ath1_conf.confirmation");

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
    fwrite($fp, $postData['mac_essid_i']."\n");
    fwrite($fp, "\n");
    fwrite($fp, "\n");
    if($postData['freq'] == '2')
    {
        fwrite($fp, $postData['channel2']."\n");
        fwrite($fp, "11g\n");
    }
    else if($postData['freq'] == '5')
    {
        fwrite($fp, $postData['channel5']."\n");
        fwrite($fp, "11a\n");
    }
    
    fwrite($fp, $postData['tx_power']."\n");
    fwrite($fp, $postData['rate']."\n");

    fclose($fp);

    exec("paste ".$base_plugin."data/static_conf ".$base_plugin."data/temp_interfaces > ".$base_plugin."data/ath1_conf_tmp;
          cat ".$base_plugin."data/ath1_conf_tmp | tr '\t' ' ' > ".$base_plugin."data/ath1_conf;
          rm ".$base_plugin."data/ath1_conf_tmp;
          rm ".$base_plugin."data/temp_interfaces");
}

/* Returns tmp_interfaces file */
function generate_interfaces_file()
{
    global $base_plugin;
    exec("cat ".$base_plugin."../a0_ethernet/data/lo_conf > ".$base_plugin."/data/tmp_interfaces", $ret );
    exec("echo '' >> ".$base_plugin."data/tmp_interfaces");
    exec("cat ".$base_plugin."../a0_ethernet/data/eth0_conf >> ".$base_plugin."/data/tmp_interfaces", $ret );
    exec("echo '' >> ".$base_plugin."data/tmp_interfaces");
    exec("cat ".$base_plugin."../b0_wifi_ap/data/ath0_conf >> ".$base_plugin."/data/tmp_interfaces", $ret );
    exec("echo '' >> ".$base_plugin."data/tmp_interfaces");
    exec("cat ".$base_plugin."/data/ath1_conf >> ".$base_plugin."/data/tmp_interfaces", $ret );
    exec("echo '' >> ".$base_plugin."data/tmp_interfaces");

}

/* Copy files to System */
function set_interfaces($postData)
{
    /*
     * FALTA BORRAR ARCHIVO DE OTRAS POSIBLES CONFIGURACIONES
     */

    global $base_plugin;
    exec("sudo cp ".$base_plugin."/data/tmp_interfaces  /etc/network/interfaces", $ret );
}

function get_ath1_conf($file="/etc/network/interfaces")
{
    global $base_plugin;

    $writepath=$base_plugin.'data/ath0Conf';
    $fp=fopen($writepath,"w");

    $ath1_line = exec('cat /etc/network/interfaces | grep -n "auto ath1" | cut -d: -f1', $ath1Line);
    $end = 1000;

    $ath0_conf = exec("cat /etc/network/interfaces | sed -n '".$ath1_line.",".$end."p'", $ath1Conf);

    foreach ($ath1Conf as $line) {
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

        if(file_exists($base_plugin."../b0_wifi_ap/data/simpleInfo"))
        {
            exec('cat '.$base_plugin."../b0_wifi_ap/data/simpleInfo >> ".$base_dir.'core/structure/confirmation/data/toConfirm ');
        }

        exec('echo "<br>" >> '.$base_dir.'core/structure/confirmation/data/toConfirm');
        exec('echo "<b>Interfaz: </b> Wifi Mesh" >> '.$base_dir.'core/structure/confirmation/data/toConfirm');
        exec('echo "<b>Mode: </b> Ad-hoc" >> '.$base_dir.'core/structure/confirmation/data/toConfirm');
        exec('echo "<b>IP: </b> '.$postData['address'].'" >> '.$base_dir.'core/structure/confirmation/data/toConfirm');

        exec('echo "<br>" > '.$base_plugin."/data/simpleInfo");
        exec('echo "<b>Interfaz: </b> Wifi Mesh" >> '.$base_plugin."/data/simpleInfo");
        exec('echo "<b>Mode: </b> Ad-hoc" >> '.$base_plugin."/data/simpleInfo");
        exec('echo "<b>IP: </b> '.$postData['address'].'" >> '.$base_plugin."/data/simpleInfo");

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
                'mac_essid_i' => Array ('ms_mac','ms_mandatory')
                );

            if(are_form_fields_valid ($post_data, $fields_check_types))
            {
                save_ath1($post_data);
//response_additem("script", 'alert("'.$post_data.'")');

                generate_interfaces_file();
                exec('sudo cp /etc/network/interfaces /etc/network/interfaces.confirmation');
               // prepareConfirmation($post_data);
                set_interfaces($post_data);
            }
            break;        
    }
    exec("sudo remountro");

                response_additem("script", 'endnotify()');
        response_additem("script", 'notify("save", "Restart the machine to take effect<br><br>Once you restart you have to confirm the changes in the next 5 minutes.")');
                response_additem("script", 'fadenotify()');
    response_return();
}

/* ------------------------------------------------------------------------ */

include_once $API_core.'conf_file.php';
include_once $base_plugin.'php/paths.php';

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

?>
