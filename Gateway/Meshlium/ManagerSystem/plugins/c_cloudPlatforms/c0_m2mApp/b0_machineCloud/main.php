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

/*$_main_title="M2M Platform";

$_plugin_css=Array("basic.css");

$_plugin_javascript=Array("jquery.json-1.3.min.js","ajax.js","json_encode.js","form_fields_check.js","scripts.js");
*/
    global $section;
    global $plugin;

    $html.='
    
    <script type="text/javascript">
    </script>

    <style>
    </style>';

    $html.='
        <h3 style="margin: 10 0 30 15;">Click on a Image to open the Plugin</h3>

    <div id="topaxeda"></div><div id="todoAx" style="display:none">';
    ob_start();
    passthru('/sbin/ifconfig | grep eth');
    $serID = ob_get_contents();
    preg_match('/[A-F0-9]{2}:[A-F0-9]{2}:[A-F0-9]{2}:[A-F0-9]{2}:[A-F0-9]{2}:[A-F0-9]{2}/i', $serID, $mac);
    ob_end_clean();
    $macSer = strtoupper(str_replace(':','', $mac[0]));
    $html.='<div id="plugin_content_info"><h2>Configuration</h2><br />
    <table style="width:500px"> 
    <tr><td><img src="plugins/c_cloudPlatforms/c0_m2mApp/b0_machineCloud/images/axeda-conn.png" style="height:15px"></td>
    <td><input type="button" onclick="window.open(\'http://connect.axeda.com/\',\'Connection Test\',\' width=800,height=800\')" value="Open Axeda Connection Test" style="width:85%"> 
    </td></tr>
    <tr><td><img src="plugins/c_cloudPlatforms/c0_m2mApp/b0_machineCloud/images/logo.png" style="height:15px"></td>
    <td><input type="button" onclick="window.open(\'http://developer.axeda.com/platform-access/\',\'Machine Cloud\',\' width=800,height=800\')" value="Open Axeda Machine Cloud" style="width:85%"> 
    </td></tr>
    <tr><td colspan="2"><HR style="border-color: #BAF5A4;"></td></tr>
    <tr>
        <td><b>Device: </b></td>
        <td>Libelium Meshlium</td>
    </tr>
    <tr>
        <td><b>Serial ID: </b></td>
        <td>'.$macSer.'</td>
    </tr>
    </table>
    <a href="http://www.axeda.com/community/partners/edge-device/libelium" target="_blank">
        <img style="margin-top:-155px;margin-left:580px" src="plugins/c_cloudPlatforms/c0_m2mApp/b0_machineCloud/images/aready.png"/>
    </a>
    </div>';
    ob_start();
    passthru('ps -e | grep xGate');
    $act = ob_get_contents();
    ob_end_clean();

    ob_start();
    passthru('ps -e | grep ERemote');
    $act2 = ob_get_contents();
    ob_end_clean();

    $html.='
        <div id="plugin_content_cons" ><h2>Gateway Agent Status</h2>
            <div id ="agentstatus"><img id="progress" src="plugins/c_cloudPlatforms/progress.gif"';
        if ($act == null)   
            $html.='style="display:none;"/> <p id="statusAx" style="color: #E30000;">STOPPED</p></div>
            <div id="buttons">
            <button id="start" onclick="start(\''.$section.'\',\''.$plugin.'\');" >Start</button>
            <button id="stop" onclick="stop(\''.$section.'\',\''.$plugin.'\');" disabled>Stop</button>
        ';
        else $html.='/><p id="statusAx" style="color:#4BB007;margin-top: -28px;margin-left: 40px;">RUNNING</p> </div>
            <div id="buttons">
            <button id="start" onclick="start(\''.$section.'\',\''.$plugin.'\');" disabled>Start</button>
            <button id="stop" onclick="stop(\''.$section.'\',\''.$plugin.'\');" >Stop</button>
        ';

        $html.='</div><br></div>
        <div id="plugin_content_dtar">
            <h2>Development Target Properties</h2><br />
            <table style="width:500px"> 
            <tr>
                <td><b>Axeda Gateway Home: </b></td>
                <td>/mnt/usr/axeda/gateway/</td>
            </tr>
            <tr>
                <td><b>Meshlium Project directory: </b></td>
                <td>/mnt/usr/builder/MeshliumSample/</td>
            </tr>
            </table>
        </div>
        <div id="plugin_content" ><h2>ERemote Server</h2>
            <div id ="agentstatus"><img id="progress2" src="plugins/c_cloudPlatforms/progress.gif"';
        if ($act2 == null)   
            $html.='style="display:none;"/> <p id="statusAx2" style="color: #E30000;">STOPPED</p></div>
            <div id="buttons">
            <button id="start2" onclick="start2(\''.$section.'\',\''.$plugin.'\');" >Start</button>
            <button id="stop2" onclick="stop2(\''.$section.'\',\''.$plugin.'\');" disabled>Stop</button>
            </div>';
        else $html.='/><p id="statusAx2" style="color:#4BB007;margin-top: -28px;margin-left: 40px;">RUNNING</p> </div>
            <div id="buttons">
            <button id="start2" onclick="start2(\''.$section.'\',\''.$plugin.'\');" disabled>Start</button>
            <button id="stop2" onclick="stop2(\''.$section.'\',\''.$plugin.'\');" >Stop</button>
            </div>';
        $html.='</div>';

        // Accept rights

        $html.='  <div id="axedaTerms" style="position: fixed;
                                                width: 820px;
                                                height: 600px;
                                                top: 250px;
                                                left: auto;
                                                margin-left:-32px;
                                                z-index: 99999;
                                                background-color: rgba(0, 0, 0, 0.35);">
  <div class="dentro" style="top:30%;left:39%;z-index:99999;position:fixed"><br/>
  <div style="background-color:rgb(221, 247, 255); border: 2px dotted rgb(0, 67, 128); padding:8px; width:550px">
        <div align="justify"><b>PLEASE NOTE:</b> This software is protected by copyright laws and international copyright treaties 
        as well as other intellectual property laws and treaties.  ©Axeda Corporation 2001-2013.  
        All rights reserved.  This software is licensed not sold.  A license is not required to view the 
        Libelium-Axeda-Go connectivity demo at <a href="http://www.axeda.com/go-libelium" target="_blank">axeda.com/go-libelium</a>.   
        However, you are not authorized to use this software unless you (or the company for which you work 
        and have received your authorization from) is validly licensed to use this software either through 
        registering on the Axeda® Developer Connection at <a href="http://developer.axeda.com" target="_blank">developer.axeda.com</a> and accepting the Terms 
        & Conditions of Use posted on such site, or by purchasing a subscription to the Axeda® Cloud Service 
        from Axeda or an authorized Axeda reseller.  For information on subscription purchases, please contact 
        Axeda at <a href="http://www.axeda.com/about/contact" target="_blank">axeda.com/about/contact</a></div><br/>
      <input id="buttonCloseOK" type="button" value="OK" style="float:right; margin-right: 10px;margin-top:-9px" onclick="closeOK();">
    <br /></div></div></div></div>';

?>
