

function saveAlert()
{
    notify("saving.png", "Saving data...");
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
         $("#notification").fadeOut(1200);
      }, 3000);
}

function save(form_id,output_id,section,plugin,xbee)
{
    if(confirm('During this proccess the zigbee frames storage will be stopped.\n Do you want to continue?'))
    {
        if(!ms_check_form_fields("xbee_configuration"))
        {
            document.body.style.cursor = 'wait';
            var json_field=json_encode(form_id);
            submit_data="section="+section+"&plugin="+plugin+"&action=save&form_fields="+json_field+"&xbee="+xbee;
            saveAlert();
            $.ajax({
                       type: "POST",
                       url: "index.php",
                       data: submit_data,
                       success: function(datos){
                              document.body.style.cursor = 'default';
                               // A JSON array is expected
                              var ret = eval('(' + datos + ')');
                              $.each(ret.item, function(i,item){
                                  if (item['type']=="script")
                                  {
                                      eval(item['value']);
                                  }
                                  else if (item['type']=="return")
                                  {
                                      $('#'+output_id).html(item['value']);
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
                              });
                              endnotify();
                              notify("save.png", "Data saved");
                              fadenotify()
                                  setTimeout( function()
                                  {
                                    checkStatus(form_id,section,plugin,xbee);
                                  }, 1500);

                       }
                    });
        }
    }
}

function getMacs(section,plugin)
{
    if(confirm('During this proccess the zigbee frames storage will be stopped.\n Do you want to continue?'))
    {
        document.body.style.cursor = 'wait';
        notify("loadinfo.net.gif", "Please wait ...");
        submit_data="section="+section+"&plugin="+plugin+"&action=getmacs";
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
                       brokenstring=datos.split("#");
                       $("#atsh").val(brokenstring[0]);
                       $("#atsl").val(brokenstring[1]);
                       document.body.style.cursor = 'default';
                       fadenotify();
                   }
               }
            });
    }
}

function checkStatus(form_id,section,plugin,xbee)
{
    if(confirm('During this proccess the zigbee frames storage will be stopped.\n Do you want to continue?'))
    {
        if(!ms_check_form_fields("xbee_configuration"))
        {
            document.body.style.cursor = 'wait';
            notify("loadinfo.net.gif", "Please wait ...");
            var json_field=json_encode(form_id);
            submit_data="section="+section+"&plugin="+plugin+"&action=check&form_fields="+json_field+"&xbee="+xbee;
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
                           $("#checking").html(datos);
                           document.body.style.cursor = 'default';
                           fadenotify();
                       }
                   }
                });
        }
    }
}
