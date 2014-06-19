<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function _xsc_interface()
{
    global $url_plugin;
    global $section;
    global $plugin;
    global $base_plugin;

    $NetworkID = exec("cat ".$base_plugin."data/xsc.conf | grep NetworkID | cut -d':' -f2");
    $Channel = exec("cat ".$base_plugin."data/xsc.conf | grep Channel | cut -d':' -f2");
    $NetworkAddress = exec("cat ".$base_plugin."data/xsc.conf | grep NetworkAddress | cut -d':' -f2");
    $MacHigh = exec("cat ".$base_plugin."data/xsc.conf | grep MacHigh | cut -d':' -f2");
    $MacLow = exec("cat ".$base_plugin."data/xsc.conf | grep MacLow | cut -d':' -f2");

	$list='
    <div class="title2">XSC</div>
    <div class="plugin_content">
        <form id="xbee_configuration">
            <input type="hidden" name="port" value="S0">
            <input type="hidden" name="old_speed" value="5">
            <table>
                <tbody>
                    <tr>
                        <td>
                            <label>Network ID: </label></td><td>
                            <input maxlength="4" type="text" class="ms_hex" name="atid" id="atid" value="'.$NetworkID.'" />
                        </td>
                        <td>
                            <div id="atid_ms_cte"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Channel:</label></td><td>';
                            unset($options);
                            $options=array('b'=>'0x0B','c'=>'0x0C','d'=>'0x0D','e'=>'0x0E','f'=>'0x0F','10'=>'0x10','11'=>'0x11','12'=>'0x12','13'=>'0x13','14'=>'0x14','15'=>'0x15','16'=>'0x16','17'=>'0x17','18'=>'0x18');
                            $list.=make_select('atch',$options,$Channel,"");
                            $list.='
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Network address: </label></td><td>
                            <input maxlength="4" type="text" class="ms_hex" name="atmy" id="atmy" value="'.$NetworkAddress.'" />
                        </td>
                        <td>
                            <div id="atmy_ms_cte"></div>
                        </td>
                    </tr>

                    <tr><td>
                            <label>MAC high: </label></td><td>
                            <input type="text" name="atsh" id="atsh" disabled value="'.$MacHigh.'" readonly />
                        </td></tr><tr><td>
                            <label>MAC low: </label></td><td>
                            <input type="text" name="atsl" id="atsl" disabled value="'.$MacLow.'" readonly />
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
        <div id="checking" style="width: 240px; padding: 0 5px; background:white; float: left;"></div>
        <div style="clear: both;"></div>
        <div class="right_align">
            <input class="bsave" type="button" value="Save" onclick="save(\'xbee_configuration\',\'output\',\''.$section.'\',\''.$plugin.'\',\'xsc\')"/>
        </div>
        <div style="background: white; border: 1px solid #898989; padding: 5px;-moz-border-radius: 5px;float: right;">
            <button onclick="getMacs(\''.$section.'\',\''.$plugin.'\')">Load MAC</button>
            <button onclick="checkStatus(\'xbee_configuration\',\''.$section.'\',\''.$plugin.'\',\'xsc\')">Check status</button>
        </div>
    </div>';


    return $list;
}
?>
