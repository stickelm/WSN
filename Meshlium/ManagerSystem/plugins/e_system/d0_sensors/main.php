<?php
/*Basic configuration*/
$_main_title="Meshlium Sensors";
$_plugin_css=Array("basic.css");
$_plugin_javascript=Array("jquery-1.3.2.min.js", "globals.js","dygraph.js","dygraph-canvas.js","dygraph-combined.js","excanvas.js","graph.js");//, "globals.js", "recarga.js");



/*CPU Sensors*/
exec('sensors | grep -i core',$_sensors);
foreach ($_sensors as $_sensor)
{
    $_sensor_list=explode(':',$_sensor);
    $_sensor_html.='<pre>'.$_sensor_list['0']."\t <span class='tempe'>".$_sensor_list['1'].'</span></pre>';
}
$html=' <div class="title2">Meshlium Sensors</div>
        <div id="plugin_content">
            <h3>Meshlium temperature sensors graph</h3>
            <br />
            <div style="width:700px" id="evolution_graph"></div>
            <div id="labels_div"></div>
        </div>
       <script>var section=\''.$section.'\';var plugin=\''.$plugin.'\';</script>';
unset($_sensor);unset($_sensors);unset($_sensor_list);unset($_sensor_html);

?>