
function saveAlert()
{
    notify("saving", "New configuration is being saved<br><br>Please wait...");
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
    $("#notification").fadeOut(3000);
}

function setPresetAjax(type)
{
    document.body.style.cursor = 'wait';
    notify('saving.png', 'Setting Meshlium as '+type);
    submit_data="set="+type;
    $.ajax({
        type: "POST",
        url: "core/structure/presets/setPresets.php",
        data: submit_data,
        success: function(datos){
            $('#preset_content').html(datos);
            document.body.style.cursor = '';
            endnotify();
            notify('save.png', 'Meshlium setted');
            fadenotify();
            if (type == 'meshlium_3G_ap' || type == 'meshlium_mesh_ap_3G_gw')
            {
                location.href="index.php?section=a_interfaces&plugin=d0_gprs";
            }
        }
    });
}

