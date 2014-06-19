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
$_main_title="About Meshlium Manager System";

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



$html='
        <div class="title2">Upload Update</div>
        <div class="plugin_content">
Current version: '.$manager_system_version.'<br><br>


<form action="upload.php" method="post" enctype="multipart/form-data">
      <input type="text" name="returnsto" value="updates" style="display: none;" />
      <div style="float: left;">
            <input type="radio" name="uploadKind" value="local" id="localUploadKind" />
            <div class="divForLaber"><label for="file">Local file:</label></div>
            <div class="fileinputs">
                  <input id="file" name="file" type="file" class="file hidden"  onChange="$(\'#fileUpload\').show();$(\'#localUploadKind\').attr(\'checked\', true);$(\'#fakeinput\').val($(this).val())"/>
                  <div class="fakefile" style="" >
                        <input type="text" id="fakeinput" />
                        <div id="imageSelect"></div>
                  </div>
                  <div style="clear: both"></div>
            </div>
            <input id="fileUpload" type="submit" name="submit" value="Upload" style="display: none;margin-right: 5px;" onClick="$(\'#localUploadKind\').attr(\'checked\', true);" />
      </div>
      <div style="float: left;">
            <input type="radio" name="uploadKind" value="url" id="urlUploadKind" />
            <div class="divForLaber"><label for="file">Url:</label></div>
            <input type="text" name="urlFile" id="urlFile" style="width: 205px; margin-top: 1px;" onChange="$(\'#urlUploadKind\').attr(\'checked\', true);" />
            <input type="submit" name="submit" value="Download" style="margin-left: 5px; margin-right: 5px;" onClick=""$(\'#urlUploadKind\').attr(\'checked\', true);" />
      </div>
      <div style="clear: both"></div>
</form>';


   if ((exec("ls upload | wc -l") == 2) && file_exists('upload/log'))
   {
         $html .= "
         <div id='resultsOfLoad'>
            <br><hr style='border-width: 1px 0 0 0; border-style: dashed; border-color: #898989;'>";
            ob_start();
            readfile('upload/log');
            $image_data = ob_get_contents();
            ob_end_clean();

            //$html .= "File: ".exec("cat upload/log | tr '/' '\n' | grep '<br' | cut -d'<' -f1")."<br>";
            $html .= $image_data;

            if(exec("ls upload | grep libupd | wc -l"))
            {
                  $nameFile = exec("ls upload | grep libupd");
                  $html .= "<button onclick='installUpdate(\"".$section."\",\"".$plugin."\",\"".$nameFile."\")' >Install Update</button>";
            }
            elseif(exec("ls upload | grep libplg | wc -l"))
            {
                  $html .= "<b>Plugin package detected</b>";
            }
            $html .=
         "</div>";
   }
   elseif ((exec("ls upload | wc -l") == 1) && file_exists('upload/log'))
   {
      $html .= "<div id='resultsOfLoad'><br><hr style='border-width: 1px 0 0 0; border-style: dashed; border-color: #898989;'><b style='color:#DF4C44;'>Invalid file or file not found</b></div>";
   }

        $html .='</div>';

$html.='
<div class="title2">Check for Updates</div>
<div class="plugin_content">';
$html.="<button onclick='checkForUpdates(\"".$section."\",\"".$plugin."\")' >Check for updates</button>";
$html.="<div style=' width: 100%;' id='resultsForCheck' ></div>";
$html.='</div>';




// $html will be printed by core if $html is defined. But you can uncomment following
// lines if you know what you are doing.
// echo $html;
// unset($html);
?>