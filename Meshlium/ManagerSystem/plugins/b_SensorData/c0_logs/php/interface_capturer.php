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
 *  Version 2.0
 *  Author: Alvaro Gonzalez
 */
function make_frame_log(){
  $list.='<div id="frame_div" style="overflow:auto; 
            height:390px;
            font-family:monospace;
            background-color:white;
            -moz-border-radius:5px;
            background-color:white;
            border:1px solid #898989;
            margin:5px;
            padding:10px;">';
              
             
               $list.='</div>';
  return $list;
}

function make_sensor_log(){
  $list.='<div id="sensor_div" style="overflow:auto; 
                            height:390px;
                            font-family:monospace;
                            background-color:white;
                            -moz-border-radius:5px;
                            background-color:white;
                            border:1px solid #898989;
                            margin:5px;
                            padding:10px;">';
             
             
               $list.='</div>';
  return $list;
}

function make_state_daemon()
{
    global $base_plugin, $section, $plugin;

    $list.='
        <div>
          <div id="daemonStatus">';
          if (exec("ps ax | grep sensorParser | grep -v grep | wc -l") == 1)
          {
             $list.= '<div id="dRunning"></div> <span> <b>Sensor Parser Available</b></span>';
          }
          elseif (exec("ps ax | grep sensorParser | grep -v grep | wc -l") == 0)
          {
             $list.= '<div id="dStopped"></div> <span> <b>Sensor Parser Not Available</b></span>';
          }
          else
          {
             $list.= '<b>Problem</b> - <b>killall</b>';
          }
    $list.= '</div>
        <div style="clear: both;"></div>
        </div>';

    return $list;
}




function make_interface()
{
    global $url_plugin;
    global $base_plugin;
    global $section;
    global $plugin;
    global $init_sensor_bt;
    $list = "";
    $list.=make_state_daemon();
    
    $list.='<div class="title2">Sensor Log</div>';
    $list.='<div id="plugin_content">';
    $list.=make_sensor_log();
    $list.='</div>';

   
    $list.='<div class="title2">Frame Log</div>';
    $list.='<div id="plugin_content" ">';
    $list.=make_frame_log();
    $list.='</div>';
    
    return $list;
}
?>