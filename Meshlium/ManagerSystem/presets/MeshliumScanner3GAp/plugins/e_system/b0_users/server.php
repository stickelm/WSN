<?php
/*
 *  Copyright (C) 2009 Libelium Comunicaciones Distribuidas S.L.
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
 *  Author: Daniel Larraz <d.larraz [at] libelium [dot] com>
 */

include_once $API_core.'json_api.php';
include_once $API_core.'complex_ajax_return_functions.php';
include_once $API_core.'form_fields_check.php';
include_once $base_plugin.'php/paths.php';
include_once $base_plugin.'php/write_users.php';


/* ------------------------------------------------------------------------ */
if ( $_POST['type']=="nv" )
{
    if ( isset($_POST['form_fields']) )
    {
        $post_data=jsondecode ($_POST['form_fields']);
    }
    exec("sudo remountrw");
    switch ($_POST['action'])
    {
        case 'update_pass':
            if ( $post_data['password'] != $post_data['cnf_password'] )
            {
                response_additem("script", 'endnotify()');
                response_additem("script", 'notify("fail", "Password missmatch")');
                response_additem("script", 'fadenotify()');
            }
            elseif(strlen($post_data['password']) <= 5)
            {
                response_additem("script", 'endnotify()');
                response_additem("script", 'notify("fail", "Password too short (at least 6 characters)")');
                response_additem("script", 'fadenotify()');

            }
            else
            {
                // Mandatory check should be the last one for coherency with js.
                // But you can priorize a check alert checking it last.
                $fields_check_types = Array (
                  'password' => Array ('ms_mandatory'),
                  'cnf_password' => Array ('ms_mandatory')
                );

                //return are_form_fields_valid ($post_data, $fields_check_types, $fileds_ms_ctes);
                if(are_form_fields_valid ($post_data, $fields_check_types, $fileds_ms_ctes))
                {                    
                    exec('echo "'.trim($post_data['password']).'" | openssl passwd -1 -stdin', $newPass);

                    $writepath='/tmp/shadow';

                    $fp=fopen($writepath,'w');
                    fwrite($fp,"root:".$newPass['0'].":14760:0:99999:7:::\n");
                    fclose($fp);

                    exec ("sudo cat /etc/shadow | grep -v root >> /tmp/shadow");
                    exec ("sudo cp /tmp/shadow /etc/shadow");
                        
                      $libPass = exec("cat /var/www/ManagerSystem/core/globals/users.php | grep libelium | cut -d\"'\" -f4");

                    $users = array("root" => $newPass['0'], "libelium" => $libPass);
                    write_users ($paths['users'], $users);

                    response_additem("script", 'endnotify()');
                    response_additem("script", 'notify("save", "Data saved.")');
                    response_additem("script", 'fadenotify()');
                }
            }
            break;
    }
    exec("sudo remountro");
    /* DEBUG OUTPUT */
    //response_additem ("html", "<pre>".print_r($post_data,true)."</pre>", "debug");
    response_return();
}
if ( $_POST['type']=="mysqlpass" )
{
    exec("sudo remountrw");

    $post_data=jsondecode ($_POST['form_fields']);
    if($post_data['mysqlPassword'] == $post_data['cnf_mysqlPassword'])
    {
        $oldPass = exec('cat /mnt/lib/cfg/bluetoothDBSetup | grep -n "" | grep 6: | cut -d\':\' -f2');
        exec ('mysqladmin -u root --password="'.$oldPass.'" password '.$post_data['mysqlPassword']);

        $writepath='/mnt/lib/cfg/zigbeeDBSetup';
        $fp=fopen($writepath,"w");
            fwrite($fp, "MeshliumDB\n");
            fwrite($fp, "zigbeeData\n");
            fwrite($fp, "localhost\n");
            fwrite($fp, "3306\n");
            fwrite($fp, "root\n");
            fwrite($fp, $post_data['mysqlPassword']."\n");
        fclose($fp);

        $writepath='/mnt/lib/cfg/bluetoothDBSetup';
        $fp=fopen($writepath,"w");
            fwrite($fp, "MeshliumDB\n");
            fwrite($fp, "bluetoothData\n");
            fwrite($fp, "localhost\n");
            fwrite($fp, "3306\n");
            fwrite($fp, "root\n");
            fwrite($fp, $post_data['mysqlPassword']."\n");
        fclose($fp);

        $writepath='/mnt/lib/cfg/gpsDBSetup';
        $fp=fopen($writepath,"w");
            fwrite($fp, "MeshliumDB\n");
            fwrite($fp, "gpsData\n");
            fwrite($fp, "localhost\n");
            fwrite($fp, "3306\n");
            fwrite($fp, "root\n");
            fwrite($fp, $post_data['mysqlPassword']."\n");
        fclose($fp);

	$writepath='/mnt/lib/cfg/wifiDBSetup';
        $fp=fopen($writepath,"w");
            fwrite($fp, "MeshliumDB\n");
            fwrite($fp, "wifiScan\n");
            fwrite($fp, "localhost\n");
            fwrite($fp, "3306\n");
            fwrite($fp, "root\n");
            fwrite($fp, $post_data['mysqlPassword']."\n");
        fclose($fp);

        echo "Password changed";
    }
    else
    {
        echo "Passwords must match";
    }

    exec("sudo remountro");
}
?>
