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
 *  Authors: Octavio Benedí  
 *           Daniel Larraz <d.larraz [at] libelium [dot] com>
 */



function make_wireless($path, $interface,$initial=true)
{
    global $url_plugin;
    global $section;
    global $plugin;
    global $base_plugin;

	$input=parse_interfaces($path);

    /*exec('echo "<br><b>Interfaz: </b> Wifi Mesh" > '.$base_plugin."data/simpleInfo");
    exec('echo "<b>Mode: </b> Ad-hoc" >> '.$base_plugin."data/simpleInfo");
    exec('echo "<b>IP: </b> '.$input[$interfaz]['address'].'" >> '.$base_plugin."data/simpleInfo");
    exec('echo "" >> '.$base_plugin."data/simpleInfo");*/
    
    $list.='
    <form id="'.$interface.'" name="'.$interface.'">
        <div class="title2">Wifi Mesh Network</div>
            <div id="network_plugin_content" class="plugin_content">
                <table cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr class="hidden">
                            <td>
                                Choose IP method
                            </td>
                            <td>
                                <input  name="iface_sel" id="iface_sel" value="static" disabled readonly />
                            </td>
                        </tr>';
                        $list.="
                        <tr>
                            <td>
                                <a id=address_lab>Address</a>
                            </td>
                            <td>
                                <input type=\"text\" class=\"ms_mandatory ms_ip\" name=\"address\" id=\"address\"";
                                if ($input[$interface]['address']){
                                    $list.=" value=".$input[$interface]['address'];
                                }
                                else
                                {
                                    $list.=" value='10.1.20.1'";
                                }
                                $list.=" size=16 maxlength=15>
                            </td>
                            <td>
                                <div id=\"address_ms_cte\"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a id=netmask_lab>Netmask</a>
                            </td>
                            <td> 
                                <input type=\"text\" class=\"ms_mandatory ms_ip\" name=\"netmask\" id=\"netmask\"";
                                if ($input[$interface]['netmask']){
                                    $list.=" value=".$input[$interface][netmask];
                                }
                                else
                                {
                                    $list.=" value='255.255.255.0'";
                                }
                                $list.=" size=16 maxlength=15>
                            </td>
                            <td>
                                <div id=\"netmask_ms_cte\"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a id=broadcast_lab>Broadcast</a></td><td> <input type=\"text\" class=\"ms_mandatory ms_ip\" name=\"broadcast\" id=\"broadcast\"";
                                if ($input[$interface]['broadcast']){
                                        $list.=" value=".$input[$interface]['broadcast'];
                                }
                                $list.=" size=16 maxlength=15></td><td><div id=\"broadcast_ms_cte\"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a id=DNS1_lab>Primary DNS</a>
                            </td>
                            <td> 
                                <input type=\"text\" class=\"ms_mandatory ms_ip\" name=\"DNS1\" id=\"DNS1\"";
                                if ($input[$interface]['dns_primario']){
                                    $list.=" value=".$input[$interface]['dns_primario'];
                                }
                                $list.=" size=16 maxlength=15></td><td><div id=\"DNS1_ms_cte\"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <a id=DNS2_lab>Secondary DNS</a></td><td> <input type=\"text\" class=\"ms_mandatory ms_ip\" name=\"DNS2\" id=\"DNS2\"";
                                if ($input[$interface]['dns_secundario']){
                                    $list.=" value=".$input[$interface]['dns_secundario'];
                                }
                                $list.=" size=16 maxlength=15></td><td><div id=\"DNS2_ms_cte\"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>";

            // Second block of options.

            $list.='
            <div class="title2">Radio</div>
            <div id="radio_plugin_content" class="plugin_content">
                <table cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                            <td>';
                                $list.="ESSID</td><td><input type=\"text\" class=\"ms_mandatory\" name=\"essid\" id=\"essid\" MAXLENGTH=32 size=16";
                                if ($input[$interface]['pre-up']['iwconfig']['essid'])
                                {
                                    $list.=" value=".$input[$interface]['pre-up']['iwconfig']['essid'];
                                }
                                $list.=">
                            </td>
                            <td>
                                <div id=\"essid_ms_cte\"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div id='mac_essid'>
                                    CELL ID
                                </div>
                            </td>
                            <td>
                                <div id='mac_essid2' > 
                                    <input type=\"text\" class=\"ms_mandatory ms_mac\" name='mac_essid_i' id='mac_essid_i'";
                                    if ($input[$interface]['pre-up']['iwconfig']['ap']){
                                            $list.=" value=".$input[$interface]['pre-up']['iwconfig']['ap'];
                                    }
                                    $list.=" size=16 maxlength=17>
                                </div>
                            </td>
                            <td>
                            </td>
                            <td>
                                <div id=\"mac_essid_i_ms_cte\"></div>
                            </td>
                        </tr>";

    $list.="<tr><td>";
    $list.="Frequency</td><td><div style=\"display:inline;\"> <select onchange=\"check_conditions();\" name=\"freq\" id=\"freq\" >";
    if (isset($input[$interface]['pre-up']['iwconfig']['channel'])&&($input[$interface]['pre-up']['iwconfig']['channel']>14))
    {
    	$list.="<option value=\"2\">2.4GHz</option>";
    	$list.="<option selected='yes' value=\"5\">5GHz</option>";
    }
    else
    {
    	$list.="<option selected='yes' value=\"2\">2.4GHz</option>";
    	$list.="<option value=\"5\">5GHz</option>";
    }
    $list.="</select></div>";

    $list.="</td></tr>";


                $list.="<tr>
                            <td>
                                Channel
                            </td>
                            <td>

    <select onchange=\"check_conditions();\" name=\"channel2\" id=\"channel2\">";
    for($vuelta=1;$vuelta<=13;$vuelta++)
    {
    	$list.="<option ";
    	if ($input[$interface]['pre-up']['iwconfig']['channel']==$vuelta)
    	{ $list.=" selected='yes' ";}
    	$list.="value=".$vuelta.">".$vuelta."</option>";
    }
    $list.="</select>
                                <select onchange=\"check_conditions();\" name=\"channel5\" id=\"channel5\" >";
                                    for($vuelta=36;$vuelta<=56;$vuelta+=2)
                                    {
                                        $list.="<option ";
                                        if ($input[$interface]['pre-up']['iwconfig']['channel']==$vuelta)
                                        { $list.=" selected='yes' ";}
                                        $list.="value=".$vuelta.">".$vuelta."</option>";
                                    }
                                    $vuelta=60;
                                    $list.="
                                    <option ";
                                    if ($input[$interface]['pre-up']['iwconfig']['channel']==$vuelta)
                                    { $list.=" selected='yes' ";}
                                    $list.="value=".$vuelta.">".$vuelta."</option>";
                                    $vuelta=64;
                                    $list.="<option ";
                                    if ($input[$interface]['pre-up']['iwconfig']['channel']==$vuelta)
                                    { $list.=" selected='yes' ";}
                                    $list.="value=".$vuelta.">".$vuelta."</option>";
                                    $vuelta=149;
                                    $list.="<option ";
                                    if ($input[$interface]['pre-up']['iwconfig']['channel']==$vuelta)
                                    { $list.=" selected='yes' ";}
                                    $list.="value=".$vuelta.">".$vuelta."</option>";
                                    $vuelta=153;
                                    $list.="<option ";
                                    if ($input[$interface]['pre-up']['iwconfig']['channel']==$vuelta)
                                    { $list.=" selected='yes' ";}
                                    $list.="value=".$vuelta.">".$vuelta."</option>";
                                    $vuelta=157;
                                    $list.="<option ";
                                    if ($input[$interface]['pre-up']['iwconfig']['channel']==$vuelta)
                                    { $list.=" selected='yes' ";}
                                    $list.="value=".$vuelta.">".$vuelta."</option>";
                                    $vuelta=161;
                                    $list.="<option ";
                                    if ($input[$interface]['pre-up']['iwconfig']['channel']==$vuelta)
                                    { $list.=" selected='yes' ";}
                                    $list.="value=".$vuelta.">".$vuelta."</option>";
                                    $list.="
                                </select>
                            </div>
                            </td>
                        </tr>
                        <tr>
                            <td>";
                                $tx_power_values=array('auto'=>'auto','0'=>'0 dB','1'=>'1 dB','2'=>'2 dB','3'=>'3 dB','4'=>'4 dB','5'=>'5 dB','6'=>'6 dB','7'=>'7 dB','8'=>'8 dB','9'=>'9 dB','10'=>'10 dB','11'=>'11 dB','12'=>'12 dB','13'=>'13 dB','14'=>'14 dB','15'=>'15 dB','16'=>'16 dB','17'=>'17 dB','18'=>'18 dB','19'=>'19 dB');
                                if(!empty($input[$interface]['pre-up']['iwconfig']['txpower']))
                                {
                                    $default_tx_power=$input[$interface]['pre-up']['iwconfig']['txpower'];
                                }
                                else
                                {
                                    $default_tx_power='auto';
                                }
                                $list.="Tx power
                            </td>
                            <td>
                                ".make_select_detailed('tx_power',$tx_power_values,$default_tx_power);
                                $list.="
                            </td>
                        </tr>
                        <tr>
                            <td>";
                                $rate_values=array('auto','1Mbps','2Mbps','6Mbps','9Mbps','11Mbps','12Mbps','18Mbps','24Mbps','36Mbps','48Mbps','54Mbps');
                                $list.="Rate
                            </td>
                            <td>
                                ".make_select('rate',$rate_values,$input[$interface]['pre-up']['iwconfig']['rate']);
                                $list.="
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>";


    
    $list.='
            <div class="right_align"><input type="button" class="bsave" onclick="complex_ajax_call(\''.$interface.'\',\'output\',\''.$section.'\',\''.$plugin.'\',\'default\')" value="save"></div>
        <div id="output"></div>';
	
	return $list;
}

function make_selector()
{
    global $plugin;
    global $section;
    $options=array(' ','ath0','ath1');
    $list='
        <div class="title">WIFI</div>
        <div class="title2">Interface configuration</div>
            <div class="plugin_content">
            <label class="btext">Select interface </label>';
    $list.=     make_select('interface_selector',$options,' ');
    $list.='
            <input type="hidden" id="plugin" value="'.$plugin.'" />
            <input type="hidden" id="section" value="'.$section.'" />
            </div>
        <div id="interface_info"></div>';
    return $list;

}

function make_selector_arg($options)
{
    global $plugin;
    global $section;
    $list='
        <div class="title">WIFI</div>
        <div class="title2">Interface configuration</div>
            <div class="plugin_content">
            <label class="btext">Select interface </label>';
    $list.=     make_select('interface_selector',$options,' ');
    $list.='
            <input type="hidden" id="plugin" value="'.$plugin.'" />
            <input type="hidden" id="section" value="'.$section.'" />
            </div>
        <div id="interface_info"></div>';
    return $list;

}
?>