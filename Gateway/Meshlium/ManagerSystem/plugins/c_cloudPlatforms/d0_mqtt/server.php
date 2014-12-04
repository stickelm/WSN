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
include_once $API_core.'json_api.php';
include_once $base_plugin.'php/interface_capturer.php';
include_once $API_core.'form_fields_check.php';


if ($_POST['type']=="save")
{     
       exec("sudo remountrw");
       $values=jsondecode($_POST['form_fields']);

	   exec("echo IP::".$values['config1']." > /root/MQTT/config");
       exec("echo Port::".$values['config2']." >> /root/MQTT/config");
       exec("sudo remountro");

}
else if ($_POST['type']=="start")
{
    //exec('cd /var/www/ManagerSystem/plugins/c_cloudPlatforms/d0_ibm/ && ./bep.sh');
    exec("sudo remountrw");
    exec('cat /root/MQTT/config',$config);
    exec('echo "" > /root/MQTT/en');
    $c0=explode("::", $config[0]); $c1=explode("::", $config[1]); 
    exec("sudo chown www-data:www-data /dev/ttyS0");
    exec("cd /root/MQTT/ && sudo java -jar MQTT.jar -i ".$c0[1]." -p ".$c1[1]." > /var/www/ManagerSystem/plugins/c_cloudPlatforms/d0_mqtt/console/terminal");

}
else if ($_POST['type']=="stop")
{
    exec('rm /root/MQTT/en');
    exec("ps ax | grep MQTT | grep -v grep | cut -d' ' -f1",$pid);
    exec("sudo kill ".$pid[0]);
    exec("ps ax | grep MQTT | grep -v grep | cut -d' ' -f1",$pid1);
    exec("sudo kill ".$pid1[0]);
    exec("ps ax | grep MQTT | grep -v grep | cut -d' ' -f2",$pid2);
    exec("sudo kill ".$pid2[0]);
    exec("ps ax | grep MQTT | grep -v grep | cut -d' ' -f2",$pid3);
    exec("sudo kill ".$pid3[0]);

    exec("sudo remountrw");
    exec("rm /var/www/ManagerSystem/plugins/c_cloudPlatforms/d0_mqtt/console/terminal");
    exec("sudo remountro");
}
else if ($_POST['type']=="syncmqtt")
{
    $DATABASE = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 1: | cut -d : -f2');
    $TABLE = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 2: | cut -d : -f2');
    $IP = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 3: | cut -d : -f2');
    $PORT = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 4: | cut -d : -f2');
    $USER = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 5: | cut -d : -f2');
    $PASS = exec('cat /mnt/lib/cfg/sensorDBParser | grep -n "" | grep 6: | cut -d : -f2');

    $conexion = mysql_connect ($IP, $USER, $PASS)
         or die ("No se puede conectar con el servidor");

    mysql_select_db ($DATABASE)
         or die ("No se puede seleccionar la base de datos");

    $instruccion = "select * from ".$TABLE." where sync&2=0;";
    $consulta = mysql_query ($instruccion, $conexion)
         or die ("Fallo en la consulta");

    while($row = mysql_fetch_array($consulta))
    {
        $instruccionX = "UPDATE ".$TABLE." SET sync=".($row['sync']|2)." where id=".$row['id'].";";
        $consultaX = mysql_query ($instruccionX, $conexion)
             or die ("Fallo en la consulta");
    }

    mysql_close($con);

}

?>
