<link type="text/css" rel="Stylesheet" href="/ManagerSystem/plugins/c_tools/h0_wifi_scan/css/basic.css" />
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
 *  Author: Joaquin Ruiz
 */
    flush();
    echo '<div style="height:300px;width:830px;"><table id="background-image">
<thead><tr>
<th scope="col">TimeStamp</th>
<th scope="col">MAC</th>
<th scope="col">AP</th>
<th scope="col">RSSI</th>
<th scope="col">Vendor</th>
</tr></thead>
<tbody>';

    $lineas = file("/tmp/wifiresult");

    foreach ($lineas as $num_linea => $linea) {
      //  exec('echo '.$content.' | grep ":" | grep -n "" | grep "'.$cont.':,"',$linea);
        exec("echo \"".$linea."\" | cut -d',' -f2",$mac);
        exec("echo \"".$linea."\" | cut -d',' -f3",$time);
        exec("echo \"".$linea."\" | cut -d',' -f4",$power);
        exec("echo \"".$linea."\" | cut -d',' -f5",$asociated);
        exec("echo \"".$linea."\" | cut -d',' -f6",$company);
	echo '<tr>
		<td>'.$time[$num_linea*2]."</td>
		<td>".$mac[$num_linea*2]."</td>
		<td>".$asociated[$num_linea*2]."</td>
		<td>".$power[$num_linea*2]."</td>
		<td>".$company[$num_linea*2]."</td>
	      </tr>";
        //echo $cont." - ".$mac[$cont-1]." - ".$company[$cont-1]." - ".$time[$cont-1]." - ".$associated[$cont-1]." - ".$power[$cont-1]."\n";
        //flush();
        // avoid a busy wait
        //usleep(500);
	//echo $num_linea."<br>".$linea."<br>";
        //sleep(1);
	//$cont=$cont+1;
    }
    //exec("sudo remountro", $ret);
echo "</tbody>
</table></div>";
?>
