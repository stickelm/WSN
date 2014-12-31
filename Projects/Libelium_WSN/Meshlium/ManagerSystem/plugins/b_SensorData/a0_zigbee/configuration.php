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

$xbee = exec("cat /mnt/lib/cfg/zigbee");

$type="PLUGIN"; // This is just for integrity checks.
$plugin_name="Xbee"; // THIS SHOULD BE A LINE.
$plugin_version="0.1"; // THIS SUOULD BE A LINE.
$plugin_author="Joaquin Ruiz;"; //THIS SHOULD BE A LINE.
$plugin_description="Xbee configuration"; // THIS SHOULD BE A SMALL DESCRIPTION
$plugin_main_file="main.php";  // BETER IF USED THE STANDARD main.php
$plugin_server_file="server.php"; // BETER IF USED THE STANDARD server.php
$plugin_icon="images/".$xbee.".png"; // BY DEFAULT
$plugin_icon_selected="images/".$xbee."_hv.png"; // BY DEFAULT
?>
