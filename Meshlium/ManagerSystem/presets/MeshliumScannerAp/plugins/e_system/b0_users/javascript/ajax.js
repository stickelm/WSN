

function saveAlert()
{
    notify("saving", "Saving data...");
}

function notify(icon, content)
{
    $("#notification").html("<img src='core/images/"+icon+".png' style='float: left; margin-right: 15px;' />");
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
         $("#notification").fadeOut(3000);
      }, 3000);
}

function nv_ajax_call (section, plugin, action, form_id, params)
{
    document.body.style.cursor = 'wait';
    var submit_data  = "type=nv";
    submit_data += "&section="+section+"&plugin="+plugin;

    if (form_id != "")
    {
        json_field=json_encode(form_id);
        submit_data += "&form_id="+form_id+"&form_fields="+json_field;
    }

    if (action != '')
    {
        submit_data += "&action=" + action;
    }

    for (key in params)
    {
        submit_data += "&"+key+"="+params[key];
    }

    //alert (submit_data);
    saveAlert()
    $.ajax({
               type: "POST",
               url: "index.php",
               data: submit_data,
               success: execute_action
            });
}

function execute_action (response)
{
    document.body.style.cursor = 'default';
    // A JSON array is expected
    var ret = eval('(' + response + ')');

    $.each(ret.item, function(i,item){
      if (item['type']=="script")
      {
          eval(item['value']);
      }
      else if (item['type']=="html")
      {
          $('#'+item['id']).html(item['value']);
      }
      else if (item['type']=="value")
      {
          $('#'+item['id']).val(item['value']);
      }
      else if (item['type']=="append")
      {
          $('#'+item['id']).append(item['value']);
      }
      else if (item['type']=="remove")
      {
          $('#'+item['id']).remove();
      }
    });
}

function changeMysqlPass(section, plugin, form_id)
{
    if(!ms_check_form_fields('mysql_form'))
    {
        document.body.style.cursor = 'wait';
        submit_data = "type=mysqlpass&section="+section+"&plugin="+plugin;
        json_field=json_encode(form_id);
        submit_data += "&form_id="+form_id+"&form_fields="+json_field;
        saveAlert()
        $.ajax({
               type: "POST",
               url: "index.php",
               data: submit_data,
               success:  function(datos){

                        endnotify();
                        notify("icono-i", datos);
                        fadenotify();
                    }
                });
    }
}