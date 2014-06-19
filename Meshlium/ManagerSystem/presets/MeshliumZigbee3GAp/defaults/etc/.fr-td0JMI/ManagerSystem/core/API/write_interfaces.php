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
function write_interfaces ( $filepath,$input,$writepath='') {
    global $base_plugin;

    if ($writepath=='') {
        $writepath=$base_plugin.'data/temp_interfaces';
    }
    $fp=fopen($writepath,"w");    

    // for each interface in the list we will search for data and rules and will write them
    // to file given in $filepath
    $input['listainterfaces']=trim($input[listainterfaces]);
    $temp=explode(' ',$input[listainterfaces]);
    foreach($temp as $interface) {
        fwrite($fp,"auto ".$interface."\n");
        if ($input[$interface]['allow']) {
            fwrite($fp, "\tallow-hotplug ".$interface."\n");
        }
        if ($input[$interface]['iface']) {
            fwrite($fp, "\tiface ".$interface." inet ".$input[$interface][iface]."\n");
        }
        if ($input[$interface]['address']) {
            fwrite($fp, "\taddress ".$input[$interface][address]."\n");
        }
        if ($input[$interface]['netmask']) {
            fwrite($fp, "\tnetmask ".$input[$interface][netmask]."\n");
        }
        if ($input[$interface]['gateway']) {
            fwrite($fp, "\tgateway ".$input[$interface][gateway]."\n");
        }
        if ($input[$interface]['dns_primario']) {
            fwrite($fp, "\tdns-nameservers ".$input[$interface][dns_primario]);
            if ($input[$interface]['dns_secundario']) {
                fwrite($fp, " ".$input[$interface][dns_secundario]);
            }
            fwrite($fp, "\n");
        }
        if ($input[$interface]['broadcast']) {
            fwrite($fp, "\tbroadcast ".$input[$interface][broadcast]."\n");
        }
        //MAC ADDRESS
        if (isset($input[$interface]['hw-address'])) {
            fwrite($fp, "\thwaddress ether ".$input[$interface]['hw-address']."\n");
        }
        // FIRST WE MANAGE THE ATTRIBUTES WE MAY HAVE CHANGED IN WEB INTERFACE.
        // wlanconfig
        if (isset($input[$interface]['pre-up']['wlanconfig'])) {
            fwrite($fp, "\tpre-up wlanconfig ".$interface." ".$input[$interface]['pre-up']['wlanconfig']['type']);
            fwrite($fp, " wlandev ".$input[$interface]['pre-up']['wlanconfig']['BaseDevice']);
            fwrite($fp, " wlanmode ".$input[$interface]['pre-up']['wlanconfig']['mode']."\n");
        }
        // ESSID
        if (isset($input[$interface]['pre-up']['iwconfig']['essid'])) {
            fwrite($fp, "\tpre-up iwconfig ".$interface." essid ".$input[$interface]['pre-up']['iwconfig']['essid']."\n");
        }
        //HIDE IS IN UP.
        if (isset($input[$interface]['up']['iwpriv']['hide_ssid'])) {
            fwrite($fp, "\tup iwpriv ".$interface." hide_ssid ".$input[$interface]['up']['iwpriv']['hide_ssid']."\n");
        }

        // ESSID MAC ADDRESS
        if (isset($input[$interface]['pre-up']['iwconfig']['ap'])) {
            fwrite($fp, "\tpre-up iwconfig ".$interface." ap ".$input[$interface]['pre-up']['iwconfig']['ap']."\n");
        }
        //MODE
        if (isset($input[$interface]['pre-up']['iwconfig']['mode'])) {
            fwrite($fp, "\tpre-up iwconfig ".$interface." mode ".$input[$interface]['pre-up']['iwconfig']['mode']."\n");
        }
        // FRECUENCY
        // Fecuency depends on the channel we use. So we may know the frecuency by the channel value.
        // CHANNEL
        if (isset($input[$interface]['pre-up']['iwconfig']['channel'])) {
            fwrite($fp, "\tpre-up iwconfig ".$interface." channel ".$input[$interface]['pre-up']['iwconfig']['channel']."\n");
        }
        // MODE
        if (isset($input[$interface]['up']['iwpriv']['mode'])) {
            if ($input[$interface]['up']['iwpriv']['mode']=='3') {
                fwrite($fp, "\tup iwpriv ".$interface." mode 11a\n");
            }
            elseif ($input[$interface]['up']['iwpriv']['mode']=='1') {
                fwrite($fp, "\tup iwpriv ".$interface." mode 11b\n");
            }
            else {
                fwrite($fp, "\tup iwpriv ".$interface." mode 11g\n");
            }
        }

        //TX POWER
        if (isset($input[$interface]['pre-up']['iwconfig']['txpower'])) {
            fwrite($fp, "\tpre-up iwconfig ".$interface." txpower ".$input[$interface]['pre-up']['iwconfig']['txpower']."\n");
        }

        // FRAGMENTATION
        if (isset($input[$interface]['pre-up']['iwconfig']['frag'])) {
            fwrite($fp, "\tpre-up iwconfig ".$interface." frag ".$input[$interface]['pre-up']['iwconfig']['frag']."\n");
        }

        //RATE
        if (isset($input[$interface]['pre-up']['iwconfig']['rate'])) {
            fwrite($fp, "\tpre-up iwconfig ".$interface." rate ".$input[$interface]['pre-up']['iwconfig']['rate']."\n");
        }
        // NOW WE HAVE TO COPY ALL THE LINES WE HAVE IN INTERFACES TO KEEP USER MODIFICATIONS
        if ($input[$interface]['pre-up']) {
            for($vuelta=1;$vuelta<=$input[$interface]['pre-up']['num'];$vuelta++) {
                fwrite($fp, "\t".$input[$interface]['pre-up'][$vuelta]."\n");
            }
        }
        if ($input[$interface]['up']) {
            for($vuelta=1;$vuelta<=$input[$interface]['up']['num'];$vuelta++) {
                fwrite($fp, "\t".$input[$interface]['up'][$vuelta]."\n");
            }
        }
        if ($input[$interface]['post-up']) {
            for($vuelta=1;$vuelta<=$input[$interface]['post-up']['num'];$vuelta++) {
                fwrite($fp, "\t".$input[$interface]['post-up'][$vuelta]."\n");
            }
        }
        if ($input[$interface]['pre-down']) {
            for($vuelta=1;$vuelta<=$input[$interface]['pre-down']['num'];$vuelta++) {
                fwrite($fp, "\t".$input[$interface]['pre-down'][$vuelta]."\n");
            }
        }
        if ($input[$interface]['down']) {
            for($vuelta=1;$vuelta<=$input[$interface]['down']['num'];$vuelta++) {
                fwrite($fp, "\t".$input[$interface]['down'][$vuelta]."\n");
            }
        }
        if ($input[$interface]['post-down']) {
            for($vuelta=1;$vuelta<=$input[$interface]['post-down']['num'];$vuelta++) {
                fwrite($fp, "\t".$input[$interface]['post-down'][$vuelta]."\n");
            }
        }
        if ($input[$interface]['num']) {
            for($vuelta=1;$vuelta<=$input[$interface]['num'];$vuelta++) {
                fwrite($fp, "\t".$input[$interface][$vuelta]."\n");
            }
        }

        // START SECURITY
        // AUTHMODE IS DEFINED
        if (isset($input[$interface]['up']['iwpriv']['authmode'])) {
            fwrite($fp, "\tup iwpriv ".$interface." authmode ".$input[$interface]['up']['iwpriv']['authmode']."\n");
        }
        if (isset($input[$interface]['pre-up']['iwconfig']['key'])) {
            fwrite($fp, "\tpre-up iwconfig ".$interface." key s:".$input[$interface]['pre-up']['iwconfig']['key']."\n");
        }
        if (isset($input[$interface]['pre-up']['iwconfig']['enc'])) {
            fwrite($fp, "\tpre-up iwconfig ".$interface." enc s:".$input[$interface]['pre-up']['iwconfig']['enc']."\n");
        }
        if ($input[$interface]['pre-up']['iwconfig']['mode']=='managed') {
            if (isset($input[$interface]['post-up']['wpa_supplicant'])) {
                fwrite($fp, "\tpost-up /sbin/wpa_supplicant ".$input[$interface]['post-up']['wpa_supplicant']."\n");
            }
        }
        elseif ($input[$interface]['pre-up']['iwconfig']['mode']=='master') {
            if (isset($input[$interface]['post-up']['hostapd'])) {
                fwrite($fp, "\tpost-up /usr/sbin/hostapd ".$input[$interface]['post-up']['hostapd']."\n");
            }
        }
        // END SECURITY
        fwrite($fp,"\n");
    }

    fclose($fp);
    exec('sudo cp '.$writepath.' '.$filepath);
}    
?>