
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
 *  Author: Joaquin Ruiz  
 */
?>
<script type='text/javascript' src='/ManagerSystem/core/javascript/jquery-1.7.2.min.js'></script>
<script type='text/javascript'>
$(document).ready(function(){
 
$("img.a").hover(
function() {
$(this).stop().animate({"opacity": "0"}, "medium");
},
function() {
$(this).stop().animate({"opacity": "1"}, "medium");
});
 
});
</script>


<?php
function load_sections($container_folder)
{
    $sections = array();
    //$sections['base']=$container_folder;
    $ignore_dirs = array(".", "..");
    // container folder is the folder that will be processed.
    $files=scandir($container_folder);
    //echo "<pre>".print_r($files,true)."</pre>";
    $i=0;
    foreach ($files as $file)
    {
        // just looking for directories other than . and ..
        if ((is_dir($container_folder.'/'.$file))&&(!in_array($file,$ignore_dirs)))
        {
            // echo "$file lo es";
            //Check for a configuration file.
            if(file_exists($container_folder.'/'.$file.'/configuration.php'))
            {
                include $container_folder.'/'.$file.'/configuration.php';
                if ($type=="SELECTOR")
                {
                    $sections[$i]['name']=$section_name;
                    unset($section_name);
                    $sections[$i]['description']=$section_description;
                    unset($section_description);
                    $sections[$i]['icon']=$section_icon;
                    unset($section_icon);
                    $sections[$i]['icon_hv']=$section_icon_selected;
                    unset($section_icon);
                    $sections[$i]['folder']=$file;
                }
            }
            $i++;
        }
        else
        {
            //echo "$file no lo es";
        }
    }
    return $sections;
}

$core_sections_menu='
<div id="section_navbar">
    <div id="section_navbar_menu"  style="left:28px">
        <div id="section_navbar_slider" style="left:0px">
            <ul>';

$i='0';
foreach (load_sections($base_dir.'plugins') as $itm)
{
    if ($section!=$itm['folder'])
    {
        $core_sections_menu.='<li class="fadehover">
		<a href="index.php?section='.$itm['folder'].'">
		    <img id="section_navbar_slider_image_'.$i.'" src="plugins/'.$itm['folder'].'/'.$itm['icon'].'" alt="'.$itm['name'].'" title="'.$itm['description'].'" 
		    class="a" style="left:'.$i*(-114).'px"/>
		    <img id="section_navbar_slider_image_'.$i.'" src="plugins/'.$itm['folder'].'/'.$itm['icon_hv'].'" alt="'.$itm['name'].'" title="'.$itm['description'].'"
		    class="b" style="left:'.($i+1)*(-114).'px"/>
                </a>
            </li>';
    }
    else
    {
        // Highlight selected section.
        $core_sections_menu.='<li class="fadehover">
		<a href="index.php?section='.$itm['folder'].'">
		    <img id="section_navbar_slider_image_'.$i.'" src="plugins/'.$itm['folder'].'/'.$itm['icon_hv'].'" alt="'.$itm['name'].'" title="'.$itm['description'].'"
		    class="a" style="left:'.$i*(-114).'px"/>
		    <img id="section_navbar_slider_image_'.$i.'" src="plugins/'.$itm['folder'].'/'.$itm['icon_hv'].'" alt="'.$itm['name'].'" title="'.$itm['description'].'"
		    class="b" style="left:'.($i+1)*(-114).'px"/>
                </a>
            </li>';
    }
    $i++;
}
unset($i);
$core_sections_menu.='
    </ul></div></div>
    <div id="logo_libelium" onclick="window.location=\'http://www.libelium.com\'"></div>
</div>';

//echo "<pre>".print_r(load_sections($base_dir.'plugins'),true)."</pre>";
?>
