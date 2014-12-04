<link type="text/css" rel="Stylesheet" href="/ManagerSystem/plugins/c_tools/i0_bluetooth_scan/css/basic.css" />
<?php/*
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
?>
<?php

    flush();
    $cont=1;
    exec('cat /tmp/scannow | grep -c ""',$contador);
    echo '<div style="height:300px;width:830px;"><table id="background-image">
    <thead><tr>
    <th scope="col">TimeStamp</th>
    <th scope="col">MAC</th>
    <th scope="col">ID</th>
    <th scope="col">RSSI</th>
    <th scope="col">CoD</th>
    <th scope="col">Vendor</th>
    </tr></thead>
    <tbody>';

    while ($cont+1<=$contador[0])
    {	
	$cont=$cont+1;
        exec('cat /tmp/scannow | grep -n "" | grep "'.$cont.':2"',$linea);
	$cont=$cont-1;
        exec("echo ".$linea[$cont-1]." | cut -d'+' -f1",$date);
        exec("echo ".$linea[$cont-1]." | cut -d'+' -f2",$mac);
        exec("echo ".$linea[$cont-1]." | cut -d'+' -f3",$name);
	exec("echo ".$linea[$cont-1]." | cut -d'+' -f4",$rssi);
	exec("echo ".$linea[$cont-1]." | cut -d'+' -f5",$vendor);
	exec("echo ".$linea[$cont-1]." | cut -d'+' -f6",$cod);
        exec("echo ".$linea[$cont-1]." | cut -d'+' -f2 | cut -d':' -f1",$mac1);
        exec("echo ".$linea[$cont-1]." | cut -d'+' -f2 | cut -d':' -f2",$mac2);
        exec("echo ".$linea[$cont-1]." | cut -d'+' -f2 | cut -d':' -f3",$mac3);

	/*$link = mysql_connect('localhost', 'root', 'libelium2007');
	mysql_select_db('macaddress',$link);
	$result = mysql_query('SELECT oui FROM OUIData WHERE `hex_mac` = \''.$mac1[$cont-1].'-'.$mac2[$cont-1].'-'.$mac3[$cont-1].'\'', $link);

	$row = mysql_fetch_assoc($result);
*/
	$rest=substr($date[$cont-1],2);
	echo '<tr>
		<td>'.$rest."</td>
		<td>".$mac[$cont-1]."</td>
		<td>".$name[$cont-1]."</td>
		<td>".$rssi[$cont-1]."</td>
		<td>".$cod[$cont-1]."</td>
		<td>".$vendor[$cont-1]."</td>
	      </tr>";
        flush();
        // avoid a busy wait
        usleep(500);
        //sleep(1);
	$cont=$cont+1;
    }
    //exec("sudo remountro", $ret);
    echo "</tbody>
    </table></div>";

?>
