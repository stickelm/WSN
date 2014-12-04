/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
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

function startTW(section,plugin)
{
    $('#progressTW').fadeIn();
    $('#startTW').attr("disabled","disabled");
    $('#stopTW').removeAttr("disabled");
    $('#statusTW').html("RUNNING");
    $('#statusTW').css({"color":"#4BB007","margin-top": "-28px","margin-left": "40px"});
    submit_data="section="+section+"&plugin="+plugin+"&type=startTW";
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
        }
    });
}

function stopTW(section,plugin)
{
    $('#progressTW').fadeOut();
    $('#stopTW').attr("disabled","disabled");
    $('#startTW').removeAttr("disabled");
    $('#statusTW').html("STOPPED");
    $('#statusTW').css({"color":"#E30000","margin-top": "0px","margin-left": "0px"});
    submit_data="section="+section+"&plugin="+plugin+"&type=stopTW";
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
        }
    });
}