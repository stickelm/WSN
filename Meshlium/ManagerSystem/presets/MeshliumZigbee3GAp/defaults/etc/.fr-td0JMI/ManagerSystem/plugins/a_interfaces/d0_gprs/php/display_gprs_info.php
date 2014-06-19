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
function addInputGprs()
{
    global $section;
    global $plugin;
    global $url_plugin;
    global $operators_file_path;
    global $base_plugin;
    
    $list = '';

   /* exec("sudo ifconfig | grep -v ^' ' | grep  '\n' | cut -d' ' -f1", $a);
    if (!in_array('ppp0', $a))
    {
        $list.="<h3 class='alarm'>No GPRS Detected</h3><br><br>";
    }*/
        $data=list_operators($operators_file_path);

        $path='/etc/wvdial.conf';
        $path=$base_plugin.'data/wvdial.conf';
        $known_operator=parse_wvdial($path);
        //$list.='<div class="title">GPRS</div>';
        $list.='<div class="title2">GPRS Network</div>';
        //$list.='<pre>'.print_r($data,true).'</pre>';
        //$list.='<pre>'.print_r($known_operator,true).'</pre>';
        $list.='<div id="plugin_content">';
        $list.='<form id="gprs" name="gprs" onsubmit="return false"><table style="text-align: left;" border="0" cellpadding="2" cellspacing="2"><tbody><tr>';
        $list.="<td colspan='3' rowspan='1'>";
        $list.="<p class='advice'>Connectivity information from operators provided without warranty.</p>";
        $list.="</td></tr>";
        $list.="<tr><td>Select country</td><td>";
        $list.=add_countries($data,$known_operator['country']);
        $list.="</td></tr>";
        $list.="<tr><td>Choose operator</td><td>";
        $list.="<div id='add_operators'>";
        $list.=add_operators($data,$known_operator['country'],$known_operator['operator']);
        $list.="</div>";
        $list.="</td></tr>";
        $list.="<tr><td></td><td class='ss'>";
        $list.='<span class="ref" onclick="allow_edit();">Click here</span> to edit';
        $list.="</td></tr>";
        $list.="<tr><td>Card PIN</td><td><input type='text' name='PIN' id='PIN' class='readonly ms_numerical' value='".$known_operator['pin']."' readonly /></td><td class='advice'>Leave it empty for no PIN</td>";
        $list.="</tr><tr>";
        $list.='<td></td><td><div id="PIN_ms_cte"></div></td>';
        $list.="</tr><tr>";
        $list.="<tr><td>Username</td><td> <input type='text' name='username' id='username' class='readonly' value='".$known_operator['username']."' readonly /></td><td class='advice'>Should be provided by your operator.";
        $list.="</td></tr>";
        $list.='<td></td><td><div id="username_ms_cte"></div></td>';
        $list.="</tr><tr>";
        $list.="<tr><td>Password</td><td><input type='text' name='password' id='password' class='readonly' value='".$known_operator['password']."' readonly /></td><td class='advice'>Should be provided by your operator.";
        $list.="</td></tr>";
        $list.='<td></td><td><div id="password_ms_cte"></div></td>';
        $list.="</tr><tr>";
        $list.="<tr><td>Phone</td><td><input type='text' name='phone' id='phone' class='readonly ms_mandatory' value='".$known_operator['phone']."' readonly /></td><td class='advice'>Should be provided by your operator.";
        $list.="</td></tr>";
        $list.='<td></td><td><div id="phone_ms_cte"></div></td>';
        $list.="</tr><tr>";
        $list.="<tr><td>Init</td><td><input type='text' name='init1' id='init1' class='readonly ms_mandatory' value='".$known_operator['init2']."' readonly /></td><td class='advice'>If more than one init is required you should edit wvdial.conf manually .";
        $list.="</td></tr>";
        $list.='<td></td><td><div id="init1_ms_cte"></div></td>';
        $list.="</tr><tr>";
        $list.="<tr><td>Dial</td><td><select id='dial' name='dial' class='readonly' readonly />";
        if ($known_operator['dial'])
        {
            if ($known_operator['dial']=='ATD')
            {
                $list.="<option value='atd' selected='yes'>ATD</option>";
                $list.="<option value='atdt'>ATDT</option>";
            }
            else
            {
                $list.="<option value='atd'>ATD</option>";
                $list.="<option value='atdt' selected='yes'>ATDT</option>";
            }
        }
        else
        {
            $list.="<option value='atd'>ATD</option>";
            $list.="<option value='atdt'>ATDT</option>";
        }
        $list.="</select></td><td class='advice'>Should be provided by your operator.";
        $list.="</td></tr><br>";
        $list.="</tbody></table>";
        $list.='<div class="right_align">';
        $list.='<br><br>
                    <div style="float: left;">
                        <div class="miniHelp" onclick="notify(\'icono-i\', \'Try to connect with the current configuration\');fadenotify();"></div>';
                        exec("ps -e | grep wvdial | wc -l", $ret);
                        if($ret['0'] == '1')
                        {
                            $list.= '<button id="GPRSdisconnect" style="float: left; margin-top: 5px; margin-right: 10px;" onclick="$(\'#GPRSdisconnect\').hide();$(\'#GPRSconnect\').show();complex_ajax_call(\'gprs\',\'disconnect\',\''.$section.'\',\''.$plugin.'\',\'output\')" >Disconnect now</button>
                            <button id="GPRSconnect" style="float: left; margin-top: 5px; margin-right: 10px; display: none;" onclick="$(\'#GPRSconnect\').hide();$(\'#GPRSdisconnect\').show();ajax_connect_0(\'gprs\',\'connect\',\''.$section.'\',\''.$plugin.'\',\'output\')" >Connect now</button>';
                        }
                        else
                        {
                            $list.= '<button id="GPRSconnect" style="float: left; margin-top: 5px; margin-right: 10px;" onclick="$(\'#GPRSconnect\').hide();$(\'#GPRSdisconnect\').show();ajax_connect_0(\'gprs\',\'connect\',\''.$section.'\',\''.$plugin.'\',\'output\')" >Connect now</button>
                                     <button id="GPRSdisconnect" style="float: left; margin-top: 5px; margin-right: 10px; display: none;" onclick="$(\'#GPRSdisconnect\').hide();$(\'#GPRSconnect\').show();complex_ajax_call(\'gprs\',\'disconnect\',\''.$section.'\',\''.$plugin.'\',\'output\')" >Disconnect now</button>';
                        }
                
                    $list.= '
                    </div>
                    <div style="float: left; border-left: 1px solid #454545; padding-left: 15px; margin-left: 15px;">
                        <div class="miniHelp" onclick="notify(\'icono-i\', \'Save the configuration and set the system to connect at every restart.\');fadenotify();"></div>';

                        if(file_exists("/etc/init.d/wvdiald.sh"))
                        {
                            $list.= '<input type="checkbox" name="defgw" id="defgw"  style="margin-top: 8px;" checked /><span>Set as default gw</span><br>';
                        }
                        else
                        {
                            $list.= '<input type="checkbox" name="defgw" id="defgw"  style="margin-top: 8px;" /><span>Set as default gw</span><br>';
                        }

                        $list.= '
                        <div class="miniHelp" onclick="notify(\'icono-i\', \'Save the configuration\');fadenotify();"></div>
                        <input style="float: left;margin-left:0;margin-top:4px;" type="button" class="bsave" onclick="complex_ajax_call(\'gprs\',\'save\',\''.$section.'\',\''.$plugin.'\',\'output\')" value="save">
                    </div>';
        //$list.='<input type="button" class="bsave" onclick="complex_ajax_call(\'gprs\',\'save_restart\',\''.$section.'\',\''.$plugin.'\',\'output\')" value="save & Apply">';
        $list.='</div></form>';
        

        if($ret['0'] == '1')
        {
            $pppIPa = exec("sudo ifconfig ppp0 | grep 'inet addr' | cut -d: -f2 | cut -d' ' -f1", $pppIP);
            $list.='
            <div style="clear: both;"></div>
            <div id="GPRSStatus" class="connected">
                <b>Connected</b><br><br>
                GPRS IP: '.$pppIP['0'].'<br>
            </div>';
        }
        else
        {
            $list.='
            <div style="clear: both;"></div>
            <div id="GPRSStatus" class="disconnected">
                <b>Disonnected</b><br>
            </div>';
            
        }


        $list.='</div>';

	return $list;
}

function add_countries($data,$known_country='')
{
    global $section;
    global $plugin;
	//$list.="<select name=country_list id=country_list onchange=\"refresh_gprs('country'); check_opt('configure','gprs')\">";
    $list.="<select name='country_list' id='country_list' onchange=\"complex_ajax_call('gprs','country','$section','$plugin','output')\">";
	$list.='<option value="other">Other</option>';
    $country=explode('//',$data['list']);
    
	foreach($country as $i)
	{
		$list.='<option value="'.$i.'"';
        if ($known_country==$i)
            {
                $list.='selected="yes" ';
            }
        $list.='>'.$i.'</option>';
	}
	$list.='</select>';
	return $list;
}

function add_operators($data,$country='',$known_operator='')
{
    global $section;
    global $plugin;
	$list.="<select name='country_operators' id='country_operators' onchange=\"complex_ajax_call('gprs','operator','$section','$plugin','output')\">";
    $list.='<option value="other">Other</option>';
	if ($country!='')
		{
		$country_ops=explode('//',$data[$country]['list']);
		foreach($country_ops as $i)
		{
			$list.='<option value="'.$i.'" ';
            if ($known_operator==$i)
            {
                $list.='selected="yes" ';
            }
            $list.='>'.$i.'</option>';
		}
	}
	$list.='</select>';
	return $list;
}
?>