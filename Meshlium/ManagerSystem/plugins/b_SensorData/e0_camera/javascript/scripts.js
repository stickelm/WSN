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

function download(filename) {

    $.ajax({
      type: "POST",
      url: "/ManagerSystem/plugins/b_SensorData/e0_camera/php/download.php",
      data: "do=download&file="+filename
    });

}


$(document).ready(function() {
  /**
   * [basename description]
   * @param  {[type]} path
   * @return {[type]}
   */
  function basename(path) {
    return path.replace(/\\/g,'/').replace( /.*\//, '' );
  }

  /* Carga asincrona de texto en el visor */
  function visor_text(txt, padding, ancho) {

    if (padding == undefined) padding = true;

    $('#visor').remove();
    $('#visor_tmp').remove();
    $('body').css('overflow', 'hidden').append('<div id="visor_tmp"></div><div id="visor"><div id="visor_border"><div id="visor_close">&#10005;</div><div id="visor_content"></div></div></div>');


    if (!padding) { $('#visor_content').css('padding',0); $('#visor_tmp').css('padding',0); }

    if (ancho != undefined) {
      $('#visor_close').css('margin-left', ancho);
      $('#visor_border').width(ancho+2);
    }

    $('#visor').height($(document).height());
    $('#visor_content').hide();
    $('#visor_border').css(
      {'margin-top':($(window).height()-$('#visor_border').height()-25)/2, 'position': 'fixed', 'margin-left': ($(window).width()-$('#visor_border').width())/2});


    $('#visor_tmp').append(txt);
    
    var height = ($('#visor_tmp').height()+20  > 0.8*$(window).height())? 0.8*$(window).height() : $('#visor_tmp').height()+((!padding)? 15 : 30);

    if ($('#visor_tmp').height()+20  <= 0.8*$(window).height()) $('#visor_content').css('overflow','hidden');

    $('#visor_tmp').remove();
    $('#visor_border').css({'position': 'fixed', 'margin-left': ($(window).width()-$('#visor_border').width())/2 }).animate({'margin-top':($(window).height()-height-25)/2,'height': height-((!padding)? 28 : 0)}, '', '', function() { $('#visor_close').fadeIn() });

    $('#visor_content').height(height-34).html(txt).fadeIn(1500);   
    $('#visor_close').click(function() { $('body').css('overflow','visible'); $('#visor').remove() });
  }

    $.ajax({
      type: "POST",
      url: "/ManagerSystem/plugins/b_SensorData/e0_camera/php/check.php",
      data: "do=fotos",
      success: function(datos){
              $("#total_fotos").html(datos);
           }
         });

    $.ajax({
      type: "POST",
      url: "/ManagerSystem/plugins/b_SensorData/e0_camera/php/check.php",
      data: "do=usage",
      success: function(datos){
        $("#data_table").html(datos);
        $("#data_table_videos").html(datos);
      }
    });
    $.ajax({
      type: "POST",
      url: "/ManagerSystem/plugins/b_SensorData/e0_camera/php/check.php",
      data: "do=videos",
      success: function(datos){
        $("#total_videos").html(datos);
      }
 });
        $.ajax({
             type: "POST",
             url: "/ManagerSystem/plugins/b_SensorData/e0_camera/php/check.php",
             data: "do=foto_files",
             dataType: 'json', // Choosing a JSON datatype
             success: function(data){

                var image_path= "/ManagerSystem/plugins/b_SensorData/e0_camera/data/camera/";

                $('#slider_foto').empty();
                
                for (var i=1; i< data.length; i++) {
                   if (/.(jpg)$/i.test(basename(data[i]))){
                  var name = data[i].split('_');
                  var name_aux = name[2];
                  var nombreArchivo=name_aux.substring(0,name_aux.lastIndexOf("."));

                  $('#slider_foto').append('<div style="width:215px"><img src="'+image_path+data[i]+'" width="190" height="130"/><br><b style="padding-left: 10%;">Waspmote: '+name[1]+'</b><br><b style="padding-left: 10%;">Date: '+nombreArchivo+'</b></div>');
                  }
                }

                $('#slider_foto div img').click(function() { visor_text('<img src="'+$(this).attr('src')+'" width="640" height="480" />', false, 640); });
                
                for(i=0;i<3;i++) {
                  $('#slider_foto div:first-child img').fadeTo('', 0.2).fadeTo('', 1.0);
                }
              }
        });

        $.ajax({
             type: "POST",
             url: "/ManagerSystem/plugins/b_SensorData/e0_camera/php/check.php",
             data: "do=video_files",
             dataType: 'json', // Choosing a JSON datatype
             success: function(data){

                var image_path= "/ManagerSystem/plugins/b_SensorData/e0_camera/data/camera/";

                $('#slider_video').empty();

                 for (var i=1; i< data.length; i++) {
                   if (/.(mp4)$/i.test(basename(data[i]))){
                     var name = data[i].split('_');
                     var name_aux = name[2];
                     var nombreArchivo=name_aux.substring(0,name_aux.lastIndexOf("."));
                     $('#slider_video').append('<div style="width:215px"><a href="'+image_path+data[i]+'" id="video_'+i+'" class="video"><img src="plugins/b_SensorData/e0_camera/images/video.png" style="width: 200px;"/></a><br><b style="padding-left: 15%;">Waspmote: '+name[1]+'</b><br><b style="padding-left: 15%;">Date: '+nombreArchivo+'</b></div>');
                   }
                 }
                  $('.video').click(function() { 
                    visor_text('<div style="width:640px;height:500px"><embed width="640" height="480" name="plugin" src="'+$(this).attr('href')+'" type="video/mp4"></embed></div>', false, 640); 
                    return false;

                  });

                for(i=0;i<3;i++) {
                  $('#slider_video div:first-child img').fadeTo('', 0.2).fadeTo('', 1.0);
                }
              }
              
        });


    
});
