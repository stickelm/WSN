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

function start(section,plugin)
{
    submit_data="section="+section+"&plugin="+plugin+"&type=start";
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
            fadenotify();
        }
    });

}

function stop(section,plugin)
{
    submit_data="section="+section+"&plugin="+plugin+"&type=stop";
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
            fadenotify();
        }
    });

}

function remove(section,plugin)
{
    submit_data="section="+section+"&plugin="+plugin+"&type=remove";
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
            fadenotify();
        }
    });
}

function syncmqtt(section,plugin)
{
    notify("saving", "Marking sensor entries as MQTT synchronized");
    submit_data="section="+section+"&plugin="+plugin+"&type=syncmqtt";
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
            fadenotify();
        }
    });
}

function save(form_id,output_id,section,plugin)
{
    var json_field=json_encode(form_id);
    submit_data="section="+section+"&plugin="+plugin+"&type=save&form_fields="+json_field;
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
            notify("saving", "Server/Broker configuration saved");
            fadenotify();
        }
    });
}
