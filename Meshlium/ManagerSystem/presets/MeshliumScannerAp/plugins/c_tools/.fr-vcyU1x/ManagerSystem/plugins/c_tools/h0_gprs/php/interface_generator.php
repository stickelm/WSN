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
function make_inputs($hcid_conf)
{
    global $base_plugin, $section, $plugin;

    //$list.="<pre>".print_r($hcid_conf,true)."</pre>";
    exec("cat /etc/bluetooth/hcid.conf | grep name | cut -d'\"' -f2", $BtName);
    exec("cat ".$base_plugin."data/interval", $BtInterval);
    exec("cat /etc/bluetooth/hcid.conf | grep iscan", $BtVisible);


    $list.='
        <div>
            <form id="hcid_configuration" name="hcid_configuration">
                <table style="float: left;">
                    <tbody>
                        <tr>
                            <td>
                                <span><b>Scan interval</b></span>
                            </td>
                            <td>
                                <input type="text" class="ms_mandatory ms_numerical" name="interval" id="interval" maxlength="4" value="'.$BtInterval['0'].'" />
                            </td>
                            <td>
                                <span>Seconds</span>
                            </td>
                            <td><div id="interval_ms_cte" class="ms_cte"></div></td>
                        </tr>
                    </tbody>
                </table>';


$list.= "<div id='daemonStatus'>";
if (exec("ps ax | grep GpsScan | grep -v grep | wc -l") == 1)
{
   $list.= '<div id="dRunning"></div> <span> <b>Daemon running</b></span>';
}
elseif (exec("ps ax | grep GpsScan | grep -v grep | wc -l") == 0)
{
   $list.= '<div id="dStopped"></div> <span id="changeDaemonStatus" onclick="startGpsDaemon(\''.$section.'\',\''.$plugin.'\')"> <b>Start daemon</b></span>';
}
else
{
   $list.= '<b>Problem</b> - <b>killall</b>';
}
$list.= "</div>";


                $list.='
            </form>
            <div style="clear: both;"></div>
            <div class="right_align">
                <input type="button" class="bsave" onclick="complex_ajax_call(\'hcid_configuration\',\'output\',\''.$section.'\',\''.$plugin.'\',\'save\');" value="Save" />
            </div>
        </div>';

    return $list;
}

function make_storedData()
{
    global $base_plugin, $section, $plugin;
    //$list = '<span style="float: left;"><b><br>Files </b> </span>';
    //$list .= '<div style="width: 472px; -moz-border-radius: 5px;padding: 3px; background: white; border: 1px solid #898989; float: right;"><input type="checkbox" style="float: left;" /> <span style="float: left;margin-top: 3px;"><b>Store in a file</b></span><button style="float: right;">Save</button></div><div style="clear: both;"></div>';
    //$list .= '<br>';
    $list .= '<div id="fileList" class="fileListSM"><b>File list</b><hr />';
    exec("ls /mnt/user/gps_data/", $files);
    exec("cat ".$base_plugin."data/selectedFile", $selectedToSave);
    foreach($files as $num => $file) {
        if($file == $selectedToSave['0'])
        {
            $list .= '<div class="item" onclick="toogleFileSelect(this);" id="'.$file.'"><div class="fileEdit"></div><span style="vertical-align:super; margin-left: 10px;"><b>'.$file.'</b></span><br></div>';
        }
        else
        {
            $list .= '<div class="item" onclick="toogleFileSelect(this);" id="'.$file.'"><div class="file"></div><span style="vertical-align:super; margin-left: 10px;">'.$file.'</span><br></div>';
        }
    }
    $list .= '</div>
    <div style="float: left; margin-left: 10px;">
        <div style="width: 472px; -moz-border-radius: 5px;padding: 3px; background: white; border: 1px solid #898989; ">
            <div class="running" id="localFileRunning" ';
            if(!file_exists($base_plugin.'data/localFile'))
            {
                $list .= 'style="display: none;"';
            }
            $list .= '></div>
            <input type="checkbox" style="float: left;" id="makeLocalFile" ';
            if(file_exists($base_plugin.'data/localFile'))
            {
                $list .= ' checked ';
            }
            $list .= ' />
            <span style="float: left;margin-top: 3px;"><b>Store frames in a file</b></span>
            <button style="float: right;" onclick="useLocalFile(\''.$section.'\',\''.$plugin.'\',$(\'#makeLocalFile:checked\').val())">Save</button>
            <div style="clear: both;"></div>
        </div><br>
        <button onclick="selectFile(\''.$section.'\',\''.$plugin.'\');" >Select file</button> <span style="padding: 0 10px;color: #898989;font-size: 15px;"> | </span> <input id="newFileName" maxlength="16" type="text" /> <button onclick="createFile(\''.$section.'\',\''.$plugin.'\');">Create new file</button><br><br>
        <button class="disabled" onclick="downloadFile(\''.$section.'\',\''.$plugin.'\');" >Download file</button>
        
        <button onclick="deleteFile(\''.$section.'\',\''.$plugin.'\');" >Delete file</button>
        <button onclick="viewFile(\''.$section.'\',\''.$plugin.'\', $(\'#fileNumerToShow\').val());">Show data</button>
        <span style="margin-left: 10px;"> Last </span><input id="fileNumerToShow" maxlength="10" style="width: 50px;margin: 0;" type="text" value="100"><span> lines.</span>
    </div>

    <div style="clear: right;"></div>
    <div id="fileViewer" style="font-family:monospace; display: none; -moz-border-radius:5px;background-color:white;border:1px solid #898989;float:right;height:300px;margin:15px 0 0 0;overflow:auto;padding:10px;width:459px;"></div>
    <div style="clear: both;"></div><br>';

    return $list;
}

function make_storedDataLocalDB()
{
    global $base_plugin, $section, $plugin;

    $DATABASE = exec('cat /mnt/lib/cfg/gpsDBSetup | grep -n "" | grep 1: | cut -d\':\' -f2');
    $TABLE = exec('cat /mnt/lib/cfg/gpsDBSetup | grep -n "" | grep 2: | cut -d\':\' -f2');
    $IP = exec('cat /mnt/lib/cfg/gpsDBSetup | grep -n "" | grep 3: | cut -d\':\' -f2');
    $PORT = exec('cat /mnt/lib/cfg/gpsDBSetup | grep -n "" | grep 4: | cut -d\':\' -f2');
    $USER = exec('cat /mnt/lib/cfg/gpsDBSetup | grep -n "" | grep 5: | cut -d\':\' -f2');
    $PASS = exec('cat /mnt/lib/cfg/gpsDBSetup | grep -n "" | grep 6: | cut -d\':\' -f2');

    $list .= '<div id="" class="DBConnection"><b>Connection data</b><hr />';
                $list .= '<br>
                <table>
                    <tbody>
                        <tr>
                            <td style="text-indent: 0px;"><span><b>Database: </b></span></td>
                            <td style="text-indent: 0px;"><input type="text" readonly disabled value="'.$DATABASE.'" /></td>
                        </tr>
                        <tr>
                            <td style="text-indent: 0px;"><span><b>Table: </b></span></td>
                            <td style="text-indent: 0px;"><input type="text" readonly disabled value="'.$TABLE.'" /></td>
                        </tr>
                        <tr>
                            <td style="text-indent: 0px;"><span><b>IP: </b></span></td>
                            <td style="text-indent: 0px;"><input type="text" readonly disabled value="'.$IP.'" /></td>
                        </tr>
                        <tr>
                            <td style="text-indent: 0px;"><span><b>Port: </b></span></td>
                            <td style="text-indent: 0px;"><input type="text" readonly disabled value="'.$PORT.'" /></td>
                        </tr>
                        <tr>
                            <td style="text-indent: 0px;"><span><b>User: </b></span></td>
                            <td style="text-indent: 0px;"><input type="text" readonly disabled value="'.$USER.'" /></td>
                        </tr>
                        <tr>
                            <td style="text-indent: 0px;"><span><b>Password: </b></span></td>
                            <td style="text-indent: 0px;"><input type="text" readonly disabled value="'.$PASS.'" /></td>
                        </tr>
                    </tbody>
                </table>';
    $list .= '</div>

    <div style="float: left; margin-left: 10px;">
        <div style="width: 472px; -moz-border-radius: 5px;padding: 3px; background: white; border: 1px solid #898989; ">
            <div class="running" id="localDBRunning" ';
            if(!file_exists($base_plugin.'data/localDB'))
            {
                $list .= 'style="display: none;"';
            }
            $list .= '></div>
            <input type="checkbox" style="float: left;" id="makeLocalDB" ';
            if(file_exists($base_plugin.'data/localDB'))
            {
                $list .= ' checked ';
            }
            $list .= ' />
            <span style="float: left;margin-top: 3px;"><b>Store frames in the local data base</b></span>
            <button style="float: right;" onclick="useLocalDB(\''.$section.'\',\''.$plugin.'\',$(\'#makeLocalDB:checked\').val())">Save</button>
            <div style="clear: both;"></div>
        </div>
        <br>

        <button onclick="showlocalDB(\''.$section.'\',\''.$plugin.'\', $(\'#localDbNumerToShow\').val());">Show data</button>
        <span style="margin-left: 10px;"> Last </span><input id="localDbNumerToShow"  maxlength="10" style="width: 50px;margin: 0;" type="text" value="100"><span> insertions.</span>
    </div>

    <div style="clear: right;"></div>
    <div id="localDataViewer" style="-moz-border-radius:5px;background-color:white;border:1px solid #898989;float:right;height:300px;margin:15px 0 0 0;overflow:auto;padding:10px;width:459px;"></div>
    <div style="clear: both;"></div><br>';

    return $list;
}


function make_storedDataExtDB()
{
    global $base_plugin, $section, $plugin;

    exec("cat ".$base_plugin."data/ExtDataConnection | grep -n '' | grep 1: | cut -d: -f2", $extDatabase);
    exec("cat ".$base_plugin."data/ExtDataConnection | grep -n '' | grep 2: | cut -d: -f2", $extTable);
    exec("cat ".$base_plugin."data/ExtDataConnection | grep -n '' | grep 3: | cut -d: -f2", $extIP);
    exec("cat ".$base_plugin."data/ExtDataConnection | grep -n '' | grep 4: | cut -d: -f2", $extPort);
    exec("cat ".$base_plugin."data/ExtDataConnection | grep -n '' | grep 5: | cut -d: -f2", $extUser);
    exec("cat ".$base_plugin."data/ExtDataConnection | grep -n '' | grep 6: | cut -d: -f2", $extPassword);

    $list .= '<div id="" class="DBConnection"><b>Connection data</b><hr />';
                $list .= '<br><form name="ExtConnection" id="ExtConnection">
                <table>
                    <tbody>
                        <tr>
                            <td style="text-indent: 0px;"><span><b>Database: </b></span></td>
                            <td style="text-indent: 0px;"><input type="text" class="ms_alnum ms_mandatory" id="ExtDatabase" maxlength="16" name="ExtDatabase"';
                            if(isset ($extDatabase['0']))
                            {
                                $list .= ' value="'.$extDatabase['0'].'" ';
                            }
                            $list .= '/></td>
                        </tr>
                        <tr>
                            <td colspan="2"><div id="ExtDatabase_ms_cte" class="ms_cte"></div></td>
                        </tr>
                        <tr>
                            <td style="text-indent: 0px;"><span><b>Table: </b></span></td>
                            <td style="text-indent: 0px;"><input type="text" class="ms_alnum ms_mandatory" id="ExtTable" maxlength="16" name="ExtTable"';
                            if(isset ($extTable['0']))
                            {
                                $list .= ' value="'.$extTable['0'].'" ';
                            }
                            $list .= ' /></td>
                        </tr>
                        <tr>
                            <td colspan="2"><div id="ExtTable_ms_cte" class="ms_cte"></div></td>
                        </tr>
                        <tr>
                            <td style="text-indent: 0px;"><span><b>IP: </b></span></td>
                            <td style="text-indent: 0px;"><input type="text" class="ms_mandatory ms_ip" id="ExtIP" maxlength="16" name="ExtIP"';
                            if(isset ($extIP['0']))
                            {
                                $list .= ' value="'.$extIP['0'].'" ';
                            }
                            $list .= ' /></td>
                        </tr>
                        <tr>
                            <td colspan="2"><div id="ExtIP_ms_cte" class="ms_cte"></div></td>
                        </tr>
                        <tr>
                            <td style="text-indent: 0px;"><span><b>Port: </b></span></td>
                            <td style="text-indent: 0px;"><input type="text" class="ms_mandatory ms_numerical" id="ExtPort" maxlength="16" name="ExtPort"';
                            if(isset ($extPort['0']))
                            {
                                $list .= ' value="'.$extPort['0'].'" ';
                            }
                            else
                            {
                                $list .= ' value="3306" ';
                            }
                            $list .= ' /></td>
                        </tr>
                        <tr>
                            <td colspan="2"><div id="ExtPort_ms_cte" class="ms_cte"></div></td>
                        </tr>
                        <tr>
                            <td style="text-indent: 0px;"><span><b>User: </b></span></td>
                            <td style="text-indent: 0px;"><input type="text" class="ms_alnum ms_mandatory" id="ExtUser" maxlength="16" name="ExtUser"';
                            if(isset ($extUser['0']))
                            {
                                $list .= ' value="'.$extUser['0'].'" ';
                            }
                            $list .= ' /></td>
                        </tr>
                        <tr>
                            <td colspan="2"><div id="ExtUser_ms_cte" class="ms_cte"></div></td>
                        </tr>
                        <tr>
                            <td style="text-indent: 0px;"><span><b>Password: </b></span></td>
                            <td style="text-indent: 0px;"><input type="text" class="ms_alnum ms_mandatory" id="ExtPassword" maxlength="16" name="ExtPassword"';
                            if(isset ($extPassword['0']))
                            {
                                $list .= ' value="'.$extPassword['0'].'" ';
                            }
                            $list .= ' /></td>
                        </tr>
                        <tr>
                            <td colspan="2"><div id="ExtPassword_ms_cte" class="ms_cte"></div></td>
                        </tr>
                    </tbody>
                </table></form>
                <br><br>
                <button style="float: right; margin-right: 10px;" onclick="saveDataConnection(\''.$section.'\',\''.$plugin.'\', \'ExtConnection\');">Save</button>
                <button style="float: right; margin-right: 10px;" onclick="checkConnection(\''.$section.'\',\''.$plugin.'\', \'ExtConnection\');">Check Connection</button>
                        
                ';
    $list .= '</div>

    <div style="float: left; margin-left: 10px;">
        <div style="width: 472px; -moz-border-radius: 5px;padding: 3px; background: white; border: 1px solid #898989; ">
            <div class="running" id="extDBRunning" ';
            if(!file_exists($base_plugin.'data/extDB'))
            {
                $list .= 'style="display: none;"';
            }
            $list .= '></div>
            <input type="checkbox" style="float: left;" id="makeExtDB" ';
            if(file_exists($base_plugin.'data/extDB'))
            {
                $list .= ' checked ';
            }
            $list .= ' />
            <span style="float: left;margin-top: 3px;"><b>Store frames in the external data base</b></span>
            <button style="float: right;" onclick="useExtDB(\''.$section.'\',\''.$plugin.'\',$(\'#makeExtDB:checked\').val())">Save</button>
            <div style="clear: both;"></div>
        </div>
        <br>

        <button onclick="showextDB(\''.$section.'\',\''.$plugin.'\', \'ExtConnection\', $(\'#extDbNumerToShow\').val());">Show data</button>
        <span style="margin-left: 10px;"> Last </span><input id="extDbNumerToShow" maxlength="10"  style="width: 50px;margin: 0;" type="text" value="100"><span style="margin-right: 10px;"> insertions.</span>
        <button onclick="showSqlScript(\''.$section.'\',\''.$plugin.'\');">Show sql script</button><span style="color: #676767; font-size: 10px;padding-left:5px;vertical-align:text-bottom;">(to create database table)</span>
    </div>

    <div style="clear: right;"></div>
    <div id="extDataViewer" style="-moz-border-radius:5px;background-color:white;border:1px solid #898989;float:right;height:300px;margin:15px 0 0 0;overflow:auto;padding:10px;width:459px;"></div>
    <div style="clear: both;"></div><br>';

    return $list;
}
function make_storedDataNow(){return "";}

function make_interface()
{
    global $url_plugin;
    global $base_plugin;
    global $section;
    global $plugin;
    global $init_sensor_bt;
    $list = "";

    exec("cat ".$base_plugin."data/interval", $interval);

    $list.='<div class="title2">GPS</div>';
    $list.='<div id="plugin_content" style="padding-bottom:5px;padding-right:0">';
    $hcid_conf=parse_hcid('/etc/bluetooth/hcid.conf');
    $list.=make_inputs($hcid_conf);
    $list.='</div>';

    $list.='<div class="title2">Captured Data</div><br>';
    $list.='
    <div id="tab1" class="tab selectedTab" style="margin-left: 15px;" onclick="loadTab(\'tab1\')" >Local file</div>
    <div id="tab2" class="tab" onclick="loadTab(\'tab2\')">Local DataBase</div>
    <div id="tab3" class="tab" onclick="loadTab(\'tab3\')">External Database</div>
    <div id="tab4" class="tab" onclick="loadTab(\'tab4\')">Show me NOW</div>
    <div style="clear: both;"></div>
    ';
    $list.='<div id="tab1content">';
        $list.=make_storedData();
    //$list.='<pre>'.print_r($hcid_conf, true)."</pre>";
    $list.='</div>';
    $list.='<div id="tab2content" style="display: none;">';
        $list.=make_storedDataLocalDB();
    $list.='</div>';
    $list.='<div id="tab3content" style="display: none;">';
        $list.=make_storedDataExtDB();
    $list.='</div>';
    $list.='<div id="tab4content" style="display: none;">';
        //$list.=make_storedDataNow();
        $list .= '<button id="showMeNowStart" onclick="showMeNow(\''.$section.'\',\''.$plugin.'\',$(\'#nonStop:checked\').val(), \''.$interval['0'].'\');" >Start Scan</button>
                  <button id="showMeNowStop" onclick="stopMeNow(\''.$section.'\',\''.$plugin.'\');" style="display: none;" >Stop Scan</button>
                    <input name="nonStop" id="nonStop" type="checkbox" /><span>Use the defined Scan interval</span>
                    <br><br>';
        $list .= '<div id="tab4contentScan" style="overflow: auto;width: 685px; height: 300px;padding: 10px;background: white; border: 1px solid #898989;"></div>';
    $list.='</div>';

    return $list;
}
?>