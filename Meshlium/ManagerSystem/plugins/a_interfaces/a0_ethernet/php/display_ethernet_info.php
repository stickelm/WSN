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
function make_eth($input,$path,$interfaz)
{
    global $url_plugin;
    global $section;
    global $plugin;
    global $base_dir;
	$input=parse_interfaces($path);
	exec("cat ".$url_plugin."data/ip6", $ip6);
	exec("sudo ifconfig eth0 | grep :Link", $temp6);
	$address6generated0=explode('/', $temp6[0]);
	$address6generated1=explode(':', $address6generated0[0]);
	$address6generated="2001::".$address6generated1[3].":".$address6generated1[4].":".$address6generated1[5].":".$address6generated1[6];
	/* exec('echo "<b>Interfaz: </b> Ethernet" > '.$base_plugin."data/simpleInfo");
        if ($input[$interfaz]['iface'] == "dhcp")
        {
            exec('echo "<b>IP: </b> DHCP" >> '.$base_plugin."data/simpleInfo");
        }
        else
        {
            exec('echo "<b>IP: </b> '.$input[$interfaz]['address'].'" >> '.$base_plugin."data/simpleInfo");
        }
        exec('echo "" >> '.$base_plugin."data/simpleInfo");*/
    $list.='<div class="title2">Ethernet Network</div>';
    $list.='<div id="plugin_content">';
    $list.='<form id="eth" name="eth">
                <table style="text-align: left;" border="0" cellpadding="2" cellspacing="2" id="rounded-corner"><tbody>';
    /*
                $list.='<tr>
                    <td colspan="2" rowspan="1"><input name="allow" id="allow" type="checkbox"';
    if ($input[$interfaz]['allow'])
    {
        $list.=" checked>";
    }
    else
    {
        $list.=">";
    }
    $list.="Allow-hotplug</td></tr>\n";
    */

    $list.='<tr>';
    $list.='<td style="width:149px"><b>Choose IP method</b></td><td style="width:157px"> <select name="iface_sel" id="iface_sel" onchange="check_me()" >';
    if ($input[$interfaz]['iface']=='static')
    	{
    	$list.="<option selected=\"yes\" value=static>Static</option>";
    	$list.="<option value=dhcp>DHCP</option>";
    	$list.="</select></td>";
    	}
    else
    	{
    	$list.="<option value=static>Static</option>";
    	$list.="<option selected=\"yes\" value=dhcp>DHCP</option>";
    	$list.="</select></td>
              <td><div onclick='showDHCPconf(\"".$section."\",\"".$plugin."\")' id='showDHCPconf'>Show DHCP info</td>";
    	}
    $list.="<td></td></tr>";

    $list.="<tr><td>";
	$list.='<span class="nl" id=address_lab>IP address</span></td><td> <input type="text" class="ms_mandatory ms_ip" name="address" id="address"';
    if ($input[$interfaz]['address']){
        $list.=" value=".$input[$interfaz]['address'];
    }
    $list.=' size="16" maxlength="15"></td><td></td></tr>';

    $list.="<tr><td>";
    $list.='</td>
            <td><div id="address_ms_cte"></div></td>
            </td><td></td></tr>
            <tr><td>';
    $list.='<span class="nl" id=netmask_lab>Netmask address</span></td><td> <input type="text" class="ms_mandatory ms_ip" name="netmask" id="netmask"';
    if ($input[$interfaz]['netmask']){
        $list.=" value=".$input[$interfaz]['netmask'];
    }
    $list.=' size="16" maxlength="15"></td>';

    $list.="<td></td></tr><tr><td>";
    $list.='</td>
            <td><div id="netmask_ms_cte"></div></td>
            </td><td></td></tr>
            <tr><td>';
    $list.='<span class="nl" id=gateway_lab>Gateway</span></td><td> <input type="text" class="ms_mandatory ms_ip" name="gateway" id="gateway"';
    if ($input[$interfaz]['gateway']){
        $list.=" value=".$input[$interfaz]['gateway'];
    }
    $list.=' size="16" maxlength="15"></td>';

    $list.="<td></td></tr><tr><td>";
    $list.='</td>
            <td><div id="gateway_ms_cte"></div></td>
            </td><td></td></tr>
            <tr><td>';
    $list.='<span class="nl" id=broadcast_lab>Broadcast</span></td><td> <input type="text" class="ms_mandatory ms_ip" name="broadcast" id="broadcast"';
    if ($input[$interfaz]['broadcast']){
            $list.=" value=".$input[$interfaz]['broadcast'];
    }
	$list.=' size="16" maxlength="15"></td>';

    $list.="<td></td></tr><tr><td>";
    $list.='</td>
            <td><div id="broadcast_ms_cte"></div></td>
            </td><td></td></tr>
            <tr><td>';
    $list.='<span class="nl" id=DNS1_lab>Primary DNS</span></td><td> <input type="text" class="ms_mandatory ms_ip" name="DNS1" id="DNS1"';
    if ($input[$interfaz]['dns_primario']){
        $list.=" value=".$input[$interfaz]['dns_primario'];
    }
    else
    {
        $list.=' value="8.8.8.8"' ;
    }
    $list.=' size="16" maxlength="15"></td>';
    $list.="<td></td></tr><tr><td>";
    $list.='</td>
            <td><div id="DNS1_ms_cte"></div></td>
            </td><td></td></tr>
            <tr><td>';
    $list.='<span class="nl" id=DNS2_lab>Secondary DNS</span></td><td> <input type="text" class="ms_mandatory ms_ip" name="DNS2" id="DNS2"';
    if ($input[$interfaz]['dns_secundario']){
        $list.=" value=".$input[$interfaz]['dns_secundario'];
    }
    else
    {
        $list.=' value="8.8.4.4"' ;
    }
    $list.=' size="16" maxlength="15"></td><td></td></tr>
            <tr><td></td>
            <td><div id="DNS2_ms_cte"></div></td>
            </td><td></td></tr>
            ';
//empiezo    
    $list.='<tr  style="height:31px"><td><span><b>Use IPv6</b></span></td>
    <td> <input name="ipv6" type="checkbox" id="ipv6" onclick=\'$("#divip6").toggle();$("#divip61").toggle();$("#divip62").toggle();$("#generate6").toggle();\' ';
			if (strstr($ip6[0],"on")) $list.='checked';
			$list.='/>
    </td><td><input type="button" id="generate6"';
	  if (!strstr($ip6[0],"on")) $list.=' style="display:none" ';
	  $list.='style="float: left; margin-top: 5px; margin-right: 10px;" onclick=\'
	      $("#address6").val("'.$address6generated.'"); $("#gateway6").val("2001::1"); $("#netmask6").val("64");
	    \' value="Generate IPv6 address"></td></tr>
            <tr><td></td>
            <td><div id="DNS2_ms_cte"></div></td>
            </td><td></td></tr>
            <tr id="divip6" ';
    if (!strstr($ip6[0],"on")) $list.='style="display:none"';
    $list.='><td>';
    $list.='<span class="nl" id=address_lab>IPv6 address</span></td><td> <input type="text" name="address6" id="address6"';
    if ($input[$interfaz]['address6']){
        $list.=' value="'.$input[$interfaz]['address6'].'"';
    }
    else
    {
        $list.=' value=""' ;
    }
    $list.=' size="16" maxlength="15"></td><td></td>
</tr>';

    $list.='        <tr id="divip61" ';
    if (!strstr($ip6[0],"on")) $list.='style="display:none"';
    $list.='><td>';
    $list.='<span class="nl" id=address_lab>Netmask number</span></td><td> <input type="text" name="netmask6" id="netmask6"';
    if ($input[$interfaz]['netmask6']){
        $list.=' value="'.$input[$interfaz]['netmask6'].'"';
    }
    else
    {
        $list.=' value=""' ;
    }
    $list.=' size="16" maxlength="15"></td><td></td></tr>';

    $list.='        <tr id="divip62" ';
    if (!strstr($ip6[0],"on")) $list.='style="display:none"';
    $list.='><td>';
    $list.='<span class="nl" id=address_lab>Gateway</span></td><td> <input type="text" name="gateway6" id="gateway6"';
    if ($input[$interfaz]['gateway6']){
        $list.=' value="'.$input[$interfaz]['gateway6'].'"';
    }
    else
    {
        $list.=' value=""' ;
    }
    $list.=' size="16" maxlength="15"></td><td></td></tr>';


//acabo
    $list.='        </tbody></table></form>';
    $list.='
            <div class="right_align">
                <input class="bsave" type="button" onclick="complex_ajax_call(\'eth\',\'output\',\''.$section.'\',\''.$plugin.'\',\'save\')" value="save"></fieldset>
                <!--<input class="bsave" type="button" onclick="complex_ajax_call(\'eth\',\'output\',\''.$section.'\',\''.$plugin.'\',\'save_restart\')" value="save & Apply"></fieldset>-->
            </div>
        </div>';

	return $list;
}

?>