<?
// Desactivar toda notificación de error
error_reporting(0);
  if ($_POST['check'] == 'sync'){
    exec("tail -n 30 /mnt/lib/cfg/parser/sync.log",$sync);
    for ($i = 1; $i <= 30; $i++) {
      $list.='<span style="padding:10px;font-size: 12px">'.$sync[$i].'</span><br>';
      };
    echo $list;
  }

  if ($_POST['check'] == 'adv'){
    $localDATABASE = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 1: | cut -d: -f2');
    $localTABLE = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 2: | cut -d: -f2');
    $localIP = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 3: | cut -d: -f2');
    $localPORT = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 4: | cut -d: -f2');
    $localUSER = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 5: | cut -d: -f2');
    $localPASS = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 6: | cut -d: -f2');

    $extDATABASE = exec('cat /mnt/lib/cfg/sensorExternalDB | grep -n  "" | grep 1: | cut -d: -f2');
    $extTABLE = exec('cat /mnt/lib/cfg/sensorExternalDB | grep -n  "" | grep 2: | cut -d: -f2');
    $extIP = exec('cat /mnt/lib/cfg/sensorExternalDB | grep -n  "" | grep 3: | cut -d: -f2');
    $extPORT = exec('cat /mnt/lib/cfg/sensorExternalDB | grep -n "" | grep 4: | cut -d: -f2');
    $extUSER = exec('cat /mnt/lib/cfg/sensorExternalDB | grep -n  "" | grep 5: | cut -d: -f2');
    $extPASS = exec('cat /mnt/lib/cfg/sensorExternalDB | grep -n  "" | grep 6: | cut -d: -f2');

    // Conectar con el servidor de base de datos
    $localconexion = mysql_connect ($localIP, $localUSER, $localPASS);
    // Seleccionar base de datos
    mysql_select_db ($localDATABASE);

    // Enviar consulta



    $localinstruccion_1 = "select count(id) AS rows_1 from ".$localTABLE." ;";
    $localconsulta_1 = mysql_query ($localinstruccion_1, $localconexion);   
    $localnfilas_1 = mysql_fetch_array( $localconsulta_1 );
   

    $localinstruccion_2 = "select count(id) AS rows_2 from ".$localTABLE." WHERE sync=1;";
    $localconsulta_2 = mysql_query ($localinstruccion_2, $localconexion);       
    $localnfilas_2 = mysql_fetch_array( $localconsulta_2 );

   
    $localinstruccion_3 = "select count(id) AS rows_3 from ".$localTABLE." WHERE sync=0;";
    $localconsulta_3 = mysql_query ($localinstruccion_3, $localconexion);       
    $localnfilas_3 = mysql_fetch_array( $localconsulta_3 );
     


    mysql_select_db( $localDATABASE );
    $result = mysql_query( 'SHOW TABLE STATUS');
    $dbsize = 0;

      while( $row = mysql_fetch_array( $result ) ) {  

        $dbsize += $row[ "Data_length" ] + $row[ "Index_length" ];

      }
      $decimals = 2;  
      $mbyteslocal = number_format($dbsize/(1024*1024),$decimals);

      $extconexion = mysql_connect ($extIP, $extUSER, $extPASS);  
      if($extconexion) {
      mysql_select_db ($extDATABASE);         

      $extinstruccion = "select * from ".$extTABLE.";";
      $extconsulta = mysql_query ($extinstruccion, $extconexion);
      if (!$extconsulta) {
        $extnfilas=0;
      }
      else{
        $extnfilas = mysql_num_rows ($extconsulta);
      }
              mysql_select_db( $extDATABASE );
      $result = mysql_query( 'SHOW TABLE STATUS');
      $dbsize = 0;

      while( $row = mysql_fetch_array( $result ) ) {  

        $dbsize += $row[ "Data_length" ] + $row[ "Index_length" ];

      }
      $decimals = 2;  
      $mbytes = number_format($dbsize/(1024*1024),$decimals);
      mysql_close($localconexion);
      mysql_close($extconexion);
      $output = $localDATABASE." ".$mbyteslocal." ".$localTABLE." ".$localnfilas_1['rows_1']." ".$localnfilas_2['rows_2']." ".$localnfilas_3['rows_3']." ".$extDATABASE." ".$mbytes." ".$extTABLE." ".$extnfilas;
      }else{
       $output = $localDATABASE." ".$mbyteslocal." ".$localTABLE." ".$localnfilas_1['rows_1']." ".$localnfilas_2['rows_2']." ".$localnfilas_3['rows_3']." Unable_to_connect_to_the_external_database";
      }
     echo $output;
  }
  
?>