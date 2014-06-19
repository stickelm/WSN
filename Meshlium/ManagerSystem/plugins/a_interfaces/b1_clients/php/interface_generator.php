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

include_once $API_core.'parser_dhcp_server_new.php';

function make_input()
{
    global $section;
    global $plugin;



/*
    exec("wlanconfig ath0 list | cut -d' ' -f1 | grep -v ADDR", $return);
    foreach ($return as $MacItem) {
         exec('cat /var/tmp/dnsmasq.leases | grep "00:1b:77:3d:44:d3"', $return);
  */
    

    $list="
    <div class=\"title2\">Clients Connected</div>
    <div class='plugin_content' >";
        $list .= "
        <table>
            <tbody>
                <tr>
                    <td>
                        <b>Time</b>
                    </td>
                    <td>
                        <b>MAC address</b>
                    </td>
                    <td>
                        <b>IP address</b>
                    </td>
                    <td>
                        <b>Hostname</b>
                    </td>
                    <!--<td>
                        <b>Options</b>
                    </td>-->
                </tr>";

                exec("sudo wlanconfig ath0 list | cut -d' ' -f1 | grep -v ADDR", $return);
                foreach ($return as $number => $MacItem) {
                   // $list .= $MacItem."<br><br>____________________-<br>";
                    $mod = $number % '2';
                    $list .= "
                    <tr id='user_".$number."' ";
                        if($mod == '0')
                            { $list .= 'class="mod"';}
                        $list .= "
                    >";
                        unset($return2);
                        exec('sudo cat /var/tmp/dnsmasq.leases | grep "'.$MacItem.'"', $return2);

                        $values = explode(' ', $return2['0']);
                        foreach($values as $num => $item)
                        {
                            if ($num == '0')
                            {
                                $item1 = exec('date -d @'.$item);
                                $list .= "<td>".$item1."</td>";
                            }
                            elseif ($num == '1')
                            {
                                $client = $item;
                                $list .= "<td>".$item."</td>";
                            }
                            elseif ($num == '4')
                            {
                                ;//$list .= "<td><button onclick='kickUser(\"$client\", \"user_".$number."\", \"$section\", \"$plugin\")'>Disconnect user</button></td>";
                            }
                            else
                            {
                                $list .= "<td>".$item."</td>";
                            }
                            /*
                            $list .= "<pre>".print_r($values, true)."</pre>";
                            $list .="[".$num."] => [".$item."]";
                           if($num == '1')
                           {
                              $client = $item;
                              $item1 = exec('date -d @'.$item);
                           }

                           if($num == '1'){
                                $list .= "<td>".$num.": ".$item1."</td>";
                           }elseif($num == '4'){
                              $list .= "<td>".$num.": "."<button onclick='kickUser(\"$client\", \"user_".$number."\", \"$section\", \"$plugin\")'>Disconnect user</button></td>";
                           }else{
                              $list .= "<td>".$num.": ".$item."</td>";}
                             */
                        }


                        $list .= "
                    </tr>";

                }

                $list .= "
            </tbody>
        </table>
    </div>";
//$list .= "<pre>".print_r($return, true)."</pre>";
    return $list;
}

function make_interface()
{
    global $url_plugin;
    global $section;
    global $plugin;
    global $init_ifaces;
    //$options=array(' ','eth0','ath0','ath1');
    $options=array('ath0');
    $list = '
    <div id="interface">';
        $list.= make_input();
        $list.='
    </div>';

    return $list;
}
?>