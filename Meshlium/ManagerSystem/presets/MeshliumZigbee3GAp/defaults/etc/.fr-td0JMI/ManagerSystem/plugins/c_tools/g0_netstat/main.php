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


// Those variables will help you to load css, javascript and some page information
//
// $_main_title This variable will load the page title.
$_main_title="Netstat plugin";

// You can define an array with the css files you want to load. The css must be
// on the plugin css folder.
// $_plugin_css=Array('plugin_1.css','plugin_2.css');
// Will load files
// plugins/section_name/plugin_name/css/plugin1.css
// plugins/section_name/plugin_name/css/plugin1.css
$_plugin_css=Array("basic.css");

// You can define an array with the javascript files you want to load.
// javascript files must be under the plugin javascript folder
// $_plugin_javascript=Array('plugin_1.js','plugin_2.js');
// Will load files
// plugins/section_name/plugin_name/javascript/plugin1.js
// plugins/section_name/plugin_name/javascript/plugin1.js
$_plugin_javascript=Array("jquery-1.3.2.min.js","ajax.js");

// Predefined variables:
// $section contains the section folder name.
// $plugin contains the plugin folder name.
// $section and $plugin can be used to make a link to this plugin by just reference
// $html="<a href=\"index.php?section=$section&plugin=$plugin\">This plugin</a>";
// $base_plugin contains the path that must be used as start to includes for
// plugin includes that need the local path.
// example: include_once $base_plugin.'php/my_include.php';
// $url_plugin contains the url base that must be used to include html items
// such as images.
// example: <img src="'.url_plugin.'images/my_image.png">
// $API_core contains the path to the core API folder.
// example: include_once $API_core.'is_active.php';

// Plugin produced data will be output between a <div> structure.
// <div>
//      Plugin output will be here.
// </div>

// Once plugin is finished core will check $html variable and output its content if any is stored.
// Is better to use $html variable to avoid direct call of the plugin from browsers.



function convert_to_table($data,$id,$th='')
{
    // Given an array it will convert the array to a table using each component as row
    // and space or tabs as column separator.
    $pattern_text = '/[\t]+/';
    $return='<table id="'.$id.'"><tbody>';
    if(!empty($th)){
        $return.='<tr>'.$th.'</th>';
    }
    if(!empty($data)){
        foreach ($data as $line)
        {
            // Remove \n an not desired separators.
            $line=trim($line);
            $return.='<tr><td>'.preg_replace($pattern_text,'</td><td>',$line).'</td></tr>';
        }
    }
    $return.='</tbody></table>';
    return $return;
}


        $html='<div class="title2">Netstat</div>
                <div id="plugin_content">';

        $html.="<div>Active Internet connections (servers and established) at: ". date(DATE_RFC822)."</div>";
        $html.='<div class="information">';

        exec('netstat -lantp',$netstat);
        //echo "<pre>".print_r($netstat,true)."</pre>";
        $primero = true;
        $i=0;
        foreach ($netstat as $line)
        {
            if($i<2){
                // This is table headers, and should be modified in a different way                
                $i++;
            } else{
                $pattern = "{[ \t]+}";
                $replace = "\t";
                $line = preg_replace($pattern,$replace, $line);
                $data[]=$line;
            }
        }
        $th='<th>Proto</th><th>Recv-Q</th><th>Send-Q</th><th>Local Address</th><th>Foreign Address</th><th>State</th><th>PID/Program name</th>';
        //echo "<pre>".print_r($th,true)."</pre>";
        //echo "<pre>".print_r($data,true)."</pre>";
        $html.= convert_to_table($data,'netstat',$th);
        $html.='</div></div></div>';

?>