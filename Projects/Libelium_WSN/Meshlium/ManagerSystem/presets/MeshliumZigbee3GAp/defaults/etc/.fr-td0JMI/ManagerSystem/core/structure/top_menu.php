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
// Just close body and html tags.
$hostname=exec("hostname");
$core_top_menu='
<div style="display:inline;float:right; width: 130px; height:52px; border-left: 1px dashed #dedede; margin-left:10px; padding-left:10px;">
<form id="halt" name="halt" action="#" method="post">
    <input type="submit" name="restart" id="halt_restart" value="  Restart" \>
    <input type="submit" name="shutdown" id="halt_shutdown" value="  Shutdown" \>
</form>
</div>
<span style="display:inline;float:right;color: white;text-align:right;font-weight:bold; margin: 5px 5px 0 0;">'.$mesh_kind.'</span>
<div id="top_menu">
    <div id="application_banner" onclick="window.location=\'index.php\'"></div>
    <div id="top_navigation_menu_hostname">';
exec("cat /mnt/lib/cfg/currentPreset", $prst);

    //.$hostname.'
$core_top_menu.=$prst['0'].'
    </div>
    <div style="font-size:12px;font-weight:bold;height:55px;position:absolute;right:150px;text-align:right;top:7px;width:200px;"><span style="color:#fff;">'.$hostname.'</span></div>
    <div id="top_navigation_menu">        
        <a class="top_navigation_menu_item" href="index.php">Home</a>
        <a class="top_navigation_menu_item" href="index.php?logout=true"> | Logout</a>
    </div>
</div>
    <div id="presets">
        <form name="f_presets" action="#" method="get">
            <input type="submit" name="presets" id="s_presets" value="Presets" style="border: 0px; background: transparent; color: white; font-weight: bold; cursor: pointer;"  \>
        </form>
    </div>
';
?>
