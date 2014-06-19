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
 *  Author: Manuel Calvo, Octavio Benedí
 */

function make_options($wifi)
{
    global $url_plugin;
    global $section;
    global $plugin;
    global $base_plugin;

    if($wifi == '0')
    {
        $list.='<div class="title2">Wifi AP</div>';
        $diversity=exec('cat /etc/init.d/diversity.sh | grep "wifi0.diversity" | cut -d= -f2', $div);
        $txantenna=exec('cat /etc/init.d/diversity.sh | grep "wifi0.txantenna" | cut -d= -f2', $tx);
        $rxantenna=exec('cat /etc/init.d/diversity.sh | grep "wifi0.rxantenna" | cut -d= -f2', $rx);
    }
    if($wifi == '1')
    {
        $list.='<div class="title2">Wifi Mesh</div>';
        $diversity=exec('cat /etc/init.d/diversity.sh | grep "wifi1.diversity" | cut -d= -f2', $div);
        $txantenna=exec('cat /etc/init.d/diversity.sh | grep "wifi1.txantenna" | cut -d= -f2', $tx);
        $rxantenna=exec('cat /etc/init.d/diversity.sh | grep "wifi1.rxantenna" | cut -d= -f2', $rx);
    }

    $list.='
            <div class="plugin_content">
            <form id="diversity">
            <table><tbody><tr><td colspan="2">
            <input type="checkbox" name="wifi'.$wifi.'_manual" id="wifi'.$wifi.'_manual"';
            if($div['0']=='1')
            {
                $list.=' checked ';
            }
    $list.='
             />
            <label>Activate manual configuration for ath'.$wifi.'?</label>
            </td></tr><tr><td class="nl">
            <label>RX</label>
            </td><td>
            <select name="wifi'.$wifi.'_0">';
            if($rx['0']=='2')
            {
                $list.='
                <option value="0">Auto</option>
                <option value="1">Antenna 1</option>
                <option value="2" selected="yes">Antenna 2</option>';
            }
            elseif ($rx['0']=='1')
            {
                $list.='
                <option value="0">Auto</option>
                <option value="1" selected="yes">Antenna 1</option>
                <option value="2">Antenna 2</option>';
            }
            else
            {
                $list.='
                <option value="0" selected="yes">Auto</option>
                <option value="1">Antenna 1</option>
                <option value="2">Antenna 2</option>';
            }
    $list.='
            </select>
                </td></tr><tr><td class="nl">
                <label>TX</label>
            </td><td>
                <select name="wifi'.$wifi.'_1">
                    ';
                if($tx['0']=='2')
                {
                    $list.='
                    <option value="0">Auto</option>
                    <option value="1">Antenna 1</option>
                    <option value="2" selected="yes">Antenna 2</option>';
                }
                elseif ($tx['0']=='1')
                {
                    $list.='
                    <option value="0">Auto</option>
                    <option value="1" selected="yes">Antenna 1</option>
                    <option value="2">Antenna 2</option>';
                }
                else
                {
                    $list.='
                    <option value="0" selected="yes">Auto</option>
                    <option value="1">Antenna 1</option>
                    <option value="2">Antenna 2</option>';
                }
    $list.='
            </select>
            </td></tr></tbody></table></form>
            <div class="right_align">
                <input type="button" class="bsave" value="Save" onclick="complex_ajax_call(\'diversity\',\'output\',\''.$section.'\',\''.$plugin.'\',\'save\')"/>
            </div></div>';
    return $list;
}
function make_interface($interface)
{
    global $url_plugin;
    global $section;
    global $plugin;
    global $base_plugin;

    if($interface=='ath0')
    {
        $do_interface='0';
    }
    else
    {
        $do_interface='1';
    }

    $list.=make_options($do_interface);
    
    return $list;
}
function select_interface()
{
    global $section;
    global $plugin;
    
    $detailOptions=array();
    $detailOptions[' '] = ' ';
    $detailOptions['ath0'] = 'Wifi AP';
    $detailOptions['ath1'] = 'Wifi Mesh';

    $list='
        
        <div class="title2">Antenna diversity</div>
            <div class="plugin_content">
            <label class="btext">Select interface </label>';
    $list.=make_select_detailed('interface_selector',$detailOptions,' ');
    $list.='<input type="hidden" id="plugin" value="'.$plugin.'" />
            <input type="hidden" id="section" value="'.$section.'" />
            </div>';
    $list.='<div id="interface">';
    $list.='</div>';
    return $list;
}
?>