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

$(document).ready(function(){
    $("#add_sensor_button").click(function(e) {
        $.ajax({
            type: "post",
            url: "/ManagerSystem/plugins/b_SensorData/d0_sensors/php/check.php",
            data: "id="+$("#new_sensor_id").val()+"&fields="+$("#new_sensor_fields").val()+"&type="+$('#new_sensor_type').val(),
            success: function(data){
                $("#new_sensor_id").val("");
                $("#new_sensor_fields").val("");
                $.ajax({
                        type: "post",
                        url: "/ManagerSystem/plugins/b_SensorData/d0_sensors/php/check.php",
                        data: "refresh=1",
                        success: function(data){
                            $("#sensor_div_xml").html(data);           
                        }
                });
                $("#message_log").html(data);
                $("#message_log").show("fast").delay(5000).hide("slow");           
            }
        });
    });

    $(".delete_sensor_button").live('click',function(e){
        if(confirm('¿Are you sure you want to delete the sensor?')){
            $.ajax({
                type: "post",
                url: "/ManagerSystem/plugins/b_SensorData/d0_sensors/php/check.php",
                data: "deleteid="+$(this).attr('id'),
                success: function(data){

                    $.ajax({
                        type: "post",
                        url: "/ManagerSystem/plugins/b_SensorData/d0_sensors/php/check.php",
                        data: "refresh=1",
                        success: function(data){
                            $("#sensor_div_xml").html(data);           
                        }
                    });

                    $("#message_log").html(data);
                    $("#message_log").show("fast").delay(5000).hide("slow");           
                }
            });
        }
    });

    $(".delete_sensor_button").live('mouseover',function(e){
            $(this).attr("src","/ManagerSystem/plugins/b_SensorData/b0_capturer/images/delete_sensor_hover.png");
    });

    $(".delete_sensor_button").live('mouseout',function(e){
            $(this).attr("src","/ManagerSystem/plugins/b_SensorData/b0_capturer/images/delete_sensor.png");
    });

});

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


