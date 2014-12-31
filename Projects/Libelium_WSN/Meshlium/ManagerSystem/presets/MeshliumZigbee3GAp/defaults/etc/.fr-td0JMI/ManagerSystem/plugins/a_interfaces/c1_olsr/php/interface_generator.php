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
function make_olsrd()
{
    global $url_plugin;
    global $section;
    global $plugin;
    global $mesh_kind;

        $data=Array();
        $path='/etc/olsrd.conf';
        if (file_exists($path))
        {
            $data=parse_olsrd($path);
        }

        //exec("sudo route | grep default | wc -l", $accessInet);
        exec("cat /etc/olsrd.conf | grep Hna4 | wc -l", $accessInet);

        $list='
        <div class="title2">OLSR configuration</div>
        <div class="plugin_content">
            <a class="btext" href="http://'.$_SERVER['HTTP_HOST'].':8080">Access OLSR summary</a>
        </div>

        <form id="olsr" name="olsr">

            <div class="hidden">
                <input id="ath1" name="ath1" type="checkbox" checked>
            </div>

            <div class="title2">OLSR parameters</div>
            <div class="plugin_content">
                <div>';

                    if($accessInet['0'] >= '1')
                    {
                        $list.='
                        <input type="checkbox" name="isMeshGw" id="isMeshGw" checked /><span><b>Share the internet connection (make this node as mesh gateway)</b></span>';
                    }
                    else
                    {
                        $list.='
                        <input type="checkbox" name="isMeshGw" id="isMeshGw" /><span><b>Share the internet connection (make this node as mesh gateway)</b></span>';
                    }

                    $list.='
                </div><br><br>
                <table class="main_table" border="0" cellpadding="2" cellspacing="2">
                    <tbody>';

                    $list.='
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="padding: 0 30px;"><div class="simulatedButton" onclick="setValuesOLSRpreset(\'fixed\')">Fixed</div></td>
                            <td style="padding: 0 30px;"><div class="simulatedButton" onclick="setValuesOLSRpreset(\'mobile\')">Mobile</div></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="3"><div id="HelloInterval_ms_cte"></div></td>
                        </tr>';

                    $list.='
                        <tr>
                            <td>HelloInterval</td>
                            <td>
                                <input type="text" class="ms_mandatory ms_float" id="HelloInterval" name="HelloInterval"';
                                if ($data['Interface']['HelloInterval'])
                                {
                                    $list.=' value="'.$data['Interface']['HelloInterval'].'"';
                                }
                                $list.='
                                >
                            </td>
                            <td class="help_image">
                                <img title="Info avaible" src="'.$url_plugin.'/images/info_avaible.png" onclick="alert(\'HelloInterval [0.0-]:Sets the interval on which HELLO messages will be generated  and transmitted on this interface.\');">
                            </td>
                            <td>[0.0-]</td>
                            <td style="text-align:center;">5.0</td>
                            <td style="text-align:center;">1.0</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="3"><div id="HelloInterval_ms_cte"></div></td>
                        </tr>';

                    $list.='
                        <tr>
                            <td>HelloValidityTime</td>
                            <td>
                                <input type="text" class="ms_mandatory ms_float" id="HelloValidityTime" name="HelloValidityTime"';
                                if ($data['Interface']['HelloValidityTime'])
                                {
                                    $list.=' value="'.$data['Interface']['HelloValidityTime'].'"';
                                }
                                $list.='
                                >
                            </td>
                            <td class="help_image">
                                <img title="Info avaible" src="'.$url_plugin.'/images/info_avaible.png" onclick="alert(\'HelloValidityTime [0.0-]:Sets  the validity time to be announced in HELLO messages generated by this host on this interface. This value must  be  larger than  than  the  HELLO  generation  interval  to make any sense.Defaults to 3 * the generation interval.\');">
                            </td>
                            <td>[0.0-]</td>
                            <td style="text-align:center;">100.0</td>
                            <td style="text-align:center;">20.0</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="3"><div id="HelloValidityTime_ms_cte"></div></td>
                        </tr>';

                    $list.='
                        <tr>
                            <td>TcInterval</td>
                            <td>
                                <input type="text" class="ms_mandatory ms_float" id="TcInterval" name="TcInterval"';
                                if ($data['Interface']['TcInterval'])
                                {
                                    $list.=' value="'.$data['Interface']['TcInterval'].'"';
                                }
                                $list.='
                                >
                            </td>
                            <td class="help_image">
                                <img title="Info avaible" src="'.$url_plugin.'/images/info_avaible.png" onclick="alert(\'TcInterval [0.0-]:Sets the interval on which TC messages  will  be  generated  and transmitted on this interface.\');">
                            </td>
                            <td>[0.0-]</td>
                            <td style="text-align:center;">5.0</td>
                            <td style="text-align:center;">1.0</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="3"><div id="TcInterval_ms_cte"></div></td>
                        </tr>';

                    $list.='
                        <tr>
                            <td>TcValidityTime</td>
                            <td>
                                <input type="text" class="ms_mandatory ms_float" id="TcValidityTime" name="TcValidityTime"';
                                if ($data['Interface']['TcValidityTime'])
                                {
                                    $list.=' value="'.$data['Interface']['TcValidityTime'].'"';
                                }
                                $list.='
                                >
                            </td>
                            <td class="help_image">
                                <img title="Info avaible" src="'.$url_plugin.'/images/info_avaible.png" onclick="alert(\'TcValidityTime [0.0-]:Sets  the validity time to be announced in TC messages generated by this host on this interface. This value must be  larger  than than the TC generation interval to make any sense. Defaults to 3 * the generation interval.\');">
                            </td>
                            <td>[0.0-]</td>
                            <td style="text-align:center;">100.0</td>
                            <td style="text-align:center;">20.0</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="3"><div id="TcValidityTime_ms_cte"></div></td>
                        </tr>';

                    $list.='
                        <tr>
                            <td>HnaInterval</td>
                            <td>
                                <input type="text" class="ms_mandatory ms_float" id="HnaInterval" name="HnaInterval"';
                                if ($data['Interface']['HnaInterval'])
                                {
                                    $list.=' value="'.$data['Interface']['HnaInterval'].'"';
                                }
                                $list.='
                                >
                            </td>
                            <td class="help_image">
                                <img title="Info avaible" src="'.$url_plugin.'/images/info_avaible.png" onclick="alert(\'HnaInterval [0.0-]:Sets the interval on which HNA messages will  be  generated  and transmitted on this interface.\');">
                            </td>
                            <td>[0.0-]</td>
                            <td style="text-align:center;">5.0</td>
                            <td style="text-align:center;">2.0</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="3"><div id="HnaInterval_ms_cte"></div></td>
                        </tr>';

                    $list.='
                        <tr>
                            <td>HnaValidityTime</td>
                            <td>
                                <input type="text" class="ms_mandatory ms_float" id="HnaValidityTime" name="HnaValidityTime"';
                                if ($data['Interface']['HnaValidityTime'])
                                {
                                    $list.=' value="'.$data['Interface']['HnaValidityTime'].'"';
                                }
                                $list.='
                                >
                            </td>
                            <td class="help_image">
                                <img title="Info avaible" src="'.$url_plugin.'/images/info_avaible.png" onclick="alert(\'HnaValidityTime [0.0-]:Sets the validity time to be announced in HNA messages generated by this host on this interface. This value must be  larger  than than  the HNA generation interval to make any sense. Defaults to 3 * the generation interval.\');">
                            </td>
                            <td>[0.0-]</td>
                            <td style="text-align:center;">200.0</td>
                            <td style="text-align:center;">50.0</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="3"><div id="HnaValidityTime_ms_cte"></div></td>
                        </tr>';

                    $list.='
                    </tbody>
                </table>
            </div>            
        </form>
        <div class="right_align">
            <input type="button" class="bsave" onclick="complex_ajax_call(\'olsr\',\'output\',\''.$section.'\',\''.$plugin.'\',\'save\')" value="save">
        </div>';

    return $list;

}
?>