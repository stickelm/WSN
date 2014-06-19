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
          <div id="daemonStatus">';
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

function make_storedDataLocalDB()
{
    global $base_plugin, $section, $plugin;

    $DATABASE = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 1: | cut -d : -f2');
    $TABLE = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 2: | cut -d : -f2');
    $IP = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 3: | cut -d : -f2');
    $PORT = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 4: | cut -d : -f2');
    $USER = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 5: | cut -d : -f2');
    $PASS = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 6: | cut -d : -f2');

    $list .= '<div id="" class="DBConnection"><b>Connection data</b><hr />';
                $list .= '
                <table>
                    <tbody>
                        <tr>
                            <td style="text-indent: 0px;"><span><b>Database: </b></span></td>
                            <td style="text-indent: 0px;"><input type="text" readonly disabled value="'.$DATABASE.'" /></td>
                        </tr>
                        <tr>
                            <td style="text-indent: 0px;"><span><b>Table: </b></span></td>
                            <td style="text-indent: 0px;"><input type="text" readonly disabled value="'.$TABLE.'" /></td>
                        </tr>
                        <tr>
                            <td style="text-indent: 0px;"><span><b>IP: </b></span></td>
                            <td style="text-indent: 0px;"><input type="text" readonly disabled value="'.$IP.'" /></td>
                        </tr>
                        <tr>
                            <td style="text-indent: 0px;"><span><b>Port: </b></span></td>
                            <td style="text-indent: 0px;"><input type="text" readonly disabled value="'.$PORT.'" /></td>
                        </tr>
                        <tr>
                            <td style="text-indent: 0px;"><span><b>User: </b></span></td>
                            <td style="text-indent: 0px;"><input type="text" readonly disabled value="'.$USER.'" /></td>
                        </tr>
                        <tr>
                            <td style="text-indent: 0px;"><span><b>Password: </b></span></td>
                            <td style="text-indent: 0px;"><input type="text" readonly disabled value="'.$PASS.'" /></td>
                        </tr>
                    </tbody>
                </table>';
    $list .= '</div>

    <div style="float: left; margin-left: 10px;">
        <div style="width: 472px; -moz-border-radius: 5px;padding: 3px; background: white; border: 1px solid #898989; ">
            <div class="running" id="localDBRunning" ';
            if(!file_exists($base_plugin.'data/localDB'))
            {
                $list .= 'style="display: none;"';
            }
            $list .= '></div>
            <input type="checkbox" style="float: left;" id="makeLocalDB" ';
            if(file_exists($base_plugin.'data/localDB'))
            {
                $list .= ' checked ';
            }
            $list .= ' />
            <span style="float: left;margin-top: 3px;"><b>Store frames in the local data base</b></span>
            <button style="float: right;" onclick="useLocalDB(\''.$section.'\',\''.$plugin.'\',$(\'#makeLocalDB:checked\').val())">Save</button>
            <div style="clear: both;"></div>
        </div>
        <br>

        <button style="padding: 3px 3px;" onclick="showlocalDB(\''.$section.'\',\''.$plugin.'\', $(\'#localDbNumerToShow\').val());">Show data</button>
        <span style="margin-left: 10px;"> Last </span><input id="localDbNumerToShow" maxlength="10"  style="width: 50px;margin: 0;" type="text" value="100"><span> insertions.</span>
        <br><br><br><br><br><br><br><br><br><br>
       
    </div>

    <div style="clear: right;"></div>
    <div style="clear: both;"></div>
    <div style="overflow: scroll;-moz-border-radius:5px; background-color:white;
    border:1px solid #898989;height:300px;margin:15px 0 0 0;overflow:auto;padding:10px;width:97%;">
    <div id="localDataViewer" style="height:300px;width:830px;">';
    
    $DATABASE = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 1: | cut -d : -f2');
    $TABLE = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 2: | cut -d : -f2');
    $IP = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 3: | cut -d : -f2');
    $PORT = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 4: | cut -d : -f2');
    $USER = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 5: | cut -d : -f2');
    $PASS = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 6: | cut -d : -f2');

   // Conectar con el servidor de base de datos
      $conexion = mysql_connect ($IP, $USER, $PASS);
     

   // Seleccionar base de datos
      mysql_select_db ($DATABASE);
        

   // Enviar consulta

      $instruccion = "select * from ".$TABLE." order by id desc limit  20;";
      $consulta = mysql_query ($instruccion, $conexion);
      if (!$consulta) {
        $nfilas=0;
      }
      else
      {$nfilas = mysql_num_rows ($consulta);}

   // Mostrar resultados de la consulta
     
       $list .= '<table id="background-image">
               <thead>
                <tr>
                  <th scope="col">ID</th>
                  <th scope="col">Date</th>
                  <th scope="col">Sync</th>
                  <th scope="col">ID Wasp</th>
                  <th scope="col">ID Secret</th>
                  <th scope="col">Frame Type</th>
                  <th scope="col">Frame Number</th>
                  <th scope="col">Sensor</th>
                  <th scope="col">Value</th>     
                  
                </tr>
                 </thead><tbody>';
      if ($nfilas > 0)
      {
        for ($i=0; $i<$nfilas; $i++){
          $resultado = mysql_fetch_array ($consulta);
          if ($resultado['raw'] == NULL) {
            $list .= '            
              <tr>
                <td>'.$resultado['id'].'</td>
                <td>'.$resultado['timestamp'].'</td>
                <td>'.$resultado['sync'].'</td>
                <td>'.$resultado['id_wasp'].'</td>
                <td>'.$resultado['id_secret'].'</td>
                <td>'.$resultado['frame_type'].'</td>
                <td>'.$resultado['frame_number'].'</td>
                <td>'.$resultado['sensor'].'</td>   
                <td>'.$resultado['value'].'</td>         
                
              </tr>';
          }
          if ($resultado['raw'] != NULL){
             $list .= '           
              <tr>
                <td>'.$resultado['id'].'</td>
                <td>'.$resultado['timestamp'].'</td>
                <td>'.$resultado['sync'].'</td>
                <td colspan="8">'.$resultado['raw'].'</td>                
              </tr>';
          } 
        }
      } 
    
 $list .= '</tbody>
                </table>
    </div>
    </div>';
    mysql_close($conexion);
    return $list;
}


function make_storedDataExtDB()
{
    global $base_plugin, $section, $plugin;

    exec("cat /mnt/lib/cfg/sensorExternalDB | grep -n '' | grep 1: | cut -d: -f2", $extDatabase);
    exec("cat /mnt/lib/cfg/sensorExternalDB | grep -n '' | grep 2: | cut -d: -f2", $extTable);
    exec("cat /mnt/lib/cfg/sensorExternalDB | grep -n '' | grep 3: | cut -d: -f2", $extIP);
    exec("cat /mnt/lib/cfg/sensorExternalDB | grep -n '' | grep 4: | cut -d: -f2", $extPort);
    exec("cat /mnt/lib/cfg/sensorExternalDB | grep -n '' | grep 5: | cut -d: -f2", $extUser);
    exec("cat /mnt/lib/cfg/sensorExternalDB | grep -n '' | grep 6: | cut -d: -f2", $extPassword);
    exec("cat /mnt/lib/cfg/parser/interval", $interval);

    $list = '<div id="" class="DBConnection"><b>Connection data</b><hr />';
                $list .= '<form name="ExtConnection" id="ExtConnection">
                <table>
                    <tbody>
                        <tr>
                            <td style="text-indent: 0px;"><span><b>Database: </b></span></td>
                            <td style="text-indent: 0px;"><input type="text" class="ms_alnum ms_mandatory" id="ExtDatabase" maxlength="16" name="ExtDatabase"';
                            if(isset ($extDatabase['0']))
                            {
                                $list .= ' value="'.$extDatabase['0'].'" ';
                            }
                            $list .= '/></td>
                        </tr>
                        <tr>
                            <td style="text-indent: 0px;"><span><b>Table: </b></span></td>
                            <td style="text-indent: 0px;"><input type="text" class="ms_alnum ms_mandatory" id="ExtTable" maxlength="16" name="ExtTable"';
                            if(isset ($extTable['0']))
                            {
                                $list .= ' value="'.$extTable['0'].'" ';
                            }
                            $list .= ' /></td>
                        </tr>
                        <tr>
                            <td style="text-indent: 0px;"><span><b>IP: </b></span></td>
                            <td style="text-indent: 0px;"><input type="text" class="ms_mandatory ms_ip" id="ExtIP" maxlength="16" name="ExtIP"';
                            if(isset ($extIP['0']))
                            {
                                $list .= ' value="'.$extIP['0'].'" ';
                            }
                            $list .= ' /></td>
                        </tr>
                        <tr>
                            <td style="text-indent: 0px;"><span><b>Port: </b></span></td>
                            <td style="text-indent: 0px;"><input type="text" class="ms_mandatory ms_numerical" id="ExtPort" maxlength="16" name="ExtPort"';
                            if(isset ($extPort['0']))
                            {
                                $list .= ' value="'.$extPort['0'].'" ';
                            }
                            else
                            {
                                $list .= ' value="3306" ';
                            }
                            $list .= ' /></td>
                        </tr>
                        <tr>
                            <td style="text-indent: 0px;"><span><b>User: </b></span></td>
                            <td style="text-indent: 0px;"><input type="text" class="ms_alnum ms_mandatory" id="ExtUser" maxlength="16" name="ExtUser"';
                            if(isset ($extUser['0']))
                            {
                                $list .= ' value="'.$extUser['0'].'" ';
                            }
                            $list .= ' /></td>
                        </tr>
                        <tr>
                            <td style="text-indent: 0px;"><span><b>Password: </b></span></td>
                            <td style="text-indent: 0px;"><input type="text" class="ms_alnum ms_mandatory" id="ExtPassword" maxlength="16" name="ExtPassword"';
                            if(isset ($extPassword['0']))
                            {
                                $list .= ' value="'.$extPassword['0'].'" ';
                            }
                            $list .= ' /></td>
                        </tr>
                    </tbody>
                </table></form>                       
                ';
    $list .= '</div>

    <div style="float: left; margin-left: 10px;">
        <div style="width: 472px; -moz-border-radius: 5px;padding: 3px; background: white; border: 1px solid #898989; ">
            <div class="running" id="extDBRunning" ';
            if(!file_exists($base_plugin.'data/extDB'))
            {
                $list .= 'style="display: none;"';
            }
            $list .= '></div>
            <input type="checkbox" style="float: left;" id="makeExtDB" ';
            if(file_exists($base_plugin.'data/extDB'))
            {
                $list .= ' checked ';
            }
            $list .= ' />
            <span style="float: left;margin-top: 3px;"><b>Store frames in the external data base</b></span>
            <br><br>
            <span style="margin-left: 20px;"> Synchronize each </span>
            <input id="time_synchronize" maxlength="10"  style="width: 100px;margin: 0;" type="text" value="'. $interval[0].'">
            <span> seconds</span>
            <button style="float: right;padding: 3px 3px;" onclick="useExtDB(\''.$section.'\',\''.$plugin.'\',$(\'#makeExtDB:checked\').val(),$(\'#time_synchronize\').val())">Save</button>
            <div style="clear: both;"></div>
        </div>
        <br>

        <button style="padding: 3px 3px;" onclick="showextDB(\''.$section.'\',\''.$plugin.'\', \'ExtConnection\', $(\'#extDbNumerToShow\').val());">Show data</button>
        <span style="margin-left: 5px;"> Last </span><input id="extDbNumerToShow" maxlength="10"  style="width: 40px;margin: 0;" type="text" value="100"><span> insertions.</span>
        <button style="padding: 3px 3px;"onclick="showSqlScript(\''.$section.'\',\''.$plugin.'\');">Show sql script</button>
        <span style="color: #676767; font-size: 10px;padding-left:5px;vertical-align:text-bottom;">(to create database and table)</span>
        <div id="error_sync" style="height: 87px;overflow: auto;padding-top: 20px;padding-left: 20px;"></div>
        <button style="padding: 3px 3px;" onclick="saveDataConnection(\''.$section.'\',\''.$plugin.'\', \'ExtConnection\');">Save</button>
        <button style="padding: 3px 3px;" onclick="checkConnection(\''.$section.'\',\''.$plugin.'\', \'ExtConnection\');">Check Connection</button>
        <button style="padding: 3px 3px; float:right" onclick="synchronize(\''.$section.'\',\''.$plugin.'\', \'ExtConnection\');">Synchronize <b>Now</b></button>
        </div>

    <div style="clear: right;"></div>
    <div style="clear: both;"></div>    

    <div style="overflow: scroll;-moz-border-radius:5px; background-color:white;
    border:1px solid #898989;height:300px;margin:15px 0 0 0;overflow:auto;padding:10px;width:97%;">
    <div id="extDataViewer" style="height:300px;width:830px;">';

    exec("sudo remountrw");
     // Conectar con el servidor de base de datos
      $conexion = mysql_connect ($extIP[0].":".$extPort[0], $extUser[0], $extPassword[0]);
       

   // Seleccionar base de datos
      mysql_select_db ($extDatabase[0]);
        
   // Enviar consulta
      $instruccion = "select * from ".$extTable[0]." order by id desc limit 20;";
      $consulta = mysql_query ($instruccion, $conexion);
      if (!$consulta) {
        $nfilas=0;
      }
      else
      {$nfilas = mysql_num_rows ($consulta);}

    
      $list .='<table id="background-image">
               <thead>
                <tr>
                  <th scope="col">ID</th>
                  <th scope="col">Date</th>
                  <th scope="col">ID Wasp</th>
                  <th scope="col">ID Secret</th>
                  <th scope="col">Frame Type</th>
                  <th scope="col">Frame Number</th>
                  <th scope="col">Sensor</th>
                  <th scope="col">Value</th>                  
                </tr>
                 </thead><tbody>';
      if ($nfilas > 0)
      {
        for ($i=0; $i<$nfilas; $i++){
          $resultado = mysql_fetch_array ($consulta);
          if (($resultado['raw'] == NULL) || ($resultado['raw'] == 'null')){
            $list .='            
              <tr>
                <td>'.$resultado['id'].'</td>
                <td>'.$resultado['timestamp'].'</td>
                <td>'.$resultado['id_wasp'].'</td>
                <td>'.$resultado['id_secret'].'</td>
                <td>'.$resultado['frame_type'].'</td>
                <td>'.$resultado['frame_number'].'</td>
                <td>'.$resultado['sensor'].'</td>   
                <td>'.$resultado['value'].'</td>  
              </tr>';
          }
          if (($resultado['raw'] != NULL) && ($resultado['raw'] != 'null')){
            $list.= '            
              <tr>
                <td>'.$resultado['id'].'</td>
                <td>'.$resultado['timestamp'].'</td>
                <td colspan="8">'.$resultado['raw'].'</td>                
              </tr>';
          } 
        }
      } 
    $list.='</tbody></table></div></div>';
    mysql_close($conexion);
    return $list;
}
function make_storedDataNow(){
global $base_plugin, $section, $plugin;
  $list.='<button id="showMeNowStart" style="float: left;padding: 3px 3px;"  onclick="showMeNow(\''.$section.'\',\''.$plugin.'\',$(\'#nonStop\').attr(\'checked\'), $(\'#intervalForNow\').val());">Start Scan</button>
          <button id="showMeNowStop" onclick="stopMeNow(\''.$section.'\',\''.$plugin.'\');" style="display: none;float: left;padding: 3px 3px;" >Stop Scan</button>
          <input id="intervalForNow" maxlength="10"  style="width: 40px;margin-left: 11px;" type="text" value="10"/>
          <input style="float: left;" name="nonStop" id="nonStop" type="checkbox" value="off" />
          <span style="float: left;padding-top:3px;" >Use the defined Scan interval</span>
          <span> Seconds </span>
          <br><br> 
          <button id="clean" onclick="$(\'#tab3contentScan\').html(\'\');" style="float: right;padding: 3px 3px;" >Clean</button>
          <div style="clear: both;"></div>
          <br>
          <div style="overflow: scroll;-moz-border-radius:5px; background-color:white;
                      border:1px solid #898989;height:500px;margin:15px 0 0 0;overflow:auto;padding:10px;width:685px;">
          <div id="tab3contentScan">      
          </div>
          </div>';

  return $list;
}
function make_advanced(){
global $base_plugin, $section, $plugin;
    
      
    $list.='
    <span"><b>Local Database</b><span>
    <div style="border:1px solid #aaa;padding:5px;margin:10px 5px 10px 5px;background-color:#fff">
          <span>Database:</span> <span style="padding-left: 100px;"><b id="local_db"></b></span><br>
          <span>Database Size:</span><span style="padding-left: 76px;"><b id="local_sz"></b></span><span> Mb</span> <br>
          <span>Table:</span><span style="padding-left: 126px;"><b id="local_table"></b></span><br>
          <span>Entries:</span><span style="padding-left: 118px;"><b id="local_rw"></b></span><br> 
          <span>Syncronized Frames:</span><span style="padding-left: 44px;"><b id="local_syc"></b></span><br> 
          <span>Unsyncronized Frames:</span><span style="padding-left: 30px;"><b id="local_unsyc"></b></span><br> 
          <button id="clearDatabase" onclick="clearALL(\''.$section.'\',\''.$plugin.'\');" style="padding: 3px 3px;margin-left: 330px;" ><b>Remove synchronized</b> Data</button>  
          <button id="removeDatabase" onclick="removeALL(\''.$section.'\',\''.$plugin.'\');" style="padding: 3px 3px;float:right;" >Remove <b>ALL </b>Content</button>
          
    </div>
    <br><br>
    <span><b>External Database</b><span>
    <div id="a" style="border:1px solid #aaa;padding:5px;margin:10px 5px 10px 5px;background-color:#fff">
     <span>Database:</span> <span style="padding-left: 100px;"><b id="ext_db"></b></span><br>
          <span>Database Size:</span><span style="padding-left: 76px;"><b id="ext_sz"></b></span><span> Mb</span> <br>
          <span>Table:</span><span style="padding-left: 126px;"><b id="ext_table"></b></span><br>
          <span>Entries:</span><span style="padding-left: 118px;"><b id="ext_rw"></b></span>   
      <br><br>
      <span><b>Logs Sync</b><span>     
      <div id="sync_dev" style="margin-top:20px;padding-left:20px">
      </div>
    </div>
         
         ';

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
    $list.='<div class="title2">Captured Data</div><br>';
    $list.='
    <div id="tab1" class="tab selectedTab" style="margin-left: 15px;" onclick="loadTab(\'tab1\')" >Local DataBase</div>
    <div id="tab2" class="tab" onclick="loadTab(\'tab2\')">External Database</div>
    <div id="tab3" class="tab" onclick="loadTab(\'tab3\')">Show me<b> NOW</b></div>
    <div id="tab4" class="tab" onclick="loadTab(\'tab4\')">Advanced</div>
    <div style="clear: both;"></div>
    ';
    $list.='<div id="tab1content">';
        $list.=make_storedDataLocalDB();
    $list.='</div>';
    $list.='<div id="tab2content" style="display: none;">';
        $list.=make_storedDataExtDB();
    $list.='</div>';
    $list.='<div id="tab3content" style="display: none;">';
    $list.=make_storedDataNow();
    $list.='</div>';
    $list.='<div id="tab4content" style="display: none;">';
    $list.=make_advanced();
    $list.='</div>';
    return $list;
}
?>