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

include_once $API_core.'complex_ajax_return_functions.php';
include_once $API_core.'json_api.php';
//include_once $API_core.'save_hcid.php';
//include_once $API_core.'parser_hcid.php';
include_once $base_plugin.'php/interface_generator.php';
include_once $API_core.'form_fields_check.php';

function save_hcid($data, $path)
{
    exec('echo "'.$data['interval'].'" > '.$path.'interval');
    
    $writepath=$path.'temp_hcid_conf';
    $fp=fopen($writepath,"w");
    fwrite($fp, "\n\n\n\n\n\n\n");
    fwrite($fp, '"'.$data['name'].'";'."\n");
    fwrite($fp, "\n");
    if(isset($data['visible']))
    {
        fwrite($fp, 'enable;');
    }
    else
    {
        fwrite($fp, 'disable;');
    }
    fclose($fp);
    
    exec('paste '.$path.'hcid_conf '.$path.'temp_hcid_conf > '.$path.'hcid_aux');
    exec("cat ".$path."hcid_aux | tr '\t' ' ' > ".$path.'hcid.conf');
    exec("rm ".$path.'hcid_aux');
    exec("rm ".$path.'temp_hcid_conf');
    exec('sudo cp '.$path.'hcid.conf /etc/bluetooth/hcid.conf');
    exec("rm ".$path.'hcid.conf');
}


if ($_POST['type']=="save")
{
    exec("sudo remountrw");
    $hcid_configuration=jsondecode($_POST['form_fields']);
    //save_hcid($hcid_configuration,$base_plugin.'data/hcid_conf');
    
    save_hcid($hcid_configuration,$base_plugin.'data/');
    
    
    exec("sudo remountro");
    //response_additem("return", '<pre>'.print_r($hcid_configuration,true).'</pre>');
    //endSaveAlert();
        response_additem("script", 'endnotify()');
        response_additem("script", 'notify("save", "Data saved.")');
        response_additem("script", 'fadenotify()');

    response_return();
}
elseif ($_POST['type']=="startBtDaemon")
{
    //exec("sudo /etc/init.d/BtScanD.sh stop");
    exec("sudo /etc/init.d/BtScanD.sh start &> /dev/null &");
    echo exec("ps ax | grep BtScan | grep -v grep | wc -l");
}
elseif ($_POST['type']=="downloadFile")
{
    exec("sudo remountrw");
    exec("cp /mnt/user/bluetooth_data/".$_POST['file']." /var/www/ManagerSystem/tmp/.");
    exec("sudo remountro");
}
elseif ($_POST['type']=="viewFile")
{
    exec("sudo remountrw");
    exec("tail -n ".$_POST['num']." /mnt/user/bluetooth_data/".$_POST['file'], $fileArray);
    $fileContent = '';
    foreach ($fileArray as $line) {
        $fileContent .= $line."<br>";
    }
    exec("sudo remountro");
    echo $fileContent;
}
elseif ($_POST['type']=="selectFile")
{
    exec("sudo remountrw");
    exec('sudo echo "'.$_POST['file'].'" > '.$base_plugin.'data/selectedFile');
    exec("sudo remountro");
    echo make_storedData();
}
elseif ($_POST['type']=="createFile")
{
    exec("sudo remountrw");
    exec('sudo touch /mnt/user/bluetooth_data/'.$_POST['file']);
    exec("sudo remountro");
    echo make_storedData();
}
elseif ($_POST['type']=="deleteFile")
{
    exec("sudo remountrw");
    exec('sudo rm /mnt/user/bluetooth_data/'.$_POST['file']);
    exec("sudo remountro");
    echo make_storedData();
}
elseif ($_POST['type']=="showMeNow")
{
    exec("sudo remountrw");
    $out = '<iframe class="iframe" src="'.$url_plugin.'php/scan.php"></iframe>';
    exec("sudo remountro");
    echo $out;
}
elseif ($_POST['type']=="showlocalDB")
{
    global $base_plugin, $section, $plugin;

    $DATABASE = exec('cat /mnt/lib/cfg/bluetoothDBSetup | grep -n "" | grep 1: | cut -d\':\' -f2');
    $TABLE = exec('cat /mnt/lib/cfg/bluetoothDBSetup | grep -n "" | grep 2: | cut -d\':\' -f2');
    $IP = exec('cat /mnt/lib/cfg/bluetoothDBSetup | grep -n "" | grep 3: | cut -d\':\' -f2');
    $PORT = exec('cat /mnt/lib/cfg/bluetoothDBSetup | grep -n "" | grep 4: | cut -d\':\' -f2');
    $USER = exec('cat /mnt/lib/cfg/bluetoothDBSetup | grep -n "" | grep 5: | cut -d\':\' -f2');
    $PASS = exec('cat /mnt/lib/cfg/bluetoothDBSetup | grep -n "" | grep 6: | cut -d\':\' -f2');
    
   // Conectar con el servidor de base de datos
      $conexion = mysql_connect ($IP, $USER, $PASS)
         or die ("No se puede conectar con el servidor");

   // Seleccionar base de datos
      mysql_select_db ($DATABASE)
         or die ("No se puede seleccionar la base de datos");

   // Enviar consulta
      $instruccion = "select * from ".$TABLE." order by ID_frame desc limit ".$_POST['num'].";";
      $consulta = mysql_query ($instruccion, $conexion)
         or die ("Fallo en la consulta");

   // Mostrar resultados de la consulta
     $nfilas = mysql_num_rows ($consulta);
      if ($nfilas > 0)
      {
          echo '<table style="width: 100%;">
                    </tbody>
                        <tr>
                            <th style="text-align:left;">Frame ID</th>
                            <th style="text-align:left;">TimeStamp</th>
                            <th style="text-align:left;">MAC</th>
                            <th style="text-align:left;">ID</th>
                        </tr>';

         for ($i=0; $i<$nfilas; $i++)
         {
            $resultado = mysql_fetch_array ($consulta);
            echo '
                    <tr>
                        <td style="text-align:left;text-indent:0px;">'.$resultado['ID_frame'].'</td>
                        <td style="text-align:left;text-indent:0px;">'.$resultado['TimeStamp'].'</td>
                        <td style="text-align:left;text-indent:0px;">'.$resultado['MAC'].'</td>
                        <td style="text-align:left;text-indent:0px;">'.$resultado['ID'].'</td>
                    </tr>
                 ';
         }

         echo '</tbody></table>';
      }
      else
         echo 'No data avaliable';
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
CREATE TABLE IF NOT EXISTS `bluetoothData` (
  `ID_frame` int(11) NOT NULL auto_increment,
  `TimeStamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `MAC` varchar(17) collate utf8_unicode_ci NOT NULL,
  `ID` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`ID_frame`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
    </pre>
    <b>Just copy paste:</b><br>
    <pre>
GRANT ALL PRIVILEGES ON *.* TO root@'%' IDENTIFIED BY 'passw';
    </pre>";
    exec("sudo remountro");

}
elseif ($_POST['type']=="useLocalFile")
{
    exec("sudo remountrw");
    if($_POST['state'] == 'on')
    {
        exec("rm ".$base_plugin.'data/localFile');
        exec("cp ".$base_plugin.'data/selectedFile  '.$base_plugin.'data/localFile');
    }
    else
    {
        exec("rm ".$base_plugin.'data/localFile');
    }
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
    if($_POST['state'] == 'on')
    {
        exec("rm ".$base_plugin.'data/extDB');
        exec("touch ".$base_plugin.'data/extDB');
    }
    else
    {
        exec("rm ".$base_plugin.'data/extDB');
    }
    exec("sudo remountro");
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

    echo "Sending Inquiry ...<br>";
   // Enviar consulta
      $instruccion = "insert into ".$form_data['ExtTable']." values (null, now(), 'a', 'a');";
      $consulta = mysql_query ($instruccion, $conexion)
         or die ("<b style='color: red'>Unable to send the query, check the field: Table</b>");

   // Enviar consulta
      $instruccion = "SELECT @@IDENTITY";
      $consulta = mysql_query ($instruccion, $conexion)
         or die ("<b style='color: red'>Unable to send the query, check the field: Table</b>");

    $resultado = mysql_fetch_array ($consulta);
    $resultado['@@IDENTITY'];

    echo "Query generated with id: ".$resultado['@@IDENTITY']."<br>";

   // Enviar consulta
      $instruccion = "DELETE FROM ".$form_data['ExtTable']." WHERE ".$form_data['ExtTable'].".ID_frame = ".$resultado['@@IDENTITY']." LIMIT 1";
      $consulta = mysql_query ($instruccion, $conexion)
         or die ("<b style='color: red'>Unable to send the query, check the field: Table</b>");

    

    echo "<b style='color: green'>OK</b>";
}
elseif ($_POST['type']=="saveDataConnection")
{
    $form_data=jsondecode($_POST['form_fields']);
    exec("sudo remountrw");

    $writepath=$base_plugin.'data/ExtDataConnection';
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
      $instruccion = "select * from ".$form_data['ExtTable']." order by ID_frame desc limit ".$_POST['num'].";";
      $consulta = mysql_query ($instruccion, $conexion)
         or die ("<b style='color: red'>Unable to send the query, check the field: Table</b>");

   // Mostrar resultados de la consulta
     $nfilas = mysql_num_rows ($consulta);
      if ($nfilas > 0)
      {
          echo '<table style="width: 100%;">
                    </tbody>
                        <tr>
                            <th style="text-align:left;">Frame ID</th>
                            <th style="text-align:left;">TimeStamp</th>
                            <th style="text-align:left;">MAC</th>
                            <th style="text-align:left;">ID</th>
                        </tr>';

         for ($i=0; $i<$nfilas; $i++)
         {
            $resultado = mysql_fetch_array ($consulta);
            echo '
                    <tr>
                        <td style="text-align:left;text-indent:0px;">'.$resultado['ID_frame'].'</td>
                        <td style="text-align:left;text-indent:0px;">'.$resultado['TimeStamp'].'</td>
                        <td style="text-align:left;text-indent:0px;">'.$resultado['MAC'].'</td>
                        <td style="text-align:left;text-indent:0px;">'.$resultado['ID'].'</td>
                    </tr>
                 ';
         }

         echo '</tbody></table>';
      }
      else
         echo 'No data avaliable';
}
?>