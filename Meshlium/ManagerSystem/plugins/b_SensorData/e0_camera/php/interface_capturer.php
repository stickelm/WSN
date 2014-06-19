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
ini_set('mysql.connect_timeout', 5);
error_reporting(0);

function make_frame_log(){
  $list.='<div id="frame_div" style="overflow:auto; 
            height:90px;
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
                            height:90px;
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
  <div id="daemonStatus" style="margin-bottom: 20px;">';
  if (exec("ps ax | grep sensorParser | grep -v grep | wc -l") == 0)
  {
    $list.= '<div id="dStopped"></div> <span> <b>Sensor Parser Not Available</b></span>';
  }
  else 
  {
    $list.= '<div id="dRunning"></div> <span> <b>Sensor Parser Available</b></span>';
  }
  $list.= '</div>
  <div style="clear: both;"></div>
  </div>';

  return $list;
}

function make_Foto()
{
  $list.='<span>Total Photos:</span><span id="total_fotos"></span><table id="data_table" style="float: right;"class="main_table"></table>
  <div id="slider_foto" style="overflow: scroll;-moz-border-radius:5px; background-color:white;
  border:1px solid #898989;height:500px;overflow:auto;margin:15px 0 0 0 ;padding:10px;width:685px;">

  </div>';
  return $list;    
}
function make_Video(){

  $list.='<span>Total Videos:</span> <span id="total_videos"></span><table id="data_table_videos" style="float: right;"class="main_table"></table>
  <div id="slider_video" style="overflow: scroll;-moz-border-radius:5px; background-color:white;
  border:1px solid #898989;height:500px;overflow:auto;margin:15px 0 0 0 ;padding:10px;width:685px;">

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
    $list.='<script type="text/javascript" src="/ManagerSystem/plugins/b_SensorData/e0_camera/javascript/jwplayer.js"></script>
    <div id="tab1" class="tab selectedTab" style="margin-left: 15px;" onclick="loadTab(\'tab1\')" >Photo</div>
    <div id="tab2" class="tab" onclick="loadTab(\'tab2\')">Video</div>
    <div style="clear: both;"></div>
    ';
    $list.='<div id="tab1content">';
        $list.=make_Foto();
    $list.='</div>';
    $list.='<div id="tab2content" style="display: none;">';
        $list.=make_Video();
    $list.='</div>';
    return $list;
}
?>