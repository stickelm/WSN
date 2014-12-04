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

$_main_title="MQTT Integration";

$_plugin_css=Array("basic.css");

$_plugin_javascript=Array("jquery-1.3.2.min.js","jquery.json-1.3.min.js","ajax.js","json_encode.js","form_fields_check.js","scripts.js");

    global $section;
    global $plugin;

    $html.='
    
    <script type="text/javascript">

        var iteracion;
        var result;

        function nl2br (str, is_xhtml) {   
            var breakTag = (is_xhtml || typeof is_xhtml === \'undefined\') ? \'<br />\' : \'<br>\';    
            return (str + \'\').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, \'$1\'+ breakTag +\'$2\');
        }

        function getFile(){
            var file = "plugins/c_cloudPlatforms/d0_mqtt/console/terminal";
            $.get(file, function(txt) { 
                $("#tab4contentScan").html( nl2br(txt)); 
                document.getElementById(\'tab4contentScan\').scrollTop = 9999999;
            });
            iteracion = setTimeout(function() { getFile()}, 500);
        }    

        function en(){
            document.getElementById("progress").style.display = "none";
            document.getElementById("start").disabled = false;
            document.getElementById("stop").disabled = true;
            $(\'#statusIBM\').html("STOPPED");
            $(\'#statusIBM\').css({"color":"#E30000"});
        }

        function dis(){
            document.getElementById("progress").style.display = "block";
            document.getElementById("stop").disabled = false;
            document.getElementById("start").disabled = true;
            $(\'#statusIBM\').html("RUNNING");
            $(\'#statusIBM\').css({"color":"#4BB007"});

        }

        function ip(){
            var file2 = "../../../../tmp/ip";
            $.get(file2, function(txt)  {
                document.getElementById("ipaddr").value=txt;
            });
        }
        function addclass(){
            var d = document.getElementById("plugin_main_div");
            d.className = d.className + " misco";
        }
    </script>';

    $html.='<div id="topesri"></div>';

    $html.='<script language="javascript">addclass();</script>';
    exec('cat /root/MQTT/config',$config);
    $c0=explode("::", $config[0]); $c1=explode("::", $config[1]); 
    $html.='<div id="plugin_content" ><h2>Server/Broker Configuration</h2><br /><form id="telef_config">
    <table> 
    <tr><td><b>IP Adress: </b></td><td><input id="config1" type="text" name="config1" style="width: 300px" value="'.$c0[1].'"/> </td></tr>
    <tr><td><b>Port number: </b></td><td><input id="config2" type="text" name="config2" value="'.$c1[1].'" style="width: 300px"/> </td></tr>
    </table></form>
    <div class="right_align">
        <input class="bsave" type="button" value="Save" onClick=
	  "save(\'telef_config\',\'output\',\''.$section.'\',\''.$plugin.'\')" style="margin-top: -27px;">
    </div>
    <a href="http://mqtt.org/documentation" target="_blank">
      <img style="margin-top:-100px;margin-left:570px" src="plugins/c_cloudPlatforms/d0_mqtt/images/mready.png"/>
    </a>
    </div>';

    $html.='<div id="plugin_content_dtar"><h2>MQTT Sync</h2><br/>
              <button id="sync" onClick="syncmqtt(\''.$section.'\',\''.$plugin.'\')">Sync DB</button>
              <div id="info">Info: Mark your Sensor Database entries as MQTT synchronized</div>
              <br />
            </div>';


    $html.='<div id="plugin_content_cons" ><h2>Console</h2>';
    
    if (file_exists('/root/MQTT/en'))
    {
      $html.='<img id="progress" src="plugins/c_cloudPlatforms/progress.gif" style="width: 32px;height: 32px;float: right;
      margin-right: 10px;margin-top: -10px;display:block"/><br />';

      $html .= '<button id="start" onclick="start(\''.$section.'\',\''.$plugin.'\');setTimeout(getFile(),5000);
                     this.disabled=true;dis();" disabled>Start Program</button>
                <button id="stop" onclick="stop(\''.$section.'\',\''.$plugin.'\');clearTimeout(iteracion);
                     this.disabled=true;en();" >Stop Program</button>
                <script>setTimeout(getFile(),5000);</script>';
    }
    else
    {
      $html.='<img id="progress" src="plugins/c_cloudPlatforms/progress.gif" style="width: 32px;height: 32px;float: right;
      margin-right: 10px;margin-top: -10px;display:none"/>';
      $html.='<p id="statusIBM" style="color: #E30000;margin-right:15px">STOPPED</p><br />';
      $html .= '<button id="start" onclick="start(\''.$section.'\',\''.$plugin.'\');setTimeout(getFile(),5000);
                     this.disabled=true;dis();" >Start</button>
                <button id="stop" onclick="stop(\''.$section.'\',\''.$plugin.'\');clearTimeout(iteracion);
                     this.disabled=true;en();" disabled>Stop</button>';
    }

    $html .= ' <button id="clean" onclick="$(\'#tab4contentScan\').empty();">Clean</button>   
             <br><br>
             <div id="tab4contentScan" style="overflow: auto;width: 97%; height: 300px;padding: 5px;
             background-color: black; color:white; border: 4px solid #c90;"></div>';
    $html .= '</div>';

?>
