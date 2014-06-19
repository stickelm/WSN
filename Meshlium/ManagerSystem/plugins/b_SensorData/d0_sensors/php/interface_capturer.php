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

function make_sensor_xml(){
  $list.='<div id="sensor_div_xml" style="overflow:auto;
                            height:600px;
                            font-family:monospace;
                            background-color:white;
                            -moz-border-radius:5px;
                            background-color:white;
                            border:1px solid #898989;
                            margin:5px;
                            padding:10px;">';
                            $list.=make_sensor_xml_content();
          $list.='</div>';
  return $list;
}

function make_sensor_xml_content(){
  $list.='<div style="float:left;margin-left:10px;"><h2>Standard sensors</h2>';
               
               $list.='<table style="font-family: Arial,Verdana,sans-serif;min-width:300px" border="1" cellspacing="0" cellpadding="2">
                  <tr bgcolor="#bbb">
                    <th>ID</th>
                    <th>ASCII ID</th>
                    <th>Fields</th>
                    <th>Type</th>
                  </tr>
                  <tr>';
               
                $user_sensors_xml=$_SERVER['DOCUMENT_ROOT'].'/ManagerSystem/plugins/b_SensorData/d0_sensors/data/sensors.xml';
                $my_sensors = simplexml_load_file($user_sensors_xml);
                foreach ($my_sensors as $sensor):
                    $id=$sensor->id;
                    $ascii_id=$sensor->string;
                    $fields=$sensor->fields;
                    $type=$sensor->type;
                    if($type == 0){
                      $type = 'uint_8';
                    }else if($type == 1){
                      $type = 'int';
                    }
                    else if($type == 2){
                      $type = 'float';
                    }
                    else if($type == 3){
                      $type = 'string';
                    }
                    else if($type == 4){
                      $type = 'ulong';
                    }
                    else if($type == 5){
                      $type = 'array(ulong)';
                    }

                    $list.= '<td>'.$id.'</td>';
                    $list.= '<td>'.$ascii_id.'</td>';
                    $list.= '<td>'.$fields.'</td>';
                    $list.= '<td>'.$type.'</td></tr>';
                endforeach;
                
              $list.='</table>
               </div>
               <div style="float:right;margin-right:10px"><h2>User sensors</h2>';
               
               $list.='<table style="font-family: Arial,Verdana,sans-serif;min-width:300px" border="1" cellspacing="0" cellpadding="2">
                  <tr bgcolor="#bbb">
                    <th style="background-color:#fff;"></th>
                    <th>ID</th>
                    <th>ASCII ID</th>
                    <th>Fields</th>
                    <th>Type</th>
                  </tr>
                  <tr>';
               
                $user_sensors_xml=$_SERVER['DOCUMENT_ROOT'].'/ManagerSystem/plugins/b_SensorData/d0_sensors/data/user_sensors.xml';

                $my_sensors = simplexml_load_file($user_sensors_xml);
                foreach ($my_sensors as $sensor):
                    $id=$sensor->id;
                    $ascii_id=$sensor->string;
                    $fields=$sensor->fields;
                    $type=$sensor->type;
                    if($type == 0){
                      $type = 'uint_8';
                    }else if($type == 1){
                      $type = 'int';
                    }
                    else if($type == 2){
                      $type = 'float';
                    }
                    else if($type == 3){
                      $type = 'string';
                    }
                    else if($type == 4){
                      $type = 'ulong';
                    }
                    else if($type == 5){
                      $type = 'array(ulong)';
                    }

                    $list.= '<td> <img class="delete_sensor_button" id="delete_'.$id.'" alt="delete" src="/ManagerSystem/plugins/b_SensorData/b0_capturer/images/delete_sensor.png"/> </td>';
                    $list.= '<td>'.$id.'</td>';
                    $list.= '<td>'.$ascii_id.'</td>';
                    $list.= '<td>'.$fields.'</td>';
                    $list.= '<td>'.$type.'</td></tr>';
                endforeach;

               $list.='</table>
               <div style="clear:both"></div>';
               return $list;
}

function make_add_sensor(){
  $list.='
      <div id="configure_new_sensor" style="border:1px solid #aaa;padding:5px;margin:10px 5px 10px 5px;background-color:#fff">
        <span style="text-weigth:bold">ASCII ID: <input type="text" class="ms_alnum ms_mandatory" id="new_sensor_id" maxlength="16" name="new_sensor_id"></span>
        <span style="text-weigth:bold">Fields: <input type="text" class="ms_alnum ms_mandatory" id="new_sensor_fields" maxlength="16" name="new_sensor_fields"></span>
        <span style="text-weigth:bold">
          Type: 
          <select id="new_sensor_type">
            <option value="0">uint_8</option>
            <option value="1">int</option>
            <option value="2">float</option>
            <option value="3">string</option>
            <option value="4">ulong</option>
            <option value="5">array(ulong)</option>
          </select>
        </span>
        <button id="add_sensor_button">Add sensor</button>
      </div><div style="margin:5px auto" id="message_log"></div>';
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
    $list.='<div class="title2">Aviable Sensors</div>';
    $list.='<div id="plugin_content">
            <button onclick="checkForUpdate(\''.$section.'\',\''.$plugin.'\')" >Update sensors</button>';
    $list.='<div style="width: 100%"; id="new_sensor"></div>';
    $list.=make_add_sensor();
    $list.='<div style="width: 100%;" id="resultsForUpdate" ></div>';
    $list.=make_sensor_xml();
    $list.='</div>';

    return $list;
}
?>
