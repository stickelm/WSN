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

function make_input($interface)
{
    global $url_plugin;
    global $section;
    global $plugin;

    if(trim($interface) == 'ath0')
    {
        if(file_exists($base_plugin."data/networkingOptions_wifi0"))
            $loadedData = loadData($interface);
    }
    else
    {
        if(file_exists($base_plugin."data/networkingOptions_wifi1"))
            $loadedData = loadData($interface);
    }

    $loadedData = loadData($interface);



   $list='<div class="title2">Long range link configuration</div>
            <div class="plugin_content">
            <form id="long_range_link">
            <!--<div>
            <input id="permanent_changes" type="checkbox" '.$checked.' name="permanent_changes"/>
            <label>Make this changes permanents in system</label>
            </div>-->
            ';



    $options=array('Auto','Manual');
    $list.='<div id="select_input_method" class="nl ss">
    <label>Select input method</label>';
    $list.=make_select('input_method',$options,$loadedData[$interface]['mode']);
    $list.='</div>';
    $list.='<div id="distance" class="nl">                
                <table><tbody>
                    <tr>
                        <td class="table_label nl">
                            Distance (Km)
                        </td>
                        <td>
                            <input type="text" maxlength="5" class="ms_numerical" id="distance_value" name="distance_value" value="'.$loadedData[$interface]['distance'].'" />
                       </td>
                   </tr>
                    <tr>
                        <td></td>
                        <td><div id="distance_value_ms_cte"></div></td>
                    </tr>
               </tbody></table>
            </div>';
    $list.='<div id="direct_values" class="nl">
                    <table><tbody>
                    <tr>
                        <td class="table_label nl">
                            ACKTIMEOUT
                        </td>
                        <td>
                            <input type="text" maxlength="5" class="ms_numerical" name="acktimeout" id="acktimeout" value="'.$loadedData[$interface]['acktimeout'].'" / >
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><div id="acktimeout_ms_cte"></div></td>
                    </tr>
                    <tr>
                        <td class="nl">
                            <span>CTSTIMEOUT</span>
                        </td>
                        <td>
                            <input type="text" maxlength="5" class="ms_numerical" name="ctstimeout" id="ctstimeout" value="'.$loadedData[$interface]['ctstimeout'].'" / >
                        </td>
                    </tr>
                    <tr>    
                        <td></td>
                        <td><div id="ctstimeout_ms_cte"></div></td>
                    </tr>
                    <tr>
                        <td class="nl">
                            <span>SLOTTIME</span>
                        </td>
                        <td>
                            <input type="text" maxlength="5" class="ms_numerical" name="slottime" id="slottime" value="'.$loadedData[$interface]['slottime'].'" / >
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><div id="slottime_ms_cte"></div></td>
                    </tr>
                    </tbody></table>
                
            </div><div class="right_align">
                <input class="bsave" type="button" id="interface_info_def_'.$interface.'" type="button" onclick="saveDefaults()" value="Restore defaults" />
                <input class="bsave" type="button" id="interface_info_'.$interface.'" type="button" onclick="save()" value="Save" />';
    $list.="</div>";
    $list.='</form></div>';
    return $list;
}

function make_interface($interface)
{
    global $url_plugin;
    global $section;
    global $plugin;    
    $list.=make_input($interface);
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
        <div class="title2">Long range link</div>
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