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
 *  Author: Manuel Calvo
 */


// Those variables will help you to load css, javascript and some page information
//
// $_main_title This variable will load the page title.
$_main_title="Disk usage management";

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

function generate_background_percent($percent)
{
    if ($percent < '12')
        $background = '#08E308 ';

    if (('12' <= $percent)  && ($percent < '25'))
        $background = '#B0FF04 ';

    if (('25' <= $percent)  && ($percent < '37'))
        $background = '#DEFF00  ';

    if (('37' <= $percent)  && ($percent < '50'))
        $background = '#FBEB00  ';

    if (('50' <= $percent)  && ($percent < '62'))
        $background = '#FFC600 ';

    if (('62' <= $percent)  && ($percent < '84'))
        $background = '#FF9C00 ';

    if (('84' <= $percent)  && ($percent < '96'))
        $background = '#FF5200  ';

    if ($percent >= '96')
        $background = '#FF0000 ';


    return $background;
}

function getPercentBar($color, $value)
{
    $_ocupado = explode('%', trim($value));
    return "
    <div class='percent_bar' style='position: relative;-moz-border-radius: 4px; '>
        <div class='percent_progres small_font' style='-moz-border-radius: 4px; border: 1px solid #343434; border-top: 0px; border-left: 0px; position: absolute; z-index:9; background: ".$color.";  width:".$_ocupado['0'].";'>
        </div>
        <div style='color:#343434; z-index: 15;position:absolute;margin-left: 5px;margin-top: 2px;'>".round($_ocupado['0'], 2)." %</div>
        <div class='glass_bar' style='-moz-border-radius: 4px; left:".$_ocupado['0']."px; position: absolute;margin-left:-".$_ocupado['0']."; z-index:10;width: 100%;' ></div>
    </div> ";
}



        $html='<div class="title2">Disk usage</div>
                <div id="plugin_content">';

        $html.="<div>". date(DATE_RFC822)."</div>";
        $html.='<div class="information"><table class="main_table"><tbody>';

        exec('df -hTP',$disk_usage);

        $primero = true;
        foreach ($disk_usage as $disk)
        {
            $pattern = "{[ \t]+}";
            $replace = ' ';
            $disk = preg_replace($pattern,$replace, $disk);
            $_disk = explode(' ', $disk);

            if ($primero)
            {
                $html.='
                <tr>
                    <td>
                        <b>'.trim($_disk['0'])."</b>
                    </td><td>
                        <b>".trim($_disk['1'])."</b>
                    </td><td>
                        <b>".trim($_disk['2'])."</b>
                    </td><td>
                        <b>".trim($_disk['3'])."</b>
                    </td><td>
                        <b>".trim($_disk['4'])."</b>
                    </td><td>
                        <b>".trim($_disk['5'])."</b>
                    </td><td>
                        <b>".trim($_disk['6'])." ".trim($_disk['7']).'</b>
                    </td>
                </tr>';
            }
            else
            {

$background_color = generate_background_percent($_disk['5']);
                $html.='
                <tr>
                    <td>
                        <b>'.trim($_disk['0'])."</b>
                    </td><td>
                        ".trim($_disk['1'])."
                    </td><td>
                        ".trim($_disk['2'])."
                    </td><td>
                        ".trim($_disk['3'])."
                    </td><td>
                        ".trim($_disk['4'])."
                    </td><td class='limited'>
                        ".getPercentBar($background_color, trim($_disk['5']))."
                    </td><td>
                        ".trim($_disk['6'])." ".trim($_disk['7']).'
                    </td>
                </tr>';
                unset($barra_info);
            }
            $primero = false;
            unset($_disk);
        }

        $html.='</tbody></table></div></div></div>';

/*
// Scan partitions mounted on system:
exec('mount -l | grep sd',$mounted_partitions);
// Prepare each line to be displayed as html
foreach ($mounted_partitions as $partition)
{
    $partition_list=explode(' ',$partition);
    $partitions_html.='<pre>'.$partition_list[0]."\t".' mounted on '."\t".$partition_list[2].'</pre>';
}
$html2='<div class="title">Meshlium Sensors</div>
        <div id="plugin_content">
            <div id="output">
                <div class="information">'.$partitions_html.'</div>
                <input type="button" onclick="complex_ajax_call(\'output\',\''.$section.'\', \'show\',\''.$plugin.'\')" value="Display Disk Usage">
            </div>
        </div>
        ';
*/


// $html will be printed by core if $html is defined. But you can uncomment following
// lines if you know what you are doing.
// echo $html;
// unset($html);
?>