<?php

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
elseif ($_POST['type']=="showMeNow")
{ 

  exec("sudo remountrw");
  exec("cat /mnt/lib/cfg/parser/last_frame.log",$last_frames);
  $list.='<span style="font-size: 13px;font-family:Arial,Helvetica,sans-serif;font-size: 12px">'.$last_frames[0].'</span><br><br>';
  echo $list ;

}
elseif ($_POST['type']=="removeData")
{
  // Limpia la base de datos Local
  $DATABASE = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 1: | cut -d : -f2');
  $TABLE = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 2: | cut -d : -f2');
  $IP = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 3: | cut -d : -f2');
  $PORT = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 4: | cut -d : -f2');
  $USER = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 5: | cut -d : -f2');
  $PASS = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 6: | cut -d : -f2');
 


  // Conectar con el servidor de base de datos
  $conexion = mysql_connect ($IP, $USER, $PASS)
         or die ("No se puede conectar con el servidor");
  mysql_select_db ($DATABASE)
         or die ("No se puede seleccionar la base de datos");
  $instruccion = "TRUNCATE table ".$TABLE.";";

  $consulta = mysql_query ($instruccion, $conexion)
         or die ("Fallo en la consulta");
          
  $instruccion = "optimize table ".$TABLE.";";
  $consulta = mysql_query ($instruccion, $conexion)
         or die ("Fallo en la consulta");
}
elseif ($_POST['type']=="clearALL")
{
  // Limpia la base de datos Local
  $DATABASE = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 1: | cut -d : -f2');
  $TABLE = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 2: | cut -d : -f2');
  $IP = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 3: | cut -d : -f2');
  $PORT = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 4: | cut -d : -f2');
  $USER = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 5: | cut -d : -f2');
  $PASS = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 6: | cut -d : -f2');
 


  // Conectar con el servidor de base de datos
  $conexion = mysql_connect ($IP, $USER, $PASS)
         or die ("No se puede conectar con el servidor");
  mysql_select_db ($DATABASE)
         or die ("No se puede seleccionar la base de datos");
  $instruccion = "delete from ".$TABLE." where sync='1';";

  $consulta = mysql_query ($instruccion, $conexion)
         or die ("Fallo en la consulta");
          
  $instruccion = "optimize table ".$TABLE.";";
  $consulta = mysql_query ($instruccion, $conexion)
         or die ("Fallo en la consulta");
}
elseif ($_POST['type']=="showlocalDB")
{
    $DATABASE = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 1: | cut -d : -f2');
    $TABLE = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 2: | cut -d : -f2');
    $IP = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 3: | cut -d : -f2');
    $PORT = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 4: | cut -d : -f2');
    $USER = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 5: | cut -d : -f2');
    $PASS = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 6: | cut -d : -f2');

   // Conectar con el servidor de base de datos
      $conexion = mysql_connect ($IP, $USER, $PASS)
         or die ("No se puede conectar con el servidor");

   // Seleccionar base de datos
      mysql_select_db ($DATABASE)
         or die ("No se puede seleccionar la base de datos");

   // Enviar consulta
      $instruccion = "select * from ".$TABLE." order by id desc limit ".$_POST['num'].";";
      $consulta = mysql_query ($instruccion, $conexion)
         or die ("Fallo en la consulta");

   // Mostrar resultados de la consulta
     $nfilas = mysql_num_rows ($consulta);
      echo '<table id="background-image">
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
            echo '            
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
            echo '            
              <tr>
                <td>'.$resultado['id'].'</td>
                <td>'.$resultado['timestamp'].'</td>
                <td>'.$resultado['sync'].'</td>
                <td colspan="8">'.$resultado['raw'].'</td>                
              </tr>';
          } 
        }
      } 
}
elseif ($_POST['type']=="showSqlScript")
{
    exec("sudo remountrw");
    echo
    "<b>Just copy paste:</b><br>
    <pre>
  CREATE database MeshliumDB;
      </pre>
      <b>Just copy paste:</b><br>
      <pre>
CREATE TABLE IF NOT EXISTS `sensorParser` (
  `id` int(11) NOT NULL auto_increment,
  `id_wasp` text character set utf8 collate utf8_unicode_ci,
  `id_secret` text character set utf8 collate utf8_unicode_ci,
  `frame_type` int(11) default NULL,
  `frame_number` int(11) default NULL,
  `sensor` text character set utf8 collate utf8_unicode_ci,
  `value` text character set utf8 collate utf8_unicode_ci,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `raw` text character set utf8 collate utf8_unicode_ci,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

      </pre>
      <b>Just copy paste:</b><br>
      <pre>
  GRANT ALL PRIVILEGES ON *.* TO root@'%' IDENTIFIED BY 'passw';
      </pre>";
      exec("sudo remountro");
}
elseif ($_POST['type']=="useLocalDB")
{
    exec("sudo remountrw");
    if($_POST['state'] == 'on')
    {
        exec("rm ".$base_plugin.'data/localDB');
        exec("touch ".$base_plugin.'data/localDB');
    }
    else
    {
        exec("rm ".$base_plugin.'data/localDB');
    }
    exec("sudo remountro");
}
elseif ($_POST['type']=="useExtDB")
{
    exec("sudo remountrw");
    exec("sudo echo '".$_POST['time']."' > /mnt/lib/cfg/parser/interval");
    if($_POST['state'] == 'on')
    {
        exec("touch ".$base_plugin."data/extDB");
        exec("syncDBD.sh start > /dev/null 2>&1 &");
    }
    else
    {
       exec("rm ".$base_plugin."data/extDB");
       exec("syncDBD.sh stop 2> /dev/null");      
      
    }
    exec("sudo remountro");
}
elseif ($_POST['type']=="synchronize")
{
    exec("java -jar /bin/syncDB.jar barabaraberebere",$out);
    echo $out[0];
}
elseif ($_POST['type']=="checkConnection")
{
    $form_data=jsondecode($_POST['form_fields']);

    echo "Connecting to the database server ...<br>";

   // Conectar con el servidor de base de datos
      $conexion = mysql_connect ($form_data['ExtIP'].":".$form_data['ExtPort'], $form_data['ExtUser'], $form_data['ExtPassword'])
         or die ("<b style='color: red'>Unable to connect to the server, check the fields: IP, Port, User, Password</b>");

    echo "Selecting database ...<br>";
   // Seleccionar base de datos
      mysql_select_db ($form_data['ExtDatabase'])
         or die ("<b style='color: red'>Unable to select the database, check the field: Database</b>");

    echo "<b style='color: green'>OK</b>";
}
elseif ($_POST['type']=="saveDataConnection")
{
    $form_data=jsondecode($_POST['form_fields']);
    exec("sudo remountrw");

    $writepath='/mnt/lib/cfg/sensorExternalDB';
    $fp=fopen($writepath,"w");
    fwrite($fp, $form_data['ExtDatabase']."\n");
    fwrite($fp, $form_data['ExtTable']."\n");
    fwrite($fp, $form_data['ExtIP']."\n");
    fwrite($fp, $form_data['ExtPort']."\n");
    fwrite($fp, $form_data['ExtUser']."\n");
    fwrite($fp, $form_data['ExtPassword']."\n");
    fclose($fp);

    exec("sudo remountro");
}
elseif ($_POST['type']=="showextDB")
{
      $form_data=jsondecode($_POST['form_fields']);

   // Conectar con el servidor de base de datos
      $conexion = mysql_connect ($form_data['ExtIP'].":".$form_data['ExtPort'], $form_data['ExtUser'], $form_data['ExtPassword'])
         or die ("<b style='color: red'>Unable to connect to the server, check the fields: IP, Port, User, Password</b>");

   // Seleccionar base de datos
      mysql_select_db ($form_data['ExtDatabase'])
         or die ("<b style='color: red'>Unable to select the database, check the field: Database</b>");

   // Enviar consulta
      $instruccion = "select * from ".$form_data['ExtTable']." order by id desc limit ".$_POST['num'].";";
      $consulta = mysql_query ($instruccion, $conexion)
         or die ("<b style='color: red'>Unable to send the query, check the field: Table</b>");

     // Mostrar resultados de la consulta
     $nfilas = mysql_num_rows ($consulta);
      echo '<table id="background-image">
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
            echo '            
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
            echo '            
              <tr>
                <td>'.$resultado['id'].'</td>
                <td>'.$resultado['timestamp'].'</td>
                <td colspan="8">'.$resultado['raw'].'</td>                
              </tr>';
          } 
        }
      } 
}
?>