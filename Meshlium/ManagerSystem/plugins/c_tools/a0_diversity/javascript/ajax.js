
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

function complex_ajax_call(form_id,output_id,section,plugin,action)
{
    saveAlert();
    document.body.style.cursor = 'wait';
    var json_field=json_encode(form_id);
    var inter=$('#interface_selector').val();
    submit_data="section="+section+"&plugin="+plugin+"&action="+action+"&interface="+inter+"&form_fields="+json_field;
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
               }
            });
}