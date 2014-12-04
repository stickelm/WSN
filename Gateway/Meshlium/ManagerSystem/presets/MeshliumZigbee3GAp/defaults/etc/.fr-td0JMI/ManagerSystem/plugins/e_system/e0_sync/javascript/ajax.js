var anoBisiesto = false;
var mesCorto = false;
var mesFebrero = false;

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
    error = false;

    if($("#s_year").val() == "year")
    {
        error = true;
        $("#s_year").addClass("error");
    }
    if($("#s_mounth").val() == "mounth")
    {
        error = true;
        $("#s_mounth").addClass("error");
    }
    if($("#s_day").val() == "day")
    {
        error = true;
        $("#s_day").addClass("error");
    }
    if($("#s_hour").val() == "hour")
    {
        error = true;
        $("#s_hour").addClass("error");
    }
    if($("#s_minute").val() == "minute")
    {
        error = true;
        $("#s_minute").addClass("error");
    }

    if(error == true)
    {
        notify("fail", "Data missed");
        fadenotify();
    }
    else
    {
        document.body.style.cursor = 'wait';
        var json_field=json_encode(form_id);
        submit_data="section="+section+"&plugin="+plugin+"&action="+action+"&form_fields="+json_field;
        saveAlert()
        $.ajax({
           type: "POST",
           url: "index.php",
           data: submit_data,
           success: function(datos){
              document.body.style.cursor = 'default';

              endnotify();
              notify("save", "Data saved.")
              fadenotify();
              $("#current_date").html(datos);
           }
        });
    }
}

function yearLogic()
{
    if( ($("#s_year").val() == '2012') || ($("#s_year").val() == '2016') || ($("#s_year").val() == '2020') || ($("#s_year").val() == '2024') || ($("#s_year").val() == '2028') || ($("#s_year").val() == '2032') || ($("#s_year").val() == '2036') || ($("#s_year").val() == '2040') )
    {
        anoBisiesto = true;
    }
    if($("#s_year").val() != 'year')
    {
        $("#s_mounth").removeClass("disabled");
        $("#s_mounth").removeAttr("disabled");
    }
    if(($("#s_year").val() == 'year') && (!$("#s_mounth").hasClass("disabled")))
    {
        $("#s_mounth").addClass("disabled");
        $("#s_mounth").attr("disabled", true);
    }
    $("#s_mounth").val("mounth");
    $("#s_day").val("day");
    if(!$("#s_day").hasClass("disabled"))
    {
        $("#s_mounth").addClass("error");
        $("#s_day").addClass("error");
        setTimeout( function()
          {
             $("#s_mounth").removeClass("error");
             $("#s_day").removeClass("error");
          }, 400);
        setTimeout( function()
          {
             $("#s_mounth").addClass("error");
             $("#s_day").addClass("error");
          }, 800);
        setTimeout( function()
          {
             $("#s_mounth").removeClass("error");
             $("#s_day").removeClass("error");
          }, 1200);
        setTimeout( function()
          {
             $("#s_mounth").addClass("error");
             $("#s_day").addClass("error");
          }, 1600);
        setTimeout( function()
          {
             $("#s_mounth").removeClass("error");
             $("#s_day").removeClass("error");
          }, 2000);
    }
    
}

function mounthLogic()
{
    if ( ($("#s_mounth").val() == '04') || ($("#s_mounth").val() == '06') || ($("#s_mounth").val() == '09') || ($("#s_mounth").val() == '11') )
    {
        $('#s_day :option').each( function() {
            if (($(this).hasClass("largo")))
            {
                $(this).hide();
            }
        });
    }
    else
    {
        $('#s_day :option').each( function() {

                $(this).show();
        });

    }
    if ($("#s_mounth").val() == '02')
    {
        $('#s_day :option').each( function() {
            if (($(this).hasClass("febrero")))
            {
                $(this).hide();
            }
            if (($(this).hasClass("bisiesto")) && (anoBisiesto == true))
            {
                $(this).show();
            }
        });
    }
    if($("#s_mounth").val() != 'mounth')
    {
        $("#s_day").removeClass("disabled");
        $("#s_day").removeAttr("disabled");
    }
    if(($("#s_mounth").val() == 'mounth') && (!$("#s_day").hasClass("disabled")))
    {
        $("#s_day").addClass("disabled");
        $("#s_day").attr("disabled", true);
    }
}

function dayLogic()
{
    if($("#s_day").val() != 'day')
    {
        $("#s_hour").removeClass("disabled");
        $("#s_hour").removeAttr("disabled");
    }
    if(($("#s_day").val() == 'day') && (!$("#s_hour").hasClass("disabled")))
    {
        $("#s_hour").addClass("disabled");
        $("#s_hour").attr("disabled", true);
    }
}

function hourLogic()
{
    if($("#s_hour").val() != 'hour')
    {
        $("#s_minute").removeClass("disabled");
        $("#s_minute").removeAttr("disabled");
    }
    if(($("#s_hour").val() == 'hour') && (!$("#s_minute").hasClass("disabled")))
    {
        $("#s_minute").addClass("disabled");
        $("#s_minute").attr("disabled", true);
    }
}
