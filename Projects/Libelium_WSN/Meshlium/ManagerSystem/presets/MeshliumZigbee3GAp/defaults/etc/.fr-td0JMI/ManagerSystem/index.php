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

// Check for an authorized and logged user.

// Check for server mode or display mode. If we get any post income we assume that
// a server action is required. If there is no post info display mode is used as
// default.

include_once 'core/functions/check_login.php';

// Global version variable.
$manager_system_version="2.0.3";
//$mesh_kind = "Meshlium MeshAP";
$uploadhtml.= "";


    

if (isset($_REQUEST['presets']))
{
    include_once 'core/main_admin.php';
}
elseif (isset($_REQUEST['restart']) || isset($_REQUEST['shutdown']) || isset($_REQUEST['restartConfirm']) || isset($_REQUEST['shutdownConfirm']))
{
    include_once 'core/structure/haltRestart/haltRestart.php';
}
else
{
    if (isset($_REQUEST['confirm']))
    {
        exec("sudo remountrw");
        exec("sudo rm /etc/network/interfaces.confirmation");
        exec("sudo cp /etc/network/interfaces /etc/network/interfaces.lastValidated");

        exec("cp /var/www/ManagerSystem/plugins/a_interfaces/a0_ethernet/data/eth0_conf /var/www/ManagerSystem/plugins/a_interfaces/a0_ethernet/data/eth0_conf.lastValidated");
        exec("cp /var/www/ManagerSystem/plugins/a_interfaces/b0_wifi_ap/data/ath0_conf /var/www/ManagerSystem/plugins/a_interfaces/b0_wifi_ap/data/ath0_conf.lastValidated");
        exec("cp /var/www/ManagerSystem/plugins/a_interfaces/c0_wifi_mesh/data/ath1_conf /var/www/ManagerSystem/plugins/a_interfaces/c0_wifi_mesh/data/ath1_conf.lastValidated");

        exec("sudo remountro");
        
        echo "<div id='notificacion'><p>Confirmation acepted</p><br></div>";

        exec ("sudo ps x | grep confirmation | grep zz", $ps);
        $proccess = trim($ps['0']);
        $pidPS = explode(' ', $proccess);
        exec("sudo kill -9 ".$pidPS['0']);
    }
    if (isset($_REQUEST['default']))
    {
        exec("sudo remountrw");
        exec("sudo cp /etc/network/interfaces.default /etc/network/interfaces");

        exec("cp /var/www/ManagerSystem/plugins/a_interfaces/a0_ethernet/data/eth0_conf.default /var/www/ManagerSystem/plugins/a_interfaces/a0_ethernet/data/eth0_conf");
        exec("cp /var/www/ManagerSystem/plugins/a_interfaces/b0_wifi_ap/data/ath0_conf.default /var/www/ManagerSystem/plugins/a_interfaces/b0_wifi_ap/data/ath0_conf");
        exec("cp /var/www/ManagerSystem/plugins/a_interfaces/c0_wifi_mesh/data/ath1_conf.default /var/www/ManagerSystem/plugins/a_interfaces/c0_wifi_mesh/data/ath1_conf");

        exec("sudo remountro");

        echo "<div id='notificacion'><p>Restoring default values, restart the machine to take effect.</p><br></div>";

        exec ("sudo ps x | grep confirmation | grep zz", $ps);
        $proccess = trim($ps['0']);
        $pidPS = explode(' ', $proccess);
        exec("sudo kill -9 ".$pidPS['0']);
    }
    if (isset($_REQUEST['last']))
    {
        exec("sudo remountrw");
        exec("sudo cp /etc/network/interfaces.lastValidated /etc/network/interfaces");

        exec("cp /var/www/ManagerSystem/plugins/a_interfaces/a0_ethernet/data/eth0_conf.lastValidated /var/www/ManagerSystem/plugins/a_interfaces/a0_ethernet/data/eth0_conf");
        exec("cp /var/www/ManagerSystem/plugins/a_interfaces/b0_wifi_ap/data/ath0_conf.lastValidated /var/www/ManagerSystem/plugins/a_interfaces/b0_wifi_ap/data/ath0_conf");
        exec("cp /var/www/ManagerSystem/plugins/a_interfaces/c0_wifi_mesh/data/ath1_conf.lastValidated /var/www/ManagerSystem/plugins/a_interfaces/c0_wifi_mesh/data/ath1_conf");

        exec("sudo remountro");

        echo "<div id='notificacion'><p>Confirmation cancelled, restart the machine to take effect.</p><br></div>";

        exec ("sudo ps -x | grep confirmation | grep zz", $ps);
        $proccess = trim($ps['0']);
        $pidPS = explode(' ', $proccess);
        exec("sudo kill -9 ".$pidPS['0']);
    }
    //else
    //{
        $countdown = exec ("ps aux | grep confirmation | grep S99 | wc -l", $countdown_array);

        if (file_exists("/etc/network/interfaces.confirmation") && $countdown=='2')
        {
            // Confirmation mode
            include_once 'core/main_confirmation.php';
        }
        else
        {
            exec("sudo remountro");
            if(!empty($_POST))
            {
                // Servidor mode
                include_once 'core/main_server.php';
            }
            else
            {

                // Display mode
                include_once 'core/main_display.php';
            }
        }
   // }
}
?>