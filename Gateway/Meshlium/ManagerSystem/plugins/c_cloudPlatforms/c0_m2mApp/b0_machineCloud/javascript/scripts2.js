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


var iteracion;
var result;

function nl2br (str, is_xhtml) {   
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
}

function getFile(){
    var file = "plugins/c_cloudPlatforms/b0_machineCloud/console/terminal";
    $.get(file, function(txt) { 
        $("#tab4contentScan").html( nl2br(txt)); 
        document.getElementById('tab4contentScan').scrollTop = 9999999;
    });
    iteracion = setTimeout(function() { getFile()}, 500);
}    

function saveAlert()
{
    notify("saving", "Saving data...");
}

function notify(icon, content)
{
    $("#notification").html("<img src='core/images/"+icon+"' style='float: left; margin-right: 15px;' />");
    $("#notification").append(content);
    $("#notification").show();
}

function endnotify()
{
    $("#notification").hide();
}

function fadenotify()
{
    setTimeout( function()
      {
         $("#notification").fadeOut(1000);
      }, 3000);
}

function start(section,plugin)
{
    $('#progress').fadeIn();
    $('#start').attr("disabled","disabled");
    $('#stop').removeAttr("disabled");
    $('#statusAx').html("RUNNING");
    $('#statusAx').css({"color":"#4BB007","margin-top": "-28px","margin-left": "40px"});
    submit_data="section="+section+"&plugin="+plugin+"&type=start";
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
        }
    });
}

function stop(section,plugin)
{
    $('#progress').fadeOut();
    $('#stop').attr("disabled","disabled");
    $('#start').removeAttr("disabled");
    $('#statusAx').html("STOPPED");
    $('#statusAx').css({"color":"#E30000","margin-top": "0px","margin-left": "0px"});
    submit_data="section="+section+"&plugin="+plugin+"&type=stop";
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
        }
    });
}

function start2(section,plugin)
{
    $('#progress2').fadeIn();
    $('#start2').attr("disabled","disabled");
    $('#stop2').removeAttr("disabled");
    $('#statusAx2').html("RUNNING");
    $('#statusAx2').css({"color":"#4BB007","margin-top": "-28px","margin-left": "40px"});
    submit_data="section="+section+"&plugin="+plugin+"&type=start2";
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
        }
    });

}

function stop2(section,plugin)
{
    $('#progress2').fadeOut();
    $('#stop2').attr("disabled","disabled");
    $('#start2').removeAttr("disabled");
    $('#statusAx2').html("STOPPED");
    $('#statusAx2').css({"color":"#E30000","margin-top": "0px","margin-left": "0px"});
    submit_data="section="+section+"&plugin="+plugin+"&type=stop2";
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
        }
    });

}

function closeOK()
{
    $('#axedaTerms').fadeOut();

}

