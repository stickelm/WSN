
function complex_ajax_call(output_id,section,plugin)
{
    // This script will serialize the form indicated by an id and submit it
    // to the desired page.
    // Once the response has arrived it display the response inside the id
    // defined in output_id
    document.body.style.cursor = 'wait';
    submit_data="section="+section+"&plugin="+plugin+"&type=complex";
    //alert (submit_data);

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
                else if (item['type']=="clean")
                {
                    $('#'+item['id']).append(item['value']);
                }
            });
            ////////////////////////////////////////////////////////
            if (recargame)
            {
                setTimeout("complex_ajax_call('"+output_id+"','"+section+"','"+plugin+"')", reloadtime );
                load_data(section,plugin);
            }
            else
            {
                $('#output').html('');
            }

          
            $('span:tempe').each(function() {
                _valor = explode('+', $(this).html());
                // alert(_valor['0']);alert(_valor['1']);
                valor = explode('.', _valor['1']);
                //alert (valor['0']);
                if (valor['0'] <= 5)
                {
                    $(this).addClass("font_green");
                }
                else if (valor['0'] <= 10)
                {
                    $(this).addClass("font_yellow");
                }
                else 
                {
                    $(this).addClass("font_red");
                }
            });
          
        }
    });
}