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
//set_time_limit (20);


    //exec("sudo remountrw", $ret);

    //echo "url:".$url."<br>interface:".$interface."<br>";
    flush();
    //$fp=popen('ping '.escapeshellarg($url).' -i '.escapeshellarg($interface),"r");
    $fp=popen('hcitool scan',"r");
    echo "<pre>";
    while (!feof($fp))
    {
        $results = fgets($fp, 20);
        if (strlen($results) == 0) {
           // stop the browser timing out
           echo " ";
           flush();
        }
        else
        {
            echo $results;
            flush();
        }
        // avoid a busy wait
        usleep(500);
        //sleep(1);
    }
    //exec("sudo remountro", $ret);
    echo "</pre><span style='font-size: 13px;font-family:Arial,Helvetica,sans-serif;' ><b>Scan finished.</b></span>";
?>
