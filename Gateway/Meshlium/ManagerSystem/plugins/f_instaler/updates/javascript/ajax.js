
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


function installUpdate(section,plugin,libupd)
{
    submit_data="section="+section+"&plugin="+plugin+"&libupd="+libupd+"&type=installUpdate";
    notify("loadinfo.net.gif", "Loading...");
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
            notify("icono-i.png", datos);
            $("#resultsOfLoad").html("");
            checkForUpdates(section,plugin);
            fadenotify();
        }
    });
}


function checkForUpdates(section,plugin)
{
    submit_data="section="+section+"&plugin="+plugin+"&type=checkForUpdates";
    notify("loadinfo.net.gif", "Loading...");
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
            //notify("icono-i.png", datos);
            fadenotify();
            $("#resultsForCheck").html(datos);
        }
    });
}

function downloadUpdate(section,plugin,who,linke)
{
    $(".installSMS").each(function(){ $(this).hide(); });
    submit_data="section="+section+"&plugin="+plugin+"&type=downloadUpdate"+"&link="+linke;
    $("#loading_"+who).show();
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
            $("#loading_"+who).hide();
            if (datos == 0)
            {
               $("#notinstall_"+who).show();
            }
            else if (datos == 1)
            {
               $("#install_"+who).show();
            }
        }
    });
}