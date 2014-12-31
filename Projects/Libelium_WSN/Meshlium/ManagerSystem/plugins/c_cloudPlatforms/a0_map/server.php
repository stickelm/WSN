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

include_once $API_core.'complex_ajax_return_functions.php';
include_once $API_core.'json_api.php';;
include_once $base_plugin.'php/interface_capturer.php';
include_once $API_core.'form_fields_check.php';

if ($_POST['type']=="startZigbeeStorerDaemon")
{
  exec("sudo /etc/init.d/ZigbeeScanD.sh start &> /dev/null &");
  sleep(1);
  echo exec("ps ax | grep zigbeeStorer | grep -v grep | wc -l");
}
else if($_POST['type']=="saveWasp")
{
    $DATABASE = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 1: | cut -d : -f2');
    $TABLE = "waspmote";
    $IP = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 3: | cut -d : -f2');
    $PORT = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 4: | cut -d : -f2');
    $USER = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 5: | cut -d : -f2');
    $PASS = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 6: | cut -d : -f2');

    $form_data=jsondecode($_POST['form_fields']);

   // Conectar con el servidor de base de datos
      $conexion = mysql_connect ($IP, $USER, $PASS)
         or die ("No se puede conectar con el servidor");

   // Seleccionar base de datos
      mysql_select_db ($DATABASE)
         or die ("No se puede seleccionar la base de datos");

   // Enviar consulta
      $instruccion = "UPDATE ".$TABLE." SET `name`='".$form_data['meshName']."', `description`='".$form_data['meshDesc']."', `sensorCount`='".$form_data['sensorCount']."' where OBJECTID = ".$_POST['id'].";";
      $consulta = mysql_query ($instruccion, $conexion)
         or die ("Fallo en la consulta");
      $resultado = mysql_fetch_array ($consulta);

      echo $form_data["meshName"];
}
else if($_POST['type']=="delWasp")
{
    $DATABASE = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 1: | cut -d : -f2');
    $TABLE = "waspmote";
    $IP = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 3: | cut -d : -f2');
    $PORT = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 4: | cut -d : -f2');
    $USER = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 5: | cut -d : -f2');
    $PASS = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 6: | cut -d : -f2');

    $form_data=jsondecode($_POST['form_fields']);

   // Conectar con el servidor de base de datos
      $conexion = mysql_connect ($IP, $USER, $PASS)
         or die ("No se puede conectar con el servidor");

   // Seleccionar base de datos
      mysql_select_db ($DATABASE)
         or die ("No se puede seleccionar la base de datos");

   // Enviar consulta
      $instruccion = "DELETE FROM ".$TABLE." where OBJECTID = ".$_POST['id'].";";
      $consulta = mysql_query ($instruccion, $conexion)
         or die ("Fallo en la consulta");
      $resultado = mysql_fetch_array ($consulta);

      echo $form_data["meshName"];
}
else if($_POST['type']=="saveMesh")
{
    $DATABASE = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 1: | cut -d : -f2');
    $TABLE = "meshlium";
    $IP = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 3: | cut -d : -f2');
    $PORT = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 4: | cut -d : -f2');
    $USER = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 5: | cut -d : -f2');
    $PASS = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 6: | cut -d : -f2');

    $form_data=jsondecode($_POST['form_fields']);

   // Conectar con el servidor de base de datos
      $conexion = mysql_connect ($IP, $USER, $PASS)
         or die ("No se puede conectar con el servidor");

   // Seleccionar base de datos
      mysql_select_db ($DATABASE)
         or die ("No se puede seleccionar la base de datos");

      $instruccion = "UPDATE `meshlium` SET `name`='".$form_data['meshName']."', `description`='".$form_data['meshDesc']."' WHERE `objectid`='1'";
      $consulta = mysql_query ($instruccion, $conexion)
          or die ("Fallo en la consulta");

      echo $form_data["meshName"];
}
else if($_POST['type']=="setPos")
{
    $DATABASE = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 1: | cut -d : -f2');
    $IP = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 3: | cut -d : -f2');
    $PORT = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 4: | cut -d : -f2');
    $USER = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 5: | cut -d : -f2');
    $PASS = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 6: | cut -d : -f2');

    $form_data=jsondecode($_POST['form_fields']);

    // Conectar con el servidor de base de datos
    $conexion = mysql_connect ($IP, $USER, $PASS)
       or die ("No se puede conectar con el servidor");

    // Seleccionar base de datos
    mysql_select_db ($DATABASE)
       or die ("No se puede seleccionar la base de datos");

    // Enviar consulta
    if (strcmp($form_data['topositionate'],"meshlium")==0)
    {
      $instruccion = "UPDATE `meshlium` SET `x`=".$form_data['visorx'].", `y`=".$form_data['visory']." WHERE `OBJECTID`='1';";
      $consulta = mysql_query ($instruccion, $conexion)
         or die ("Fallo en la consulta");
      $resultado = mysql_fetch_array ($consulta);
    }
    else
    {
      $instruccion = "UPDATE `waspmote` SET `x`=".$form_data['visorx'].", `y`=".$form_data['visory']." WHERE `name`='".$form_data['topositionate']."';";
      $consulta = mysql_query ($instruccion, $conexion)
         or die ("Fallo en la consulta");
      $resultado = mysql_fetch_array ($consulta);
    }

    echo $form_data["topositionate"];
}
else if($_POST['type']=="addWasp")
{
    $DATABASE = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 1: | cut -d : -f2');
    $TABLE = "waspmote";
    $IP = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 3: | cut -d : -f2');
    $PORT = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 4: | cut -d : -f2');
    $USER = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 5: | cut -d : -f2');
    $PASS = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 6: | cut -d : -f2');
   
    $form_data=jsondecode($_POST['form_fields']);

      // Conectar con el servidor de base de datos
      $conexion = mysql_connect ($IP, $USER, $PASS)
         or die ("No se puede conectar con el servidor");

      // Seleccionar base de datos
      mysql_select_db ($DATABASE)
         or die ("No se puede seleccionar la base de datos");

      // Enviar consulta
      $instruccion = "INSERT INTO `waspmote` (`name`, `description`, `x`, `y`, `spatialReference`, `sensorCount`, `meshliumid`)
        VALUES ('".$form_data["meshName"]."', '".$form_data["meshDesc"]."', -0.05, 0.05, 4326, ".$form_data["sensorCount"].", '1');";
      $consulta = mysql_query ($instruccion, $conexion)
         or die ("Fallo en la consulta");
      $resultado = mysql_fetch_array ($consulta);

      echo $form_data["meshName"];
}
else if($_POST['type']=="addUser")
{
    $DATABASE = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 1: | cut -d : -f2');
    $TABLE = "users";
    $IP = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 3: | cut -d : -f2');
    $PORT = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 4: | cut -d : -f2');
    $USER = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 5: | cut -d : -f2');
    $PASS = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 6: | cut -d : -f2');
   
    $form_data=jsondecode($_POST['form_fields']);

      // Conectar con el servidor de base de datos
      $conexion = mysql_connect ($IP, $USER, $PASS)
         or die ("No se puede conectar con el servidor");

      // Seleccionar base de datos
      mysql_select_db ($DATABASE)
         or die ("No se puede seleccionar la base de datos");

      // Enviar consulta
      $instruccion = "INSERT INTO `users` (`user`, `passwd`)
        VALUES ('".$form_data["meshUser"]."', '".crypt($form_data["meshPassw"])."');";
      $consulta = mysql_query ($instruccion, $conexion)
         or die ("Fallo en la consulta");
      $resultado = mysql_fetch_array ($consulta);

      echo $form_data["meshUser"];
}
else if($_POST['type']=="saveUser")
{
    $DATABASE = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 1: | cut -d : -f2');
    $TABLE = "users";
    $IP = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 3: | cut -d : -f2');
    $PORT = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 4: | cut -d : -f2');
    $USER = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 5: | cut -d : -f2');
    $PASS = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 6: | cut -d : -f2');

    $form_data=jsondecode($_POST['form_fields']);
    $conexion = mysql_connect ($IP, $USER, $PASS)
      or die ("No se puede conectar con el servidor");
    mysql_select_db ($DATABASE)
      or die ("No se puede seleccionar la base de datos");
    // 1. Check password
    $instruccion = "SELECT * FROM ".$TABLE." WHERE idusers = ".$_POST['id'].";";
    $consulta = mysql_query ($instruccion, $conexion)
       or die ("Fallo en la consulta");
    $resultado = mysql_fetch_array ($consulta);

    if (crypt($form_data["meshPasswO"],$resultado["passwd"])==$resultado["passwd"])
    {
        // pass check ok
        // 2. Update data
        if ($form_data["meshPasswN"]!="")
          $instruccion = "UPDATE ".$TABLE." SET `user`='".$form_data['meshUser']."', `passwd`='".crypt($form_data['meshPasswN'])."' where idusers = ".$_POST['id'].";";
        else
          $instruccion = "UPDATE ".$TABLE." SET `user`='".$form_data['meshUser']."' where idusers = ".$_POST['id'].";";

        $consulta = mysql_query ($instruccion, $conexion)
            or die ("Fallo en la consulta");
        $resultado = mysql_fetch_array ($consulta);
    }
    else
      echo "Wrong Old Password ";

    echo $form_data["meshUser"];
}
else if($_POST['type']=="delUser")
{
    $DATABASE = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 1: | cut -d : -f2');
    $TABLE = "users";
    $IP = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 3: | cut -d : -f2');
    $PORT = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 4: | cut -d : -f2');
    $USER = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 5: | cut -d : -f2');
    $PASS = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 6: | cut -d : -f2');

    $form_data=jsondecode($_POST['form_fields']);
    $conexion = mysql_connect ($IP, $USER, $PASS)
      or die ("No se puede conectar con el servidor");
    mysql_select_db ($DATABASE)
      or die ("No se puede seleccionar la base de datos");
    // 1. Check password
    $instruccion = "SELECT * FROM ".$TABLE." WHERE idusers = ".$_POST['id'].";";
    $consulta = mysql_query ($instruccion, $conexion)
       or die ("Fallo en la consulta");
    $resultado = mysql_fetch_array ($consulta);

    if (crypt($form_data["meshPasswO"],$resultado["passwd"])==$resultado["passwd"])
    {
        // pass check ok
        // 2. Delete data
        $instruccion = "DELETE FROM ".$TABLE." where idusers = ".$_POST['id'].";";
        $consulta = mysql_query ($instruccion, $conexion)
            or die ("Fallo en la consulta");
        $resultado = mysql_fetch_array ($consulta);
    }
    else
      echo "Wrong Old Password ";

    echo $form_data["meshUser"];
}
else if($_POST['type']=="enSec")
{
  exec('echo "" > /var/www/ManagerSystem/plugins/c_cloudPlatforms/a0_map/data/security');
}
else if($_POST['type']=="disSec")
{
  exec('rm /var/www/ManagerSystem/plugins/c_cloudPlatforms/a0_map/data/security');
}
?>
