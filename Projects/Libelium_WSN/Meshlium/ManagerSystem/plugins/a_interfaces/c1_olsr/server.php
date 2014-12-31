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
include_once $API_core.'parser_olsrd.php';
include_once $API_core.'save_olsrd.php';
include_once $API_core.'form_fields_check.php';



function generate_olsrdConf($postData)
{
    global $base_plugin;

    $writepath=$base_plugin.'data/temp_olsr_conf';
    $fp=fopen($writepath,"w");

    fwrite($fp, "\n\n\n\n\n");
    if(is_int($postData['HelloInterval'])) {
        fwrite($fp, trim($postData['HelloInterval']).".0\n");
    } else {
        fwrite($fp, $postData['HelloInterval']."\n");        
    }
    fwrite($fp, "\n");
    if(is_int($postData['HelloValidityTime'])) {
        fwrite($fp, trim($postData['HelloValidityTime']).".0\n");
    } else {
        fwrite($fp, $postData['HelloValidityTime']."\n");        
    }    
    fwrite($fp, "\n");
    if(is_int($postData['TcInterval'])) {
        fwrite($fp, trim($postData['TcInterval']).".0\n");
    } else {
        fwrite($fp, $postData['TcInterval']."\n");
    }
    fwrite($fp, "\n");
    if(is_int($postData['TcValidityTime'])) {
        fwrite($fp, trim($postData['TcValidityTime']).".0\n");
    } else {
        fwrite($fp, $postData['TcValidityTime']."\n");
    }
    fwrite($fp, "\n\n\n\n\n");
    if(is_int($postData['HnaInterval'])) {
        fwrite($fp, trim($postData['HnaInterval']).".0\n");
    } else {
        fwrite($fp, $postData['HnaInterval']."\n");
    }
    fwrite($fp, "\n");
    if(is_int($postData['HnaValidityTime'])) {
        fwrite($fp, trim($postData['HnaValidityTime']).".0\n");
    } else {
        fwrite($fp, $postData['HnaValidityTime']."\n");
    }
    fwrite($fp, "\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n");

    if(isset($postData['isMeshGw']))
    {
        fwrite($fp, "Hna4\n");
        fwrite($fp, "\n");
        fwrite($fp, "{\n");
        fwrite($fp, "\n");
        fwrite($fp, " 0.0.0.0 0.0.0.0\n");
        fwrite($fp, "\n");
        fwrite($fp, "}\n\n");
    }

    fclose($fp);

    exec("paste ".$base_plugin."data/olsrd.conf_conf ".$base_plugin."data/temp_olsr_conf > ".$base_plugin."data/olsrd.conf_tmp;
          cat ".$base_plugin."data/olsrd.conf_tmp | tr '\t' ' ' > ".$base_plugin."data/olsrd.conf;
          rm ".$base_plugin."data/olsrd.conf_tmp;
          rm ".$base_plugin."data/temp_olsr_conf");
}


/* Copy files to System */
function set_olsr_conf($postData)
{
    global $base_plugin;

    exec("sudo cp ".$base_plugin."/data/olsrd.conf  /etc/olsrd.conf", $ret );

    if(!isset($postData['isMeshGw']))
    {
        exec("sudo route del defaut", $ret );
    }
}

if ($_POST['type']=="complex")
{
    $olsr_data=jsondecode($_POST['form_fields']);
    $fields_check_types = Array (
        'HelloInterval'  => Array ('ms_float','ms_mandatory'),
        'HelloValidityTime'  => Array ('ms_float','ms_mandatory'),
        'TcInterval'  => Array ('ms_float','ms_mandatory'),
        'TcValidityTime'  => Array ('ms_float','ms_mandatory'),
        'HnaInterval'  => Array ('ms_float','ms_mandatory'),
        'HnaValidityTime'  => Array ('ms_float','ms_mandatory')
        );
    if(are_form_fields_valid ($olsr_data, $fields_check_types))
    {
        exec("sudo remountrw");
        
        generate_olsrdConf($olsr_data);
        set_olsr_conf($olsr_data);

        response_additem("script", 'endnotify()');
        response_additem("script", 'notify("save", "Restart the machine to take effect.")');
        response_additem("script", 'fadenotify()');

        //save_olsrd($olsr_data);
        //exec('sudo cp '.$base_plugin.'data/new_olsd.conf /etc/olsrd/olsrd.conf');

        exec("sudo remountro");
    }
    //endSaveAlert();
    response_return();
}

?>
