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


    $html=' 
            <script type="text/javascript">
                $(function() {
                    $("#maps").hover( function() {
                        var $this = $(this);
                        $("#arcgisready").css("-webkit-filter","grayscale(0%)");
                    },
                    function() {
                        var $this = $(this);
                        $("#arcgisready").css("-webkit-filter","grayscale(100%)");
                    });
                    
                    $("#mqtt").hover( function() {
                        var $this = $(this);
                        $("#mqttready").css("-webkit-filter","grayscale(0%)");
                    },
                    function() {
                        var $this = $(this);
                        $("#mqttready").css("-webkit-filter","grayscale(100%)");
                    });
                    
                    $("#m2m").hover( function() {
                        var $this = $(this);
                        $("#axedaready").css("-webkit-filter","grayscale(0%)");
                        $("#thingworxready").css("-webkit-filter","grayscale(0%)");
                    },
                    function() {
                        var $this = $(this);
                       $("#axedaready").css("-webkit-filter","grayscale(100%)");
                       $("#thingworxready").css("-webkit-filter","grayscale(100%)");
                    });

                });
            </script>

            <style type="text/css">
                #cloud_sections {
                    height: 480px;
                    width: 770px;
                    background-image: url("plugins/c_cloudPlatforms/cloud_def.png");
                    background-repeat:no-repeat;
                    background-position:left; 
                    background-color:#eee;
                    margin-left:210px;
                    margin-top:12px;
                    -moz-border-radius-bottomleft: 5px;
                    -moz-border-radius-bottomright: 5px;
                    -moz-border-radius-topleft: 5px;
                    -moz-border-radius-topright: 5px;
                    -webkit-border-radius: 10px;
                    -opera-border-radius: 10px;
                }
                .icon_peq{
                    -moz-border-radius-bottomleft: 9px;
                    -moz-border-radius-bottomright: 9px;
                    -moz-border-radius-topleft: 9px;
                    -moz-border-radius-topright: 9px;
                    -webkit-border-radius: 18px;
                    -opera-border-radius: 18px;
                    border: 2px solid #939598;
                    background-size:cover;
                    /*box-shadow: 2px 3px 15px #888888;*/
                }
                #maps {
                    background-image:url("plugins/c_cloudPlatforms/a0_map/images/maps_peq.png");
                    width: 78px;height:54px;
                    position:absolute;
                    top:300px;
                    left:500px;
                }
                #mqtt {
                    background-image:url("plugins/c_cloudPlatforms/d0_mqtt/images/mqtt_peq.png");
                    width: 78px;height:54px;
                    position:absolute;
                    top:386px;
                    left:380px;
                }
                #m2m {
                    background-image:url("plugins/c_cloudPlatforms/c0_m2mApp/images/thingworx_peq.png");
                    width: 78px;height:54px;
                    position:absolute;
                    top:406px;
                    left:576px;
                }
                .bubble {
                    width: 100px;
                    height: 30px;
                    font-size:13px;
                    font-weight: bold;
                    line-height: 30px;
                    text-align: center;
                    vertical-align: middle;
                    color: #4682B4;
                    background-color: #c8def9;
                    border: 2px dotted #4682B4;
                    -moz-border-radius-bottomleft: 5px;
                    -moz-border-radius-bottomright: 5px;
                    -moz-border-radius-topleft: 5px;
                    -moz-border-radius-topright: 5px;
                    -webkit-border-radius: 10px;
                    -opera-border-radius: 10px;                   
                }
                #title_cp {
                    padding-top:15px;
                    font: 35px Myriad pro,Arial;
                    color:#286394;
                    /*text-shadow: 4px 4px 2px #ccc;*/
                    font-weight: bold;
                    text-align: left;
                    margin-left: 122px;
                }
                #title_cp:hover {
                    color:#444;
                }
                #info {
                    position: absolute;
                    top: 580px;
                    right: 59px;
                    font: 20px Myriad pro, sans;
                    color: #444;
                }
                #info2 {
                    display:none;
                    position: absolute;
                    top: 591px;
                    left: 225px;
                    font: 12px Myriad pro, sans;
                    color: #444;
                }
                #arcgisready{
                    background-image:url("plugins/c_cloudPlatforms/esri_web.png");
                    width: 127px;height:34;
                    position:absolute;
                    top:167px;
                    right:53px;
                    -webkit-filter:grayscale(100%);
                }
                #axedaready{
                    background-image:url("plugins/c_cloudPlatforms/axeda_web.png");
                    width: 127px;height:34;
                    position:absolute;
                    top:227px;
                    right:53px;
                    -webkit-filter:grayscale(100%);
               }
                #thingworxready{
                    background-image:url("plugins/c_cloudPlatforms/thingworx_web.png");
                    width: 127px;height:34;
                    position:absolute;
                    top:287px;
                    right:53px;
                    -webkit-filter:grayscale(100%);
                }
                #mqttready{
                    background-image:url("plugins/c_cloudPlatforms/mqtt_web.png");
                    width: 127px;height:34;
                    position:absolute;
                    top:347px;
                    right:53px;
                    -webkit-filter:grayscale(100%);
                }
            </style>
            
            <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Ultra">
            
            <div id="cloud_sections">
                <div id="title_cp">CLOUD CONNECTOR</div>
                <div id="info">Click on a plugin to load</div>
                <a href="index.php?section=c_cloudPlatforms&plugin=a0_map"> 
                    <div class="icon_peq" id="maps" alt="MAPS"></div>
                </a>
                <a href="index.php?section=c_cloudPlatforms&plugin=c0_m2mApp"> 
                    <div class="icon_peq" id="m2m" alt="M2M PLATFORM"></div>
                </a>
                <a href="index.php?section=c_cloudPlatforms&plugin=d0_mqtt">
                    <div class="icon_peq" id="mqtt" alt="MQTT SOLUTIONS"></div>
                </a>
                <div id="arcgisready"></div><div id="axedaready"></div><div id="thingworxready"></div><div id="mqttready"></div>
                <div id="info2">To ensure proper operation, we recommend to use <a target="_blank" href="http://www.google.com/chrome">Google Chrome</a> browser.</div>
            </div><br/>
            ';

?>
