<?php

include_once $API_core.'complex_ajax_return_functions.php';
include_once $API_core.'json_api.php';;
include_once $base_plugin.'php/interface_capturer.php';
include_once $API_core.'form_fields_check.php';


if ($_POST['type']=="startZigbeeStorerDaemon")
{
    //exec("sudo /etc/init.d/BtScanD.sh stop");
    exec("sudo /etc/init.d/ZigbeeScanD.sh start &> /dev/null &");
   sleep(1);
    echo exec("ps ax | grep zigbeeStorer | grep -v grep | wc -l");
}

?>