<?php
if($_POST['type']=='data_request'){
    include_once $API_core.'complex_ajax_return_functions.php';
    exec('sensors',$_sensors);
    $_sensor_label[]='Time';
    $_sensor_value[]='';
    foreach ($_sensors as $_sensor) {
        $_sensor_list=explode(':',$_sensor);
        $_sensor_label[]=$_sensor_list[0];;
        $tmp1=explode('+',$_sensor_list[1]);
        $tmp2=explode('C',$tmp1[1]);
        $_sensor_value[]=trim($tmp2[0]);
        unset($tmp1);
        unset($tmp2);
    }
    response_additem('labels', $_sensor_label);
    response_additem('graph_data', $_sensor_value);
    response_return();
}
?>