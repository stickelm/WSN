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

// Predefined variables:
// $section contains the section folder name.
// echo "section=".$section."<br>";
// $plugin contains the plugin folder name.
// echo "plugin=".$plugin."<br>";
// $section and $plugin can be used to make a link to this plugin by just reference
// echo "<a href=\"index.php?section=$section&plugin=$plugin\">This plugin</a>"."<br>";
// $base_plugin contains the path that must be used as start to includes for
// plugin includes that need the local path.
// example: include_once $base_plugin.'php/my_include.php';
// echo "base_plugin=".$base_plugin."<br>";
// $url_plugin contains the url base that must be used to include html items
// such as images.
// example: <img src="'.url_plugin.'images/my_image.png">
// echo "url_plugin=".$url_plugin."<br>";
// $API_core contains the path to the core API folder.
// example: include_once $API_core.'is_active.php';
// echo "API_core=".$API_core."<br>";

// Plugin server produced data will returned to the ajax call that made the
// request.
include_once $API_core.'complex_ajax_return_functions.php';
include_once $API_core.'json_api.php';

function save802($postData)
{
    global $base_plugin;
            exec("sudo remountrw");
    $writepath=$base_plugin.'data/temp_conf';
    $fp=fopen($writepath,"w");

    fwrite($fp, $postData['atid']."\n");
    exec("sudo ".$base_plugin."bin/exec_xbee S0 5 atid".$postData['atid']);
  //  echo "sudo ".$base_plugin."bin/exec_xbee S0 5 atid".$postData['atid'];
    sleep(1);
    fwrite($fp, $postData['atch']."\n");
    exec("sudo ".$base_plugin."bin/exec_xbee S0 5 atch".$postData['atch']);
   // echo "sudo ".$base_plugin."bin/exec_xbee S0 5 atch".$postData['atch'];
    sleep(1);
    fwrite($fp, $postData['atmy']."\n");
    exec("sudo ".$base_plugin."bin/exec_xbee S0 5 atmy".$postData['atmy']);
   // echo"sudo ".$base_plugin."bin/exec_xbee S0 5 atmy".$postData['atmy'] ;
    sleep(1);
    fwrite($fp, strtolower($postData['atni'])."\n");
    exec("sudo ".$base_plugin."bin/exec_xbee S0 5 atni".strtolower($postData['atni']));
    //echo "sudo ".$base_plugin."bin/exec_xbee S0 5 atni".$postData['atni'];
    sleep(1);
    fwrite($fp, $postData['atpl']."\n");
    exec("sudo ".$base_plugin."bin/exec_xbee S0 5 atpl".$postData['atpl']);
    //echo "sudo ".$base_plugin."bin/exec_xbee S0 5 atpl".$postData['atpl'];
    sleep(1);
    fwrite($fp, $postData['atee']."\n");
    exec("sudo ".$base_plugin."bin/exec_xbee S0 5 atee".$postData['atee']);
    //echo "sudo ".$base_plugin."bin/exec_xbee S0 5 atee".$postData['atee'];
    sleep(1);
    fwrite($fp, $postData['atky']."\n");
    exec("sudo ".$base_plugin."bin/exec_xbee S0 5 atky".$postData['atky']);
    //echo "sudo ".$base_plugin."bin/exec_xbee S0 5 atky".$postData['atky'];
    sleep(1);
exec("sudo ".$base_plugin."bin/exec_xbee S0 5 atwr");
sleep(1);
    fwrite($fp, $postData['atsh']."\n");
    fwrite($fp, $postData['atsl']."\n");

    fclose($fp);

    exec("paste ".$base_plugin."data/802_conf ".$base_plugin."data/temp_conf > ".$base_plugin."data/tmp;
          cat ".$base_plugin."data/tmp | tr -d '\t' > ".$base_plugin."data/802.conf ;
          rm ".$base_plugin."data/tmp;
          rm ".$base_plugin."data/temp_conf");
}

function check802($values, $ats)
{
    echo "<table>";

    echo "<tr><td><b>Network ID: </b></td>";
    if ($ats[2] == "atid:".$values['atid'])
        echo "<td><b style='color: green;'>OK</b></td></tr>";
    else
        echo "<td><b style='color: red;'>FAIL → ".substr($ats[2], 5)."</b></td></tr>";

    echo "<tr><td><b>Channel: </b></td>";
    if (strtolower($ats[7]) == "atch:".$values['atch'])
        echo "<td><b style='color: green;'>OK</b></td></tr>";
    else
        echo "<td><b style='color: red;'>FAIL → ".substr($ats[7], 5)."</b></td></tr>";

    echo "<tr><td><b>Network Address: </b></td>";
    if ($ats[3] == "atmy:".$values['atmy'])
        echo "<td><b style='color: green;'>OK</b></td></tr>";
    else
        echo "<td><b style='color: red;'>FAIL → ".substr($ats[3], 5)."</b></td></tr>";

    echo "<tr><td><b>Node ID: </b></td>";
    if (strtolower($ats[8]) == "atni:".$values['atni'])
        echo "<td><b style='color: green;'>OK</b></td></tr>";
    else
        echo "<td><b style='color: red;'>FAIL → ".substr($ats[8], 5)."</b></td></tr>";

    echo "<tr><td><b>Power Level: </b></td>";
    if ($ats[10] == "atpl:".$values['atpl'])
        echo "<td><b style='color: green;'>OK</b></td></tr>";
    else
        echo "<td><b style='color: red;'>FAIL → ".substr($ats[10], 5)."</b></td></tr>";

    echo "<tr><td><b>Encrypted Mode: </b></td>";
    if ($ats[9] == "atee:".$values['atee'])
        echo "<td><b style='color: green;'>OK</b></td></tr>";
    else
        echo "<td><b style='color: red;'>FAIL → ".substr($ats[9], 5)."</b></td></tr>";

    echo "</table><br>";
}

function save868($postData)
{
    global $base_plugin;
            exec("sudo remountrw");
    $writepath=$base_plugin.'data/temp_conf';
    $fp=fopen($writepath,"w");

    fwrite($fp, $postData['atid']."\n");
    exec("sudo ".$base_plugin."bin/exec_xbee S0 5 atid".$postData['atid']);
  //  echo "sudo ".$base_plugin."bin/exec_xbee S0 5 atid".$postData['atid'];
    sleep(1);
    fwrite($fp, strtolower($postData['atni'])."\n");
    exec("sudo ".$base_plugin."bin/exec_xbee S0 5 atni".strtolower($postData['atni']));
    //echo "sudo ".$base_plugin."bin/exec_xbee S0 5 atni".$postData['atni'];
    sleep(1);
    fwrite($fp, $postData['atpl']."\n");
    exec("sudo ".$base_plugin."bin/exec_xbee S0 5 atpl".$postData['atpl']);
    //echo "sudo ".$base_plugin."bin/exec_xbee S0 5 atpl".$postData['atpl'];
    sleep(1);
    fwrite($fp, $postData['atee']."\n");
    exec("sudo ".$base_plugin."bin/exec_xbee S0 5 atee".$postData['atee']);
    //echo "sudo ".$base_plugin."bin/exec_xbee S0 5 atee".$postData['atee'];
    sleep(1);
    fwrite($fp, $postData['atky']."\n");
    exec("sudo ".$base_plugin."bin/exec_xbee S0 5 atky".$postData['atky']);
    //echo "sudo ".$base_plugin."bin/exec_xbee S0 5 atky".$postData['atky'];
    sleep(1);
exec("sudo ".$base_plugin."bin/exec_xbee S0 5 atwr");
sleep(1);
    fwrite($fp, $postData['atsh']."\n");
    fwrite($fp, $postData['atsl']."\n");

    fclose($fp);

    exec("paste ".$base_plugin."data/868_conf ".$base_plugin."data/temp_conf > ".$base_plugin."data/tmp;
          cat ".$base_plugin."data/tmp | tr -d '\t' > ".$base_plugin."data/868.conf ;
          rm ".$base_plugin."data/tmp;
          rm ".$base_plugin."data/temp_conf");
}

function check868($values, $ats)
{
    echo "<table>";

    echo "<tr><td><b>Network ID: </b></td>";
    if ($ats[2] == "atid:".$values['atid'])
        echo "<td><b style='color: green;'>OK</b></td></tr>";
    else
        echo "<td><b style='color: red;'>FAIL → ".substr($ats[2], 5)."</b></td></tr>";

    echo "<tr><td><b>Node ID: </b></td>";
    if (strtolower($ats[8]) == "atni:".$values['atni'])
        echo "<td><b style='color: green;'>OK</b></td></tr>";
    else
        echo "<td><b style='color: red;'>FAIL → ".substr($ats[8], 5)."</b></td></tr>";

    echo "<tr><td><b>Power Level: </b></td>";
    if ($ats[10] == "atpl:".$values['atpl'])
        echo "<td><b style='color: green;'>OK</b></td></tr>";
    else
        echo "<td><b style='color: red;'>FAIL → ".substr($ats[10], 5)."</b></td></tr>";

    echo "<tr><td><b>Encrypted Mode: </b></td>";
    if ($ats[9] == "atee:".$values['atee'])
        echo "<td><b style='color: green;'>OK</b></td></tr>";
    else
        echo "<td><b style='color: red;'>FAIL → ".substr($ats[9], 5)."</b></td></tr>";

    echo "</table><br>";
}

function save900($postData)
{
    global $base_plugin;
            exec("sudo remountrw");
    $writepath=$base_plugin.'data/temp_conf';
    $fp=fopen($writepath,"w");

    fwrite($fp, $postData['atid']."\n");
    exec("sudo ".$base_plugin."bin/exec_xbee S0 5 atid".$postData['atid']);
  //  echo "sudo ".$base_plugin."bin/exec_xbee S0 5 atid".$postData['atid'];
    sleep(1);
    fwrite($fp, strtolower($postData['atni'])."\n");
    exec("sudo ".$base_plugin."bin/exec_xbee S0 5 atni".strtolower($postData['atni']));
    //echo "sudo ".$base_plugin."bin/exec_xbee S0 5 atni".$postData['atni'];
    sleep(1);
    fwrite($fp, $postData['atee']."\n");
    exec("sudo ".$base_plugin."bin/exec_xbee S0 5 atee".$postData['atee']);
    //echo "sudo ".$base_plugin."bin/exec_xbee S0 5 atee".$postData['atee'];
    sleep(1);
    fwrite($fp, $postData['atky']."\n");
    exec("sudo ".$base_plugin."bin/exec_xbee S0 5 atky".$postData['atky']);
    //echo "sudo ".$base_plugin."bin/exec_xbee S0 5 atky".$postData['atky'];
    sleep(1);
exec("sudo ".$base_plugin."bin/exec_xbee S0 5 atwr");
sleep(1);
    fwrite($fp, $postData['atsh']."\n");
    fwrite($fp, $postData['atsl']."\n");

    fclose($fp);

    exec("paste ".$base_plugin."data/900_conf ".$base_plugin."data/temp_conf > ".$base_plugin."data/tmp;
          cat ".$base_plugin."data/tmp | tr -d '\t' > ".$base_plugin."data/900.conf ;
          rm ".$base_plugin."data/tmp;
          rm ".$base_plugin."data/temp_conf");
}

function check900($values, $ats)
{
    echo "<table>";

    echo "<tr><td><b>Network ID: </b></td>";
    if ($ats[2] == "atid:".$values['atid'])
        echo "<td><b style='color: green;'>OK</b></td></tr>";
    else
        echo "<td><b style='color: red;'>FAIL → ".substr($ats[2], 5)."</b></td></tr>";

    echo "<tr><td><b>Node ID:</b></td> ";
    if (strtolower($ats[8]) == "atni:".$values['atni'])
        echo "<td><b style='color: green;'>OK</b></td></tr>";
    else
        echo "<td><b style='color: red;'>FAIL → ".substr($ats[8], 5)."</b></td></tr>";

    echo "<tr><td><b>Encrypted Mode: </b></td>";
    if ($ats[9] == "atee:".$values['atee'])
        echo "<td><b style='color: green;'>OK</b></td></tr>";
    else
        echo "<td><b style='color: red;'>FAIL → ".substr($ats[9], 5)."</b></td></tr>";

    echo "</table><br>";
}

function savezigbee($postData)
{
    global $base_plugin;
            exec("sudo remountrw");
    $writepath=$base_plugin.'data/temp_conf';
    $fp=fopen($writepath,"w");


    fwrite($fp, strtolower($postData['atni'])."\n");
    exec("sudo ".$base_plugin."bin/exec_xbee S0 5 atni".strtolower($postData['atni']));
    //echo "sudo ".$base_plugin."bin/exec_xbee S0 5 atni".$postData['atni'];
    sleep(1);
    fwrite($fp, $postData['atee']."\n");
    exec("sudo ".$base_plugin."bin/exec_xbee S0 5 atee".$postData['atee']);
    //echo "sudo ".$base_plugin."bin/exec_xbee S0 5 atee".$postData['atee'];
    sleep(1);
    fwrite($fp, $postData['atky']."\n");
    exec("sudo ".$base_plugin."bin/exec_xbee S0 5 atky".$postData['atky']);
    //echo "sudo ".$base_plugin."bin/exec_xbee S0 5 atky".$postData['atky'];
    sleep(1);
exec("sudo ".$base_plugin."bin/exec_xbee S0 5 atwr");
sleep(1);
    fwrite($fp, $postData['atsh']."\n");
    fwrite($fp, $postData['atsl']."\n");

    fclose($fp);

    exec("paste ".$base_plugin."data/zigbee_conf ".$base_plugin."data/temp_conf > ".$base_plugin."data/tmp;
          cat ".$base_plugin."data/tmp | tr -d '\t' > ".$base_plugin."data/zigbee.conf ;
          rm ".$base_plugin."data/tmp;
          rm ".$base_plugin."data/temp_conf");
}

function checkzigbee($values, $ats)
{
    echo "<table>";

    echo "<tr><td><b>Node ID: </b></td>";
    if (strtolower($ats[8]) == "atni:".$values['atni'])
        echo "<td><b style='color: green;'>OK</b></td></tr>";
    else
        echo "<td><b style='color: red;'>FAIL → ".substr($ats[8], 5)."</b></td></tr>";

    echo "<tr><td><b>Encrypted Mode: </b></td>";
    if ($ats[9] == "atee:".$values['atee'])
        echo "<td><b style='color: green;'>OK</b></td></tr>";
    else
        echo "<td><b style='color: red;'>FAIL → ".substr($ats[9], 5)."</b></td></tr>";

    echo "</table><br>";
}

function savexsc($postData)
{
    global $base_plugin;
            exec("sudo remountrw");
    $writepath=$base_plugin.'data/temp_conf';
    $fp=fopen($writepath,"w");

    fwrite($fp, $postData['atid']."\n");
    exec("sudo ".$base_plugin."bin/exec_xbee S0 5 atid".$postData['atid']);
  //  echo "sudo ".$base_plugin."bin/exec_xbee S0 5 atid".$postData['atid'];
    sleep(1);
    fwrite($fp, $postData['atch']."\n");
    exec("sudo ".$base_plugin."bin/exec_xbee S0 5 athp".$postData['atch']);
   // echo "sudo ".$base_plugin."bin/exec_xbee S0 5 atch".$postData['atch'];
    sleep(1);
    fwrite($fp, $postData['atmy']."\n");
    exec("sudo ".$base_plugin."bin/exec_xbee S0 5 atdt".$postData['atmy']);
   // echo"sudo ".$base_plugin."bin/exec_xbee S0 5 atmy".$postData['atmy'] ;
    sleep(1);
exec("sudo ".$base_plugin."bin/exec_xbee S0 5 atwr");
sleep(1);

    fwrite($fp, $postData['atsh']."\n");
    fwrite($fp, $postData['atsl']."\n");

    fclose($fp);

    exec("paste ".$base_plugin."data/802_conf ".$base_plugin."data/temp_conf > ".$base_plugin."data/tmp;
          cat ".$base_plugin."data/tmp | tr -d '\t' > ".$base_plugin."data/802.conf ;
          rm ".$base_plugin."data/tmp;
          rm ".$base_plugin."data/temp_conf");
}

function checkxsc($values, $ats)
{
    echo "<table>";

    echo "<tr><td><b>Network ID: </b></td>";
    if ($ats[2] == "atid:".$values['atid'])
        echo "<td><b style='color: green;'>OK</b></td></tr>";
    else
        echo "<td><b style='color: red;'>FAIL → ".substr($ats[2], 5)."</b></td></tr>";

    echo "<tr><td><b>Channel: </b></td>";
    if ($ats[7] == "athp:".strtolower($values['atch']))
        echo "<b style='color: green;'>OK</b></td></tr>";
    else
        echo "<b style='color: red;'>FAIL → ".substr($ats[7], 5)."</b></td></tr>";

    echo "<tr><td><b>Network Address: </b></td>";
    if ($ats[3] == "atdt:".$values['atmy'])
        echo "<b style='color: green;'>OK</b></td></tr>";
    else
        echo "<b style='color: red;'>FAIL → ".substr($ats[3], 5)."</b></td></tr>";

    echo "</table><br>";

}




if (!empty($_POST['action']))
{
    switch ($_POST['action'])
    {
        case "save":
            exec("sudo remountrw");
            exec("sudo /etc/init.d/ZigbeeScanD.sh stop");
            sleep(5);
            $values=jsondecode($_POST['form_fields']);
            $function = "save".$_POST['xbee'];
            $function($values);

                include_once 'php/interface_generator.php';

            response_additem("html", make_interface($_POST['xbee']), "plugin_main_div");
            exec("sudo /etc/init.d/ZigbeeScanD.sh start >/dev/null 2>&1 &");
            exec("sudo remountro");
            response_return();
            break;
        case "getmacs":
            exec("sudo /etc/init.d/ZigbeeScanD.sh stop");
            sleep(5);
            $length = 0;
            $count= 0;
            while (($length != 12) || ($length != 11))
            {
                unset ($ats);
                $length = 0;
                exec("sudo killall get_xbee");
                sleep(1);
                exec("sudo ".$base_plugin."bin/get_xbee S0 5 2>&1", $ats);
                $length = count($ats);
                if ($length == 12)
                {
                  if($ats['11'] == 'o')
                    break;
                }
                if ($length == 11)
                    break;
                $count++;
                if ($count >= 10)
                    break;
                sleep(4);
            }
            if($count == 10)
            {
                echo "-1";
            }
            else
            {
                $atsl=explode(":", $ats[5]);
                $atsh=explode(":", $ats[6]);
                echo $atsh[1]."#".$atsl[1];
            }
            exec("sudo /etc/init.d/ZigbeeScanD.sh start >/dev/null 2>&1 &");
            break;
        case "check":
            exec("sudo /etc/init.d/ZigbeeScanD.sh stop");
            sleep(5);
            $values=jsondecode($_POST['form_fields']);
            //echo "<br><b>Connecting to serial port ...</b><br>";
            $length = 0;
            $count= 0;
            while (($length != 12) || ($length != 11))
            {
                unset ($ats);
                $length = 0;
                exec("sudo killall get_xbee");
                sleep(1);
                exec("sudo ".$base_plugin."bin/get_xbee S0 5 2>&1", $ats);
                $length = count($ats);
                if ($length == 12)
                {
                  if($ats['11'] == 'o')
                    break;
                }
                if ($length == 11)
                    break;
                $count++;
                if ($count >= 10)
                    break;
                sleep(4);
            }
            if($count == 10)
            {
                echo "-1";
            }
            else
            {
                if(sizeof($ats) > 1)
                {
                    echo "<br><b>Connecting to serial port ...</b><br>";
                    echo "<b style='color: green;'>Connected.</b><br><br>";
                    $function = "check".$_POST['xbee'];
                    $function($values, $ats);
                }
                else
                {
                    echo "<br><b>Connecting to serial port ...</b><br>";
                    echo "<b style='color: red;'>Unable to connect</b>";
                }
            }
            exec("sudo /etc/init.d/ZigbeeScanD.sh start >/dev/null 2>&1 &");
            break;
        default:
            break;
    }
}

?>
