
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
    setTimeout( function()
      {
         $("#notification").fadeOut(3000);
      }, 3000);
}

function setPresetAjax(type)
{
    document.body.style.cursor = 'wait';

    switch (type) {
        case "meshlium_ap":
            notify('saving.png', 'Setting Meshlium as Meshlium AP');
            break;
        case "meshlium_gprs_ap":
            notify('saving.png', 'Setting Meshlium as Meshlium GPRS AP');
            break;
        case "meshlium_mesh_ap":
            notify('saving.png', 'Setting Meshlium as Meshlium Mesh AP');
            break;
        case "meshlium_mesh_ap_gw":
            notify('saving.png', 'Setting Meshlium as Meshlium Mesh AP GW');
            break;
        case "meshlium_mesh_ap_gprs_gw":
            notify('saving.png', 'Setting Meshlium as Meshlium Mesh AP GPRS (GW)');
            break;
        case "meshlium_zb_ap":
            notify('saving.png', 'Setting Meshlium as Meshlium Zigbee AP');
            break;
        case "meshlium_zb_gprs_ap":
            notify('saving.png', 'Setting Meshlium as Meshlium Zigbee GPRS AP');
            break;
        case "meshlium_zb_mesh_ap":
            notify('saving.png', 'Setting Meshlium as Meshlium Zigbee Mesh AP');
            break;
        case "meshlium_zb_mesh_ap_gw":
            notify('saving.png', 'Setting Meshlium as Meshlium Zigbee Mesh AP GW');
            break;
        case "meshlium_zb_mesh_ap_gprs_gw":
            notify('saving.png', 'Setting Meshlium as Meshlium Zigbee Mesh AP GPRS (GW)');
            break;
        default:
            break;
    }
    
    submit_data="set="+type;
    $.ajax({
        type: "POST",
        url: "core/structure/presets/setPresets.php",
        data: submit_data,
        success: function(datos){
            $('#preset_content').html(datos);
            document.body.style.cursor = '';
            if (type == 'meshlium_gprs_ap' || type == 'meshlium_mesh_ap_gprs_gw' || type == 'meshlium_zb_gprs_ap' || type == 'meshlium_zb_mesh_ap_gprs_gw')
            {
                endnotify();
                notify('save.png', "You are being redirected ... <br><br>Please, finish to configure GPRS and restart the machine to take effect.");
                fadenotify();

                setTimeout( function()
                  {
                     location.href="index.php?section=a_interfaces&plugin=d0_gprs";
                  }, 3000);
                
            }
            else
            {
                endnotify();
                notify('save.png', "Restart the machine to take effect.");
                fadenotify();
            }
        }
    });
}

