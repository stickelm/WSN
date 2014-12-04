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
include_once $API_core.'list_operators.php';
include_once $API_core.'parser_wvdial.php';
include_once $API_core.'json_api.php';
include_once $API_core.'save_gprs.php';
include_once $base_plugin.'php/refresh_gprs.php';
include_once $base_plugin.'php/display_gprs_info.php';
include_once $API_core.'form_fields_check.php';

$operators_file_path=$base_plugin.'data/operators.txt';
$data=jsondecode($_POST['form_fields']);

if ($_POST['action']=='country')
{
    $str=print_r($data,true);
    //str_replace("\\n", "", $str);
    //response_additem("return", '<pre>'.$str.'</pre>');
    //response_additem("script", 'alert("'.$data['country_list'].'")');
    refresh_gprs($data['country_list'],'');
}
elseif ($_POST['action']=='operator')
{
    $str=print_r($data,true);
    //response_additem("return", '<pre>'.$str.'</pre>');
    refresh_gprs($data['country_list'],$data['country_operators']);
}
elseif ($_POST['action']=='save')
{

    $fields_check_types = Array (
        'phone'  => Array ('ms_mandatory'),
        'init1'  => Array ('ms_mandatory'),
        'PIN'  => Array ('ms_numerical')
        );
    if(are_form_fields_valid ($data, $fields_check_types))
    {
        exec("sudo remountrw");

        save_gprs($data); // generates wvdial.conf
        exec('sudo cp '.$base_plugin.'data/wvdial.conf /etc/wvdial.conf 2>&1 >/dev/null &');
        exec('sudo cp '.$base_plugin.'data/wvdial_pin.conf /etc/wvdial_pin.conf 2>&1 >/dev/null &');

        if($_POST['setAsDefault'] == 'on')
        {
            //exec("sudo chmod a+x /etc/init.d/wvdiald.sh", $ret);
            exec("sudo mv /etc/init.d/wvdiald.tc.sh /etc/init.d/wvdiald.sh");
            exec ('echo "ath0|Right|ppp0" > '. $base_plugin.'../e0_join/data/join.conf');

            exec("cat /mnt/lib/cfg/initialConf", $initConf);
            if(strstr($initConf['0'], "wifiMesh"))
            {
               exec('sudo cp '.$base_plugin.'data/joinmeshppp.sh /etc/init.d/join.sh 2>&1 >/dev/null &');
               exec ('echo "ath1|Right|ppp0" >> '. $base_plugin.'../e0_join/data/join.conf');
            }
            else
            {
               exec('sudo cp '.$base_plugin.'data/joinppp.sh /etc/init.d/join.sh 2>&1 >/dev/null &');
            }
        }
        else
        {
            //exec("sudo chmod a-x /etc/init.d/wvdiald.sh", $ret);
            if(file_exists("/etc/init.d/wvdiald.sh"))
            {
                exec("sudo mv /etc/init.d/wvdiald.sh /etc/init.d/wvdiald.tc.sh");
            }
            exec ('echo "ath0|Right|eth0" > '. $base_plugin.'../e0_join/data/join.conf');

            exec("cat /mnt/lib/cfg/initialConf", $initConf);
            if(strstr($initConf['0'], "wifiMesh"))
            {
               exec('sudo cp '.$base_plugin.'data/joinmesheth.sh /etc/init.d/join.sh 2>&1 >/dev/null &');
               exec ('echo "ath1|Right|eth0" >> '. $base_plugin.'../e0_join/data/join.conf');
            }
            else
            {
               exec('sudo cp '.$base_plugin.'data/joineth.sh /etc/init.d/join.sh 2>&1 >/dev/null &');
            }
        }

        exec("sudo remountro");

        //response_additem("script", 'alert("Data saved.")');
    response_additem("script", 'endnotify()');
    response_additem("script", 'notify("save.png", "Data saved")');
    response_additem("script", 'fadenotify()');
    }
}
elseif ($_POST['action']=='connect0')
{

    $fields_check_types = Array (
        'phone'  => Array ('ms_mandatory'),
        'init1'  => Array ('ms_mandatory'),
        'PIN'  => Array ('ms_numerical')
        );
    if(are_form_fields_valid ($data, $fields_check_types))
    {
        exec("sudo remountrw");

        $existConf = 0;
        if(file_exists($base_plugin.'data/wvdial.conf'))
        {
            $existConf = 1;
        }

        save_gprs($data); // generates wvdial.conf
        exec('sudo cp '.$base_plugin.'data/wvdial.conf /etc/wvdial.conf 2>&1 >/dev/null &');
        exec('sudo cp '.$base_plugin.'data/wvdial_pin.conf /etc/wvdial_pin.conf 2>&1 >/dev/null &');

        if($existConf == 0)
        {
            exec('sudo rm '.$base_plugin.'data/wvdial.conf');
        }

        exec("sudo remountro");

    response_additem("script", 'notify("loadinfo.net.gif", "Connecting.....")');
    }
}
elseif ($_POST['action']=='connect1')
{

    $fields_check_types = Array (
        'phone'  => Array ('ms_mandatory'),
        'init1'  => Array ('ms_mandatory'),
        'PIN'  => Array ('ms_numerical')
        );
    if(are_form_fields_valid ($data, $fields_check_types))
    {
        exec("sudo remountrw");

        exec('sudo wvdial > /dev/null 2>&1 &');

        exec("sudo remountro");

    response_additem("script", 'notify("loadinfo.net.gif", "Connecting.......")');
    }
    else
    {
        response_additem("script", 'notify("fail.png", "Connection failed")');
        response_additem("status", '0');
    }
}
elseif ($_POST['action']=='connect2')
{

    $fields_check_types = Array (
        'phone'  => Array ('ms_mandatory'),
        'init1'  => Array ('ms_mandatory'),
        'PIN'  => Array ('ms_numerical')
        );
    if(are_form_fields_valid ($data, $fields_check_types))
    {
        exec("sudo remountrw");

        exec('sudo sh '.$base_plugin.'data/wvdial_connect.sh', $ret);

        exec ('echo "ath0|Right|ppp0" > '. $base_plugin.'../e0_join/data/join.conf');

        exec("sudo remountro");

    }
    
    $isPppIPa = exec("sudo ifconfig ppp0 | grep 'inet addr' | cut -d: -f2 | cut -d' ' -f1 | wc -l", $isPppIP);
    $pppIPa = exec("sudo ifconfig ppp0 | grep 'inet addr' | cut -d: -f2 | cut -d' ' -f1", $pppIP);

    if($isPppIP['0'] == '1')
    {
        response_additem("script", '$("#GPRSStatus").removeClass("disconnected");
                                    $("#GPRSStatus").addClass("connected");
                                    $("#GPRSStatus").html("<b>Connected</b><br><br>3G IP: '.$pppIP['0'].'<br>");');

        response_additem("script", 'endnotify()');
        response_additem("script", 'notify("icono-i.png", "Connected<br><br>3G IP: '.$ret['0'].'")');
        response_additem("script", 'fadenotify()');
        response_additem("script", '$("#bloqueante").hide();');
    }
    else
    {
        response_additem("script", 'endnotify()');
        response_additem("script", 'notify("fail.png", "Not connected")');
        response_additem("script", 'fadenotify()');
        response_additem("script", '$("#bloqueante").hide();');
    }
}
elseif ($_POST['action']=='disconnect')
{
        exec("sudo remountrw");
        exec('sudo sh '.$base_plugin.'data/wvdial_disconnect.sh');
        exec ('echo "ath0|Right|eth0" > '. $base_plugin.'../e0_join/data/join.conf');
    
    response_additem("script", '$("#GPRSStatus").removeClass("connected");$("#GPRSStatus").addClass("disconnected");$("#GPRSStatus").html("<b>Disonnected</b>");');
    response_additem("script", 'endnotify()');
    response_additem("script", 'notify("icono-i.png", "Disconnected")');
    response_additem("script", 'fadenotify()');
        exec("sudo remountro");
}
//endSaveAlert();
// Return the response to javascript
response_return();

?>
