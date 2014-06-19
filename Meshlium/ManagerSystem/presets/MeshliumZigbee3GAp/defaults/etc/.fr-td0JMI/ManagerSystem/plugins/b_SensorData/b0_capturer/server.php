<?php

include_once $API_core.'complex_ajax_return_functions.php';
include_once $API_core.'json_api.php';;
include_once $base_plugin.'php/interface_capturer.php';
include_once $API_core.'form_fields_check.php';




if ($_POST['type']=="downloadFile")
{
    exec("sudo remountrw");
    exec("cp /mnt/user/zigbee_data/".$_POST['file']." /var/www/ManagerSystem/tmp/.");
    exec("sudo remountro");
}
elseif ($_POST['type']=="startZigbeeStorerDaemon")
{
    //exec("sudo /etc/init.d/BtScanD.sh stop");
    exec("sudo /etc/init.d/ZigbeeScanD.sh start &> /dev/null &");
   sleep(1);
    echo exec("ps ax | grep zigbeeStorer | grep -v grep | wc -l");
}
elseif ($_POST['type']=="viewFile")
{
    exec("sudo remountrw");
    exec("tail -n ".$_POST['num']." /mnt/user/zigbee_data/".$_POST['file'], $fileArray);
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
    exec('sudo touch /mnt/user/zigbee_data/'.$_POST['file']);
    exec("sudo remountro");
    echo make_storedData();
}
elseif ($_POST['type']=="deleteFile")
{
    exec("sudo remountrw");
    exec('sudo rm /mnt/user/zigbee_data/'.$_POST['file']);
    exec("sudo remountro");
    echo make_storedData();
}
elseif ($_POST['type']=="showMeNow")
{
    exec("sudo remountrw");
    exec("touch ".$base_plugin.'data/showMeNowCheck');;
    exec("sudo remountro");
    exec("cat /mnt/user/zigbee_data/.showMeNowFile", $zbLine);
    echo "<span style='font-size: 13px;font-family:Arial,Helvetica,sans-serif;' ><b>".exec("sudo date")."</b></span><br><hr><br>";
    foreach ($zbLine as $line) {
        echo $line;
    }

}
elseif ($_POST['type']=="stopMeNow")
{
    exec("sudo remountrw");
    exec("rm ".$base_plugin.'data/showMeNowCheck');
    exec("sudo remountro");
}
elseif ($_POST['type']=="showlocalDB")
{
    $DATABASE = exec('cat /mnt/lib/cfg/zigbeeDBSetup | grep -n "" | grep 1: | cut -d\':\' -f2');
    $TABLE = exec('cat /mnt/lib/cfg/zigbeeDBSetup | grep -n "" | grep 2: | cut -d\':\' -f2');
    $IP = exec('cat /mnt/lib/cfg/zigbeeDBSetup | grep -n "" | grep 3: | cut -d\':\' -f2');
    $PORT = exec('cat /mnt/lib/cfg/zigbeeDBSetup | grep -n "" | grep 4: | cut -d\':\' -f2');
    $USER = exec('cat /mnt/lib/cfg/zigbeeDBSetup | grep -n "" | grep 5: | cut -d\':\' -f2');
    $PASS = exec('cat /mnt/lib/cfg/zigbeeDBSetup | grep -n "" | grep 6: | cut -d\':\' -f2');

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
                            <th style="text-align:left;">ID</th>
                            <th style="text-align:left;">TimeStamp</th>
                            <th style="text-align:left;">MAC</th>
                            <th style="text-align:left;">x</th>
                            <th style="text-align:left;">y</th>
                            <th style="text-align:left;">z</th>
                            <th style="text-align:left;">Temp</th>
                            <th style="text-align:left;">Battery</th>
                        </tr>';

         for ($i=0; $i<$nfilas; $i++)
         {
            $resultado = mysql_fetch_array ($consulta);
            echo '
                    <tr>
                        <td style="text-align:left;text-indent:0px;">'.$resultado['ID_frame'].'</td>
                        <td style="text-align:left;text-indent:0px;">'.$resultado['TimeStamp'].'</td>
                        <td style="text-align:left;text-indent:0px;">'.$resultado['mac'].'</td>
                        <td style="text-align:left;text-indent:0px;">'.$resultado['x'].'</td>
                        <td style="text-align:left;text-indent:0px;">'.$resultado['y'].'</td>
                        <td style="text-align:left;text-indent:0px;">'.$resultado['z'].'</td>
                        <td style="text-align:left;text-indent:0px;">'.$resultado['temp'].'</td>
                        <td style="text-align:left;text-indent:0px;">'.$resultado['bat'].'</td>
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
CREATE TABLE IF NOT EXISTS `zigbeeData` (
  `ID_frame` int(11) NOT NULL auto_increment,
  `TimeStamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `mac` varchar(16) collate utf8_unicode_ci NOT NULL,
  `x` varchar(16) collate utf8_unicode_ci NOT NULL,
  `y` varchar(16) collate utf8_unicode_ci NOT NULL,
  `z` varchar(16) collate utf8_unicode_ci NOT NULL,
  `temp` varchar(16) collate utf8_unicode_ci NOT NULL,
  `bat` varchar(16) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`ID_frame`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=39 ;
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
      $instruccion = "insert into ".$form_data['ExtTable']." values (null, now(), 'a', 'a', 'a', 'a', 'a', 'a');";
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
          echo '<table style="width: 440px;">
                    </tbody>
                        <tr>
                            <th style="text-align:left;">ID</th>
                            <th style="text-align:left;">TimeStamp</th>
                            <th style="text-align:left;">MAC</th>
                            <th style="text-align:left;">x</th>
                            <th style="text-align:left;">y</th>
                            <th style="text-align:left;">z</th>
                            <th style="text-align:left;">Temp</th>
                            <th style="text-align:left;">Battery</th>
                        </tr>';

         for ($i=0; $i<$nfilas; $i++)
         {
            $resultado = mysql_fetch_array ($consulta);
            echo '
                    <tr>
                        <td style="text-align:left;text-indent:0px;">'.$resultado['ID_frame'].'</td>
                        <td style="text-align:left;text-indent:0px;">'.$resultado['TimeStamp'].'</td>
                        <td style="text-align:left;text-indent:0px;">'.$resultado['mac'].'</td>
                        <td style="text-align:left;text-indent:0px;">'.$resultado['x'].'</td>
                        <td style="text-align:left;text-indent:0px;">'.$resultado['y'].'</td>
                        <td style="text-align:left;text-indent:0px;">'.$resultado['z'].'</td>
                        <td style="text-align:left;text-indent:0px;">'.$resultado['temp'].'</td>
                        <td style="text-align:left;text-indent:0px;">'.$resultado['bat'].'</td>
                    </tr>
                 ';
         }

         echo '</tbody></table>';
      }
      else
         echo 'No data avaliable';
}
?>