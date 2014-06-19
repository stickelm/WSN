/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery.fn.extend({
    delay: function( time, type ) {
        time = jQuery.fx ? jQuery.fx.speeds[time] || time : time;
        type = type || "fx";

        return this.queue( type, function() {
            var elem = this;
            setTimeout(function() {
                jQuery.dequeue( elem, type );
            }, time );
        });
    }
});

function initUpload() {

   /*set the target of the form to the iframe and display the status
      message on form submit.
  */
    document.getElementById('uploadform').onsubmit=function() {
    document.getElementById('uploadform').target = 'target_iframe';
    document.getElementById('status').style.display="block"; 

    }
}

//This function will be called when the upload completes.
function uploadComplete(status){
   //set the status message to that returned by the server
   document.getElementById('status').innerHTML=status;
}
function uploadComplete1(status){
   //set the status message to that returned by the server
   document.getElementById('show_file').innerHTML=status;
}

window.onload=initUpload;



$("#no_file_check").click(function(){
    if (this.checked){
        $("#file_check").attr("disabled", true);
        $("#file_exist_check").attr("disabled", true);
        $("#file").attr("disabled", true);
       

        $.ajax({
            type: "POST",
            url: "/ManagerSystem/plugins/b_SensorData/f0_otaftp/php/check.php",
            data: "do=no_file",
            success: function(datos){
            
            
        }
    });  
    }else{
       $("#file_check").removeAttr("disabled");
       $("#file").removeAttr("disabled");
       $("#file_exist_check").removeAttr("disabled");
    }
});

$("#file_check").click(function(){
    if (this.checked){
        $("#no_file_check").attr("disabled", true);
        $("#file_exist_check").attr("disabled", true);
        $("#version").attr("style","display:block");
    }else{
        $("#version").attr("style","display:none");
       $("#no_file_check").removeAttr("disabled");
       $("#file_exist_check").removeAttr("disabled");
    }
});
$("#file_exist_check").click(function(){
    if (this.checked){
        $("#no_file_check").attr("disabled", true);
        $("#file_check").attr("disabled", true);
        $("#file").attr("disabled", true);
        $("#version").attr("style","display:block");
        $("#list_abinary").attr("style","width:317px; padding-left:100px;display:block;");
      
    }else{
       $("#no_file_check").removeAttr("disabled");
       $("#version").attr("style","display:none");
       $("#file_check").removeAttr("disabled");
       $("#file").removeAttr("disabled");
       $("#list_abinary").attr("style","width:317px; padding-left:100px;display:none;");
    }
});


function upload(file)
{
  submit_data="do=upload&file="+file;
    notify("loadinfo.net.gif", "Loading...");
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
            fadenotify();
            //$("#resultsForUpdate").html(datos);
        }
    });  
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


function checkForUpdate(section,plugin)
{
    submit_data="section="+section+"&plugin="+plugin+"&type=checkForUpdate";
    notify("loadinfo.net.gif", "Loading...");
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
            fadenotify();
            $("#resultsForUpdate").html(datos);
        }
    });
}



function startZigbeeStorerDaemon(section,plugin)
{
    submit_data="section="+section+"&plugin="+plugin+"&type=startZigbeeStorerDaemon";
    notify("loadinfo.net.gif", "Loading...");
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
            if(datos == 1)
               $("#daemonStatus").html("<div id='dRunning'></div> <span> <b>Daemon running</b></span>");
            fadenotify();
        }
    });
}


var lastSelectedItem;
var currentTab = "tab1";

function loadTab(tab)
{
    $("#"+currentTab).removeClass("selectedTab");
    $("#"+tab).addClass("selectedTab");
    $("#"+currentTab+"content").hide();
    $("#"+tab+"content").show();
    currentTab = tab;
}

$(document).ready(function() {

    window.setInterval(function(){
        $.ajax({
            type: "POST",
            url: "/ManagerSystem/plugins/b_SensorData/f0_otaftp/php/check.php",
            data: "do=refres",
            success: function(datos){
                $("#show_file").html(datos);
            }
        });
       
    }, 500);
});
