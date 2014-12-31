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

include_once $base_plugin."php/display_security_info.php";
//include_once $API_core.'parser_dhcp_server_new.php';

function make_wireless($path, $interface,$initial=true)
{
    global $url_plugin;
    global $section;
    global $plugin;
    global $base_plugin;

    $entries=parse_dhcp_server('ath0');

	$input=parse_interfaces($path);

   /* exec('echo "<br><b>Interfaz: </b> Wifi AP" > '.$base_plugin."data/simpleInfo");
    exec('echo "<b>Mode: </b> Manager" >> '.$base_plugin."data/simpleInfo");
    exec('echo "<b>IP: </b> '.$input[$interfaz]['address'].'" >> '.$base_plugin."data/simpleInfo");
    exec('echo "" >> '.$base_plugin."data/simpleInfo");*/

    $list.='
    <form id="'.$interface.'" name="'.$interface.'">';
        $list.='
        <div class="title2">Wifi AP Network</div>
        <div id="network_plugin_content" class="plugin_content" style="position: relative;">
            <div id="subnet_alert" style="display: none;">Subnet must match</div>
            <table cellpadding="0" cellspacing="0" style="float: left;">
                <tbody>';
                    $list.='
                    <tr class="hidden">
                        <td>
                            IP method
                        </td>
                        <td>
                            <input type="text" name="iface_sel" id="iface_sel" value="static" readonly disabled style="background: #dedede; width: 122px;" />
                        </td>
                    </tr>
                    <tr>
                        <td>';
                            $list.="
                            <a id=address_lab>Address</a>
                        </td>
                        <td>
                            <input  onFocus=\"$('#subnet_alert').show();\" onBlur=\"$('#subnet_alert').fadeOut();\" type=\"text\" class=\"ms_mandatory ms_ip\" name=\"address\" id=\"address\"";
                            if ($input[$interface]['address']){
                                $list.=" value=".$input[$interface]['address'];
                            }
                            else
                            {
                                $list.=" value='10.10.10.1'";
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
                            $list.=" size=16 maxlength=15>
                        </td>
                        <td>
                            <div id=\"broadcast_ms_cte\"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a id=DNS1_lab>Primary DNS</a></td><td> <input type=\"text\" class=\"ms_mandatory ms_ip\" name=\"DNS1\" id=\"DNS1\"";
                            if ($input[$interface]['dns_primario']){
                                $list.=" value=".$input[$interface]['dns_primario'];
                            }
                            $list.=" size=16 maxlength=15>
                        </td>
                        <td>
                            <div id=\"DNS1_ms_cte\"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a id=DNS2_lab>Secondary DNS</a></td><td> <input type=\"text\" class=\"ms_mandatory ms_ip\" name=\"DNS2\" id=\"DNS2\"";
                            if ($input[$interface]['dns_secundario']){
                                $list.=" value=".$input[$interface]['dns_secundario'];
                            }
                            $list.=" size=16 maxlength=15>
                        </td>
                        <td>
                            <div id=\"DNS2_ms_cte\"></div>
                        </td>
                    </tr>";
                    $list.='
                </tbody>
            </table>
            <table cellpadding="0" cellspacing="0" style="float: left; margin-left: 100px;">
                <tbody>';
                    $list.='
                    <tr>
                        <td>';
                            $list.="
                            <label for=\"dhcp_start_$interface\">DHCP start ip address</label>
                        </td>
                        <td>
                            <input type=\"text\" class=\"ms_mandatory ms_ip $readonly_css\" name=\"dhcp_start_$interface\" id=\"dhcp_start_$interface\" ";
                                $list.=" value=\"".$entries['start'];
                            $list.="\" $readonly_css />";
                            $list.='
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <div id="dhcp_start_'.$interface.'_ms_cte"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>';
                            $list.="
                            <label for=\"dhcp_end_$interface\">DHCP end ip address</label>";
                            $list.='
                        </td>
                        <td>';
                            $list.="
                            <input type=\"text\" class=\"ms_mandatory ms_ip $readonly_css\" name=\"dhcp_end_$interface\" id=\"dhcp_end_$interface\" ";
                                $list.=" value=\"".$entries['end'];
                            $list.="\" $readonly_css />";
                            $list.='
                        </td>
                    </tr>
                        <td>
                        </td>
                        <td>
                            <div id="dhcp_end_'.$interface.'_ms_cte"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>';
                            $list.="
                            <label for=\"dhcp_expire_$interface\">DHCP expire time</label>";
                            $list.='
                        </td>
                        <td>';
                            $list.="
                            <input type=\"text\" class=\"ms_mandatory ms_numerical $readonly_css\" name=\"dhcp_expire_$interface\" id=\"dhcp_expire_$interface\" ";
                                $list.=" value=\"".$entries['expiration'];
                            $list.="\" $readonly_css />hours";
                            $list.='
                        </td>
                    </tr>
                        <td>
                        </td>
                        <td>
                            <div id="dhcp_expire_'.$interface.'_ms_cte"></div>';
                            $list.='
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>';

    // Second block of options.

        $list.='
        <div class="title2">Radio</div>
        <div id="radio_plugin_content" class="plugin_content">
            <table cellpadding="0" cellspacing="0">
                <tbody>
                    <tr>
                        <td>';
                            $list.="ESSID
                        </td>
                        <td>
                            <input type=\"text\" class=\"ms_mandatory\" name=\"essid\" id=\"essid\" MAXLENGTH=32 size=16";
                            if ($input[$interface]['pre-up']['iwconfig']['essid'])
                            {
                                $list.=" value=".$input[$interface]['pre-up']['iwconfig']['essid'];
                            }
                            $list.=">
                        </td>
                        <td>
                            Hide?
                            <input name=\"hide\" id=\"hide\" type=\"checkbox\"";
                            if ($input[$interface]['up']['iwpriv']['hide_ssid']=='1')
                            {
                                $list.=" checked";
                            }
                            $list.=">
                        </td>
                        <td>
                            <div id=\"essid_ms_cte\"></div>
                        </td>
                    <tr>
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
                                $list.="
                            </select>";
                            $list.="
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div id=iwpriv_mode>Protocol</div>
                        </td>
                        <td>
                            <div id=bg_dat>
                                <select onchange=\"check_conditions();\" name=\"mode-abg\" id=\"mode-abg\">";
                                    if ($input[$interface]['up']['iwpriv']['mode']=='1')
                                    {
                                        $list.="<option selected=\"yes\" value=1>b</option>";
                                        $list.="<option value=2>g</option>";
                                    }
                                    else
                                    {
                                        $list.="<option value=1>b</option>";
                                        $list.="<option selected=\"yes\" value=2>g</option>";
                                    }
                                    $list.="
                                </select>
                            </div>
                        </td>
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
                            $list.='
                        </td>
                    </tr>
                    <tr>
                        <td>';
                            $rate_values=array('auto','1Mbps','2Mbps','6Mbps','9Mbps','11Mbps','12Mbps','18Mbps','24Mbps','36Mbps','48Mbps','54Mbps');
                            $list.="
                            Rate
                        </td>
                        <td>
                            ".make_select('rate',$rate_values,$input[$interface]['pre-up']['iwconfig']['rate']);
                            $list.='
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>';

        // Third block of options.

        $list.='
        <div id="security_div">';
            $list .= make_security ($interface);
            /*$list.='<br><hr><br>
            <div id="mac_filter_div">';
                $list.=make_mac_filter($interface,$input[$interface]['up']['interfaces_plus.sh']);
                $list.='
            </div>';*/
            $list.='
        </div>';
    

        $list.='
        <div class="right_align">
            <input type="button" class="bsave" onclick="complex_ajax_call(\''.$interface.'\',\'output\',\''.$section.'\',\''.$plugin.'\',\'default\')" value="save">
        </div>
        <div id="output"></div>';
	
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
                $list.= make_select('interface_selector',$options,' ');
                $list.='
                <input type="hidden" id="plugin" value="'.$plugin.'" />
                <input type="hidden" id="section" value="'.$section.'" />
            </div>
        <div id="interface_info"></div>';
    return $list;

}
?>