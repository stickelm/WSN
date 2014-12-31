/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

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



var continous = 0;
var continousStoped = 0;
function showMeNow(section,plugin)
{
    
    if(!is_numerical("#intervalForNow"))
    {
        notify("fail.png", "Invalid number.");
        fadenotify();
    }
    else
    {
        if(continousStoped == 0)
        {
            interval = $('#intervalForNow').val();
            continous = 1;

            $('#showMeNowStop').show();
            $('#showMeNowStart').hide();

            submit_data="section="+section+"&plugin="+plugin+"&type=showMeNow";
            notify("loadinfo.net.gif", "Loading...");
            $.ajax({
                type: "POST",
                url: "index.php",
                data: submit_data,
                success: function(datos){
                    $("#tab4contentScan").html(datos);
                    fadenotify();
                    if(continous == 1)
                    {
                      setTimeout( function()
                      {
                         $("#tab4contentScan").html("");
                         showMeNow(section,plugin)
                      }, interval*1000);
                    }
                }
            });
        }
    }
}

function stopMeNow(section,plugin)
{
    continousStoped = 0;
    $('#showMeNowStart').show();
    $('#showMeNowStop').hide();
    continous=0;
}

function showlocalDB(section,plugin,num)
{
    if(!is_numerical("#localDbNumerToShow"))
    {
        notify("fail.png", "Invalid number.");
            fadenotify();
    }
    else
    {
        submit_data="section="+section+"&plugin="+plugin+"&type=showlocalDB&num="+num;
        notify("loadinfo.net.gif", "Loading...");
        $.ajax({
            type: "POST",
            url: "index.php",
            data: submit_data,
            success: function(datos){
                $("#localDataViewer").html(datos);
                fadenotify();
            }
        });
    }
}

function showSqlScript(section,plugin)
{
    submit_data="section="+section+"&plugin="+plugin+"&type=showSqlScript";
    notify("loadinfo.net.gif", "Loading...");
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
            $("#extDataViewer").html(datos);
            fadenotify();
        }
    });
}

function useLocalDB(section,plugin, checkbox)
{
    submit_data="section="+section+"&plugin="+plugin+"&type=useLocalDB"+"&state="+checkbox;
    notify("loadinfo.net.gif", "Loading...");
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
            if(checkbox == 'on')
            {
                $("#localDBRunning").show();
            }
            else
            {
                $("#localDBRunning").hide();
            }
            fadenotify();
        }
    });
}

function useExtDB(section,plugin, checkbox,time)
{
    submit_data="section="+section+"&plugin="+plugin+"&type=useExtDB"+"&state="+checkbox+"&time="+time;
    notify("loadinfo.net.gif", "Loading...");
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
            if(checkbox == 'on')
            {
                $("#extDBRunning").show();
            }
            else
            {
                $("#extDBRunning").hide();
            }
            fadenotify();
        }
    });
}
function synchronize(section,plugin)
{
    submit_data="section="+section+"&plugin="+plugin+"&type=synchronize";
    notify("loadinfo.net.gif", "Synchronizing...");
        $.ajax({
                   type: "POST",
                   url: "index.php",
                   data: submit_data,
                   success: function(datos){
                      
                        fadenotify();
                   }
                });
}
function checkConnection(section,plugin,formId)
{
    if(!ms_check_form_fields())
    {
        var json_field=json_encode(formId);
        submit_data="section="+section+"&plugin="+plugin+"&type=checkConnection&"+"form_fields="+json_field;
        notify("loadinfo.net.gif", "Loading...");
        $.ajax({
                   type: "POST",
                   url: "index.php",
                   data: submit_data,
                   success: function(datos){
                        $("#extDataViewer").html(datos);
                        fadenotify();
                   }
                });
    }
}
function saveDataConnection(section,plugin,formId)
{
    if(!ms_check_form_fields())
    {
        var json_field=json_encode(formId);
        submit_data="section="+section+"&plugin="+plugin+"&type=saveDataConnection&"+"form_fields="+json_field;
        notify("loadinfo.net.gif", "Saving data...");
        $.ajax({
                   type: "POST",
                   url: "index.php",
                   data: submit_data,
                   success: function(datos){
                        fadenotify();
                   }
                });
    }
}

function showextDB(section,plugin,formId,num)
{
    if(!is_numerical("#extDbNumerToShow"))
    {
        notify("fail.png", "Invalid number.");
            fadenotify();
    }
    else
    {
        var json_field=json_encode(formId);
        submit_data="section="+section+"&plugin="+plugin+"&type=showextDB&"+"form_fields="+json_field+"&num="+num;
        notify("loadinfo.net.gif", "Loading...");
        $.ajax({
            type: "POST",
            url: "index.php",
            data: submit_data,
            success: function(datos){
                $("#extDataViewer").html(datos);
                fadenotify();
            }
        });
    }
}
function clearAllData(section,plugin)
{   
    notify("loadinfo.net.png", "Remove ALL data.");
    submit_data="section="+section+"&plugin="+plugin+"&type=clearAllData";
     $.ajax({
            type: "POST",
            url: "index.php",
            data: submit_data,
            success: function(datos){
                fadenotify();
            }
        });
}

$(document).ready(function() {

    window.setInterval(function(){
        $.ajax({
            type: "POST",
            url: "/ManagerSystem/plugins/b_SensorData/c0_logs/php/check.php",
            data: "check=frame",
            success: function(datos){
                $("#frame_div").html(datos);
            }
        });
        $.ajax({
            type: "POST",
            url: "/ManagerSystem/plugins/b_SensorData/c0_logs/php/check.php",
            data: "check=sensor",
            success: function(datos){
                $("#sensor_div").html(datos);
            }
        });
    }, 1000);
});