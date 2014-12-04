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

$_main_title="M2M Integration";

$_plugin_css=Array("basic.css");

$_plugin_javascript=Array("jquery.json-1.3.min.js","ajax.js","json_encode.js","form_fields_check.js","scripts.js","scripts2.js");

    global $section;
    global $plugin;

    include 'b0_machineCloud/main.php';

    ob_start();
    passthru('ps -e | grep tw');
    $act = ob_get_contents();
    ob_end_clean();

    $html.='
    
    <script type="text/javascript">

    </script>

    <style>
    </style>
    <br />
    <div id="topesri"></div><div id="todoTh" style="display:none">
    <div id="plugin_content_info"><h2>Configuration</h2><br />
    <a href="http://www.thingworx.com/libelium/" target="_blank">
        <img style="margin-top:-35px;margin-left:570px" src="plugins/c_cloudPlatforms/c0_m2mApp/images/tready.png"/>
    </a>
    <table style="width:500px"> 
        <tr>
            <td><b>Thingworx MicroServer Home: </b></td>
            <td>/mnt/usr/thingworx/</td>
        </tr>
    </table>
    </div>

    <div id="plugin_content_cons"><h2>Edge MicroServer (EMS) Status</h2><br />
            <div id ="agentstatus"><img id="progressTW" src="plugins/c_cloudPlatforms/progress.gif"';
        if ($act == null)   
            $html.='style="display:none;"/> <p id="statusTW" style="color: #E30000;">STOPPED</p></div>
            <div id="buttons">
            <button id="startTW" onclick="startTW(\''.$section.'\',\''.$plugin.'\');" >Start</button>
            <button id="stopTW" onclick="stopTW(\''.$section.'\',\''.$plugin.'\');" disabled>Stop</button>
        ';
        else $html.='/><p id="statusTW" style="color:#4BB007;margin-top: -28px;margin-left: 40px;">RUNNING</p> </div>
            <div id="buttons">
            <button id="startTW" onclick="startTW(\''.$section.'\',\''.$plugin.'\');" disabled>Start</button>
            <button id="stopTW" onclick="stopTW(\''.$section.'\',\''.$plugin.'\');" >Stop</button>
        ';

        $html.='</div><br/></div></div>';



?>
