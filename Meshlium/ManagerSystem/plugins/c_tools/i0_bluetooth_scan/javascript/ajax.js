// This file is based on jquery ajax.
// You don't have to make use of jquery. You can use prototype, mootools or your
// own ajax call.
function complex_ajax_call(form_id,output_id,section,plugin,action)
{
    if(!ms_check_form_fields('hcid_configuration'))
    {
         document.body.style.cursor = 'wait';
        var json_field=json_encode(form_id);
        submit_data="section="+section+"&plugin="+plugin+"&type="+action+"&"+"form_fields="+json_field;
        notify("saving.png", "Saving data...");

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
}