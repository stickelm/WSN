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
 *                                                        )[            ....   
                                                       -$wj[        _swmQQWC   
                                                        -4Qm    ._wmQWWWW!'    
                                                         -QWL_swmQQWBVY"~.____ 
                                                         _dQQWTY+vsawwwgmmQWV! 
                                        1isas,       _mgmQQQQQmmQWWQQWVY!"-    
                                       .s,. -?ha     -9WDWU?9Qz~- -- -         
                                       -""?Ya,."h,   <!`_mT!2-?5a,             
                                       -Swa. Yg.-Q,  ~ ^`  /`   "$a.           
     aac  <aa, aa/                aac  _a,-4c ]k +m               "1           
    .QWk  ]VV( QQf   .      .     QQk  )YT`-C.-? -Y  .                         
    .QWk       WQmymmgc  <wgmggc. QQk       wgz  = gygmgwagmmgc                
    .QWk  jQQ[ WQQQQQQW;jWQQ  QQL QQk  ]WQ[ dQk  ) QF~"WWW(~)QQ[               
    .QWk  jQQ[ QQQ  QQQ(mWQ9VVVVT QQk  ]WQ[ mQk  = Q;  jWW  :QQ[               
     WWm,,jQQ[ QQQQQWQW')WWa,_aa. $Qm,,]WQ[ dQm,sj Q(  jQW  :QW[               
     -TTT(]YT' TTTYUH?^  ~TTB8T!` -TYT[)YT( -?9WTT T'  ]TY  -TY(               
                     
                          www.libelium.com

*  Libelium Comunicaciones Distribuidas SL
*  Autor: Joaquín Ruiz
*
*/
ini_set('mysql.connect_timeout', 5);
error_reporting(0);


function make_state_daemon()
{
  global $base_plugin, $section, $plugin;

  $list.='<div id="topesri"></div>';
  return $list;
}

function make_enable()
{
  global $base_plugin, $section, $plugin;

  if (file_exists('plugins/'.$section.'/'.$plugin.'/data/security'))
  {
    $color="green";
    $pos1 = "security_layer_on.png";
    $endis = "enabled";
  }
  else
  {
    $color="red";
    $pos1 = "security_layer_off.png";
    $endis = "disabled";
  }

  $list.='
    <div id="butensec" style="background-image:url(\'plugins/'.$section.'/'.$plugin.'/css/'.$pos1.'\');margin-left:14px" 
    onclick="enableSec(\'plugins/'.$section.'/'.$plugin.'/css/\',\''.$section.'\',\''.$plugin.'\')"></div>';
  return $list;
}

function make_info()
{
    global $base_plugin, $section, $plugin;

  $ip = $_SERVER['HTTP_HOST'];
  $list.='
  <div id="plugin_content_info"><h2>REST Service Info</h2><br />
  
  <table style="width:80%;"><tr><td><b>Meshlium IP<b></td><td> '.$ip.'</td></tr>
  <tr><td colspan=2><hr></td></tr>
  <tr><td><img src="plugins/c_cloudPlatforms/a0_map/images/resources.png" style="height:20px"></td><td>
  <input style="width:200px" type="button" onclick="window.open(\'http://'.$ip.'/meshlium/rest/services/Libelium\',\'REST Services Directory\',\' width=800,height=800\')" value="Explore REST Services Directory"></td></tr>
  <tr><td><img src="plugins/c_cloudPlatforms/a0_map/images/arcgis.png" style="height:20px"></td><td>
  <input style="width:200px" type="button" onclick="window.open(\'http://www.arcgis.com/home/webmap/viewer.html?url=http://'.$ip.'/meshlium/rest/services/Libelium/FeatureServer\',\'ArcGIS.com\',\' width=800,height=800\')" value="View in ArcGIS.com"></td>
  </tr></table>
  <a href="http://esri.es/es/" target="_blank">
    <img style="margin-top:-120px;margin-left:590px" src="plugins/c_cloudPlatforms/a0_map/images/powesri.png"/>
  </a>
  </div>';

  return $list;
}

function make_token()
{
  global $base_plugin, $section, $plugin;

  $list.='
  <div id="plugin_content" style="background-color:rgb(240, 232, 232)"><h2>Token Request</h2><br />
    <table id="rounded-corner">
     <tbody>
    <tr><td><label>User</label></td><td><input style="width: 178px;" type="text" name="meshRUser" id="meshRUser" value=""></td></tr>
    <tr><td><label>Password</label></td><td><input style="width: 178px;" type="password" name="meshRPassw" id="meshRPassw" value=""></td></tr>
    </tbody></table>
    <input type="button" value="Send Request!" onClick="request()" style="float:right; margin-right: 10px">
    <br/><br/><b>Received Token: </b><br/><textarea name="token" cols="80" rows="3" id="token" font-size="11px" style="margin-left:0%;background-color: #DDDDDD;" disabled readonly></textarea>
    ';
  return $list;
}

function make_security()
{
  global $base_plugin, $section, $plugin;

  $IP = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 3: | cut -d : -f2');
  $PORT = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 4: | cut -d : -f2');
  $USER = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 5: | cut -d : -f2');
  $PASS = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 6: | cut -d : -f2');
  $conexion = mysql_connect ($IP, $USER, $PASS);
  mysql_select_db ("MeshliumDB");
  $instruccion = "SELECT * FROM users;";

  $list.='<div id="plugin_content" style="background-color:rgb(240, 232, 232)"><h2>Users List</h2>
  <input id="addNUser" type="button" value="Add New" onClick="toggleU();" style="float:right; margin-right: 10px;margin-top:-20px;background-color:lightgreen" >

  <div id="userESRINEW" style="display:none;position:fixed;width:100%;height:100%;top:0px;left:0px;z-index:99999;background-color: rgba(0, 0, 0, 0.29);">
  <div class="dentro" style="top:30%;left:37%;z-index:99999;position:fixed"><br/>
  <div style="background-color:rgb(221, 255, 221); border: 2px dotted green; padding:8px; width:450px">
    <form name="fusernew" id="fusernew">
    <table id="rounded-corner2">
    <tbody>
    <tr><td><label>User</label></td><td><input style="width: 178px;" type="text" name="meshUser" id="meshUser" value=""></td></tr>
    <tr><td><label>Password</label></td><td><input style="width: 178px;" type="password" name="meshPassw" id="meshPassw" value=""></td></tr>
    </tbody></table>
    </form>
      <input id="buttonCloseUX" type="button" value="Cancel" style="float:right; margin-right: 10px">
      <input type="button" value="Add" onClick="addUser(\''.$section.'\',\''.$plugin.'\',\'fusernew\')" style="float:right; margin-right: 10px">
    <br /><br /></div></div></div>';
  $consulta = mysql_query ($instruccion, $conexion);
  $i = 0;
  while ($resultado = mysql_fetch_array($consulta, MYSQL_NUM)) {
    $list.='
    <h4>User - '.$resultado[1].'</h4><input type="button" id="userESRIb'.$i.'" value="Show" onClick="toggle4U(\''.$i.'\')" style="float:right; margin-right: 10px;margin-top:-31px">
    <div id="userESRI'.$i.'" style="padding:8px;display:none;width:450px;background-repeat:no-repeat;border:1px solid">
    <form name="fuser'.$i.'" id="fuser'.$i.'">
    <table id="rounded-corner">
     <tbody>
    <tr><td><label>User (*)</label></td><td><input style="width: 178px;" type="text" name="meshUser" id="meshUser" value="'.$resultado[1].'"></td></tr>
    <tr><td><label>Old Password (*)</label></td><td><input style="width: 178px;" type="password" name="meshPasswO" id="meshPasswO" value=""></td></tr>
    <tr><td><label>New Password</label></td><td><input style="width: 178px;" type="password" name="meshPasswN" id="meshPasswN" value=""></td></tr>
    </tbody></table>
    <input type="button" value="Save" onClick="saveUser(\''.$section.'\',\''.$plugin.'\',\'fuser'.$i.'\',\''.$resultado[0].'\')" style="float:right; margin-right: 10px">
    <input type="button" value="Delete" onClick="toggleU2('.$i.')" style="float:right; background-color:rgb(255, 121, 121); margin-right: 20px"><br /><br />
    </form>
    <div id="sureU'.$i.'" style="display: none; border: 2px dotted red; padding:8px;">
      <b> Are you sure you want to remove this User? Make sure you type the Old Password</b> <br/>
      <input type="button" value="DELETE!" onClick="delUser(\''.$section.'\',\''.$plugin.'\',\'fuser'.$i.'\',\''.$resultado[0].'\')" style="background-color:rgb(255, 121, 121); margin-left: 10px">
      <input type="button" value="Cancel" onClick="toggleU2('.$i.')" style=" margin-left: 20px">
      <br />
    </div>
    </div>';
    $i++;
  }
  $list.='</div>';
  return $list;
}

function make_map()
{
    global $base_plugin, $section, $plugin;

  $IP = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 3: | cut -d : -f2');
  $PORT = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 4: | cut -d : -f2');
  $USER = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 5: | cut -d : -f2');
  $PASS = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 6: | cut -d : -f2');

  $ip = $_SERVER['HTTP_HOST'];
  $list.='
    <link rel="stylesheet" href="http://serverapi.arcgisonline.com/jsapi/arcgis/3.3/js/esri/css/esri.css">  
  <div id="plugin_content" ><h2>Devices Location</h2><br />
  
  <div id="container">
    <div id="map-container">
    <div id="map-target"></div>
    <div id="controls">
      <ul>
        <li id="topo">Topographic</li>
        <li id="streets">StreetMap</li>
        <li id="hybrid" class="selected">Images</li>
      </ul>
      <div id="search"></div>
      <div class="clear"></div>
    </div>
    <div id="map">

    </div>
    </div>
    <script>var dojoConfig = { parseOnLoad: true, locale:"en" };</script>
    <script src="http://serverapi.arcgisonline.com/jsapi/arcgis/3.4/"></script>
    <script>
      var config = 
      {
        center: [1.293911, 103.781054],
        zoom: 12,
        meshliumUrl: \'http://'.$ip.'/meshlium/rest/services/Libelium/FeatureServer\'
      }
    </script>
  <form name="fposition" id="fposition">
  <table style="border:#AAAAAA 1px solid; width:100%;background-color:#A9D0FF">
  <tr><td><b>Center the map where the device is :</b></td>
  <td><input type="text" name="visorx" id="visorx" style="width:70px; background-color:lightgray">
  <input type="text" name="visory" id="visory" style="width:70px; background-color:lightgray"></td></tr>
  <tr><td><b>Select the device:</b></td>
  <td><select name="topositionate">
  <option value="meshlium">Meshlium</option>';

  $conexion = mysql_connect ($IP, $USER, $PASS);
  mysql_select_db ("MeshliumDB");
  $instruccion = "SELECT * FROM waspmote;";
  $consulta = mysql_query ($instruccion, $conexion);
  $i = 0;
  while ($resultado = mysql_fetch_array($consulta, MYSQL_NUM)) {
    $list.='<option value="'.$resultado[1].'">Waspmote-'.$resultado[1].'</option>';
  }
$list.='
    </select></td></tr></table></form>
    <input type="button" value="Set position" onClick="setPos(\''.$section.'\',\''.$plugin.'\',\'fposition\')" style="float:right;margin-right:9px;margin-top:-27px">
  </div>
  </div>';


  return $list;
}

function make_config()
{
    global $base_plugin, $section, $plugin;

  $IP = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 3: | cut -d : -f2');
  $PORT = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 4: | cut -d : -f2');
  $USER = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 5: | cut -d : -f2');
  $PASS = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 6: | cut -d : -f2');

  $conexion = mysql_connect ($IP, $USER, $PASS);
  mysql_select_db ("MeshliumDB");
  $instruccion = "SELECT * FROM meshlium LIMIT 1;";
  $consulta = mysql_query ($instruccion, $conexion);
  $resultado = mysql_fetch_array ($consulta);

  $list.='<div id="plugin_content_mesh" ><h2>Meshlium Info</h2>
  <h4>Meshlium - '.$resultado["name"].'</h4><button id="meshliumESRIb" style="float:right; margin-right: 10px;margin-top:-31px">Show</button>
  <div id="meshliumESRI" style="padding:8px; width:450px;background-image:url(\'plugins/'.$section.'/'.$plugin.'/css/mesh.png\');border:1px solid;display:none">
  <form name="fmeshlium" id="fmeshlium">
  <table id="rounded-corner">
   <tbody>
  <tr><td><label>Name</label></td><td><input style="width: 178px;" type="text" name="meshName" id="meshName" value="'.$resultado["name"].'"></td></tr>
  <tr><td><label>Description</label></td><td><textarea name="meshDesc" cols="20" rows="5" id="meshDesc" font-size="11px" >'.$resultado["description"].'</textarea></td></tr>
  <tr><td><label>Latitude</label></td><td><input style="background-color: #DDDDDD;" disabled readonly style="width: 178px;" type="text" name="meshX" id="meshX" value="'.$resultado["x"].'"></td></tr>
  <tr><td><label>Longitude</label></td><td><input style="background-color: #DDDDDD;" disabled readonly style="width: 178px;" type="text" name="meshY" id="meshY" value="'.$resultado["y"].'"></td></tr>
  <tr><td><label>Spatial Reference</label></td><td><input style="background-color: #DDDDDD;" disabled readonly style="width: 178px;" type="text" name="meshSR" id="meshSR" value="'.$resultado["spatialReference"].'"></td></tr>
  </tbody></table>
  <input type="button" value="Save" onClick="saveMesh(\''.$section.'\',\''.$plugin.'\',\'fmeshlium\')" style="float:right; margin-right: 10px">
  </form><br /><br />
  </div></div>';

  return $list;
}

function make_configW()
{
    global $base_plugin, $section, $plugin;

  $IP = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 3: | cut -d : -f2');
  $PORT = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 4: | cut -d : -f2');
  $USER = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 5: | cut -d : -f2');
  $PASS = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 6: | cut -d : -f2');
  $conexion = mysql_connect ($IP, $USER, $PASS);
  mysql_select_db ("MeshliumDB");
  $instruccion = "SELECT * FROM waspmote;";

  $list.='<div id="plugin_content_wasp" ><h2>Waspmote List</h2>
  <input id="addNWasp" type="button" value="Add new" onClick="toggle();" style="float:right; margin-right: 10px;margin-top:-20px;background-color:lightgreen" >

  <div id="waspmoteESRINEW" style="display:none;position:fixed;width:100%;height:100%;top:0px;left:0px;z-index:99999;background-color: rgba(0, 0, 0, 0.29);">
  <div class="dentro" style="top:30%;left:37%;z-index:99999;position:fixed"><br/>
  <div style="background-image:url(\'plugins/'.$section.'/'.$plugin.'/css/psense.png\');background-color:rgb(221, 255, 221); border: 2px dotted green; padding:8px; width:450px">
    <form name="fwaspmotenew" id="fwaspmotenew">
    <table id="rounded-corner2">
    <tbody>
    <tr><td><label>Name</label></td><td><input style="width: 178px;" type="text" name="meshName" id="meshName" value=""></td></tr>
    <tr><td><label>Description</label></td><td><textarea name="meshDesc" cols="20" rows="5" id="meshDesc" font-size="11px" ></textarea></td></tr>
    <tr><td><label>Sensor Count</label></td><td><input type="text" name="sensorCount" id="sensorCount" value="1"></td></tr>
    <tr><td><label>Latitude</label></td><td><input style="background-color: #DDDDDD;" disabled readonly style="width: 178px;" type="text" name="meshX" id="meshX" value="0.0000"></td></tr>
    <tr><td><label>Longitude</label></td><td><input style="background-color: #DDDDDD;" disabled readonly style="width: 178px;" type="text" name="meshY" id="meshY" value="0.0000"></td></tr>
    <tr><td><label>Spatial Reference</label></td><td><input style="background-color: #DDDDDD;" disabled readonly style="width: 178px;" type="text" name="meshSR" id="meshSR" value="4326"></td></tr>
    </tbody></table>
    </form>
    <input id="buttonCloseX" type="button" value="Cancel" style="float:right; margin-right: 10px">
    <input type="button" value="Add" onClick="addWasp(\''.$section.'\',\''.$plugin.'\',\'fwaspmotenew\')" style="float:right; margin-right: 10px">
    <br /><br /></div><br /></div></div>';
  $consulta = mysql_query ($instruccion, $conexion);
  $i = 0;
  while ($resultado = mysql_fetch_array($consulta, MYSQL_NUM)) {
    $list.='
    <h4>Waspmote - '.$resultado[1].'</h4><input type="button" id="waspmoteESRIb'.$i.'" value="Show" onClick="toggle4(\''.$i.'\')" style="float:right; margin-right: 10px;margin-top:-31px">
    <div id="waspmoteESRI'.$i.'" style="padding:8px;display:none;width:450px;background-image:url(\'plugins/'.$section.'/'.$plugin.'/css/psense.png\');
    background-repeat:no-repeat;border:1px solid">
    <form name="fwaspmote'.$i.'" id="fwaspmote'.$i.'">
    <table id="rounded-corner">
     <tbody>
    <tr><td><label>Name</label></td><td><input style="width: 178px;" type="text" name="meshName" id="meshName" value="'.$resultado[1].'"></td></tr>
    <tr><td><label>Description</label></td><td><textarea name="meshDesc" cols="20" rows="5" id="meshDesc" font-size="11px" >'.$resultado[2].'</textarea></td></tr>
    <tr><td><label>Sensor Count</label></td><td><input type="text" name="sensorCount" id="sensorCount" value="'.$resultado[7].'"></td></tr>
    <tr><td><label>Latitude</label></td><td><input style="background-color: #DDDDDD;" disabled readonly style="width: 178px;" type="text" name="meshX" id="meshX" value="'.$resultado[3].'"></td></tr>
    <tr><td><label>Longitude</label></td><td><input style="background-color: #DDDDDD;" disabled readonly style="width: 178px;" type="text" name="meshY" id="meshY" value="'.$resultado[4].'"></td></tr>
    <tr><td><label>Spatial Reference</label></td><td><input style="background-color: #DDDDDD;" disabled readonly style="width: 178px;" type="text" name="meshSR" id="meshSR" value="'.$resultado[5].'"></td></tr>
    </tbody></table>
    <input type="button" value="Save" onClick="saveWasp(\''.$section.'\',\''.$plugin.'\',\'fwaspmote'.$i.'\',\''.$resultado[0].'\')" style="float:right; margin-right: 10px">
    <input type="button" value="Delete" onClick="toggle2('.$i.')" style="float:right; background-color:rgb(255, 121, 121); margin-right: 20px"><br /><br />
    </form>
    <div id="sure'.$i.'" style="display: none; border: 2px dotted red; padding:8px;">
      <b> Are you sure you want to remove this Waspmote? </b> <br/>
      <input type="button" value="DELETE!" onClick="delWasp(\''.$section.'\',\''.$plugin.'\',\'fwaspmote'.$i.'\',\''.$resultado[0].'\')" style="background-color:rgb(255, 121, 121); margin-left: 10px">
      <input type="button" value="Cancel" onClick="toggle2('.$i.')" style=" margin-left: 20px">
      <br />
    </div>
    </div>';
    $i++;
  }
  $list.='</div>';
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
    $list.='
      <div id="tab1" class="tab selectedTab" style="margin-left: 15px;" onclick="loadTab(\'tab1\')" >Configuration</div>
      <div id="tab2" class="tab" onclick="loadTab(\'tab2\')">Security</div>';
    $list.='
    <div style="clear: both;"></div>
    ';
    $list.='<div id="tab1content">';
    $list.=make_info();
    $list.=make_config();
    $list.=make_configW();
    $list.=make_map();
    $list.='</div>';
    $list.='<div id="tab2content" style="display: none;">';
    $list.=make_enable();
    $list.='<div id="tab22content" ';
      if (!file_exists('plugins/'.$section.'/'.$plugin.'/data/security'))
        $list.='style="display: none;"';
      else $list.='style="display: block;"';
    $list.='>';
    $list.=make_security();
    $list.=make_token();
    $list.='</div></div>';/*
    $list.='<div id="tab3content" style="display: none;">';
    $list.='</div>';*/
    return $list;
}
?>
