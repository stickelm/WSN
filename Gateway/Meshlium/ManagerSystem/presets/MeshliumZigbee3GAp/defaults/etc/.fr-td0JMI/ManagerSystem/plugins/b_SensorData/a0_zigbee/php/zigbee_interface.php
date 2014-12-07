<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function _zigbee_interface()
{
    global $url_plugin;
    global $section;
    global $plugin;
    global $base_plugin;

    $NodeID = exec("cat ".$base_plugin."data/zigbee.conf | grep NodeID | cut -d':' -f2");
    $EncryptedMode = exec("cat ".$base_plugin."data/zigbee.conf | grep EncryptedMode | cut -d':' -f2");
    $EncryptKey = exec("cat ".$base_plugin."data/zigbee.conf | grep EncryptKey | cut -d':' -f2");
    $MacHigh = exec("cat ".$base_plugin."data/zigbee.conf | grep MacHigh | cut -d':' -f2");
    $MacLow = exec("cat ".$base_plugin."data/zigbee.conf | grep MacLow | cut -d':' -f2");

	$list='
    <div class="title2">Zigbee</div>
    <div class="plugin_content">
        <form id="xbee_configuration">
            <input type="hidden" name="port" value="S0">
            <input type="hidden" name="old_speed" value="5">
            <table>
                <tbody>
                    <tr>
                        <td>
                            <label>Node ID:</label></td><td>
                            <input maxlength="20" type="text" name="atni" id="atni" value="'.$NodeID.'" />
                        </td>
                        <td>
                            <div id="atni_ms_cte"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Encrypted mode: </label></td><td>';
                            unset($options);
                            $options=array('0'=>'Off','1'=>'On');
                            $list.=make_select('atee',$options,$EncryptedMode ,"");
                            $list.='
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>Encrypt key: </label></td><td>
                            <input maxlength="16" type="text" name="atky" id="atky" value="'.$EncryptKey.'"  />
                        </td></tr><tr><td>
                            <label>MAC high:</label></td><td>
                            <input type="text" name="atsh" id="atsh" disabled value="'.$MacHigh.'" readonly />
                        </td></tr><tr><td>
                            <label>MAC low:</label></td><td>
                            <input type="text" name="atsl" id="atsl" disabled value="'.$MacLow.'" readonly />
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
        <div id="checking" style="width: 240px; padding: 0 5px; background:white; float: left;"></div>
        <div style="clear: both;"></div>
        <div class="right_align">
            <input class="bsave" type="button" value="Save" onclick="save(\'xbee_configuration\',\'output\',\''.$section.'\',\''.$plugin.'\',\'zigbee\')"/>
        </div>
        <div style="background: white; border: 1px solid #898989; padding: 5px;-moz-border-radius: 5px;float: right;">
            <button onclick="getMacs(\''.$section.'\',\''.$plugin.'\')">Load MAC</button>
            <button onclick="checkStatus(\'xbee_configuration\',\''.$section.'\',\''.$plugin.'\',\'zigbee\')">Check status</button>
        </div>
    </div>';


    return $list;
}
?>