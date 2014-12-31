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

      $.ajax({
            type: "POST",
            url: "/ManagerSystem/plugins/b_SensorData/b0_capturer/php/check.php",
            data: "check=adv",
            success: function(datos){
                  var str=datos;
                  var n=str.split(" ");
                  $('#local_db').html(n[0]);
                  $('#local_sz').html(n[1]);
                  $('#local_table').html(n[2]);
                  $('#local_rw').html(n[3]);
                  $('#local_syc').html(n[4]);
                  $('#local_unsyc').html(n[5]);                  
                  $('#ext_db').html(n[6]);
                  $('#ext_sz').html(n[7]);
                  $('#ext_table').html(n[8]);
                  $('#ext_rw').html(n[9]);
            }
        });
}



var continous = 0;
var continousStoped = 0;
function showMeNow(section,plugin,nonStop,interval)
{
  if(nonStop== true) {
    if(continousStoped == 0){
      continous = 1;
      $('#showMeNowStop').show();
      $('#showMeNowStart').hide();
    }
  }else{
    continous = 0;
  }
  submit_data="section="+section+"&plugin="+plugin+"&type=showMeNow";
  notify("loadinfo.net.gif", "Loading...");
  $.ajax({
    type: "POST",
    url: "index.php",
    data: submit_data,
    success: function(datos){
     // $("#tab3contentScan").html(datos);
      fadenotify();
      setTimeout( function(){
        if(continous == 1){
          $("#tab3contentScan").html( datos+$("#tab3contentScan").html());
          showMeNow(section,plugin,nonStop,interval)
        }
        if (continous == 0){
          $("#tab3contentScan").html(datos);
        }
      }, interval*1000);
    }
  });

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
                        $("#error_sync").html(datos);
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
function clearALL(section,plugin)
{   
   if(confirm('Synchronized data of sensorParser table will be deleted.\n Do you want to continue?'))
    {
      notify("progress.gif", "Clear synchronized data");
      submit_data="section="+section+"&plugin="+plugin+"&type=clearALL";
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
function removeALL(section,plugin)
{
    if(confirm('All data of sensorParser table will be deleted.\n Do you want to continue?'))
    {
        
            document.body.style.cursor = 'wait';
            notify("progress.gif", "Remove ALL data.");
          
            submit_data="section="+section+"&plugin="+plugin+"&type=removeData";;
            $.ajax({
                   type: "POST",
                   url: "index.php",
                   data: submit_data,
                   success: function(datos){
                       if(datos=='-1')
                       {
                           endnotify();
                           notify("fail.png", "Unexpected error please try again.");
                           fadenotify();
                       }
                       else
                       {
                           document.body.style.cursor = 'default';
                           fadenotify();
                       }
                   }
                });
        
    }
}
$(document).ready(function() {

    //window.setInterval(function(){
        $.ajax({
            type: "POST",
            url: "/ManagerSystem/plugins/b_SensorData/b0_capturer/php/check.php",
            data: "check=sync",
            success: function(datos){
                $("#sync_dev").html(datos);
            }
        });

        $.ajax({
            type: "POST",
            url: "/ManagerSystem/plugins/b_SensorData/b0_capturer/php/check.php",
            data: "check=adv",
            success: function(datos){
                  var str=datos;
                  var n=str.split(" ");
                  $('#local_db').html(n[0]);
                  $('#local_sz').html(n[1]);
                  $('#local_table').html(n[2]);
                  $('#local_rw').html(n[3]);
                  $('#local_syc').html(n[4]);
                  $('#local_unsyc').html(n[5]);                  
                  $('#ext_db').html(n[6]);
                  $('#ext_sz').html(n[7]);
                  $('#ext_table').html(n[8]);
                  $('#ext_rw').html(n[9]);
            }
        });


  //  }, 10000);
});
