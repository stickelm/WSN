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

var lastSelectedItem;
var currentTab = "tab1";

function toogleFileSelect(that)
{
    $("#fileViewer").hide();
    $("#fileList").addClass("fileListSM");
    $("#fileList").removeClass("fileListBG");

    if($(that).hasClass("itemSelected"))
    {
        $(that).removeClass("itemSelected");
    }
    else
    {
        if(lastSelectedItem != 'undefined')
        {
            $(lastSelectedItem).removeClass("itemSelected");
        }
        $(that).addClass("itemSelected");
    }

    lastSelectedItem = that;
}

function loadTab(tab)
{
    $("#"+currentTab).removeClass("selectedTab");
    $("#"+tab).addClass("selectedTab");
    $("#"+currentTab+"content").hide();
    $("#"+tab+"content").show();
    currentTab = tab;
}

function downloadFile(section,plugin)
{
    submit_data="section="+section+"&plugin="+plugin+"&type=downloadFile&"+"file="+ $(lastSelectedItem).attr("id");
    notify("loadinfo.net.gif", "Loading...");
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
            window.open('tmp/'+$(lastSelectedItem).attr("id"));
            fadenotify();
        }
    });
}

function viewFile(section,plugin,num)
{   
    if(lastSelectedItem != 'undefined')
    {
        $("#fileViewer").show();
        submit_data="section="+section+"&plugin="+plugin+"&type=viewFile&"+"file="+$(lastSelectedItem).attr("id")+"&num="+num;
        notify("loadinfo.net.gif", "Loading...");
        $.ajax({
            type: "POST",
            url: "index.php",
            data: submit_data,
            success: function(datos){
                $("#fileViewer").html(datos);
                $("#fileList").addClass("fileListBG");
                $("#fileList").removeClass("fileListSM");
                fadenotify();
            }
        });
    }
    else
    {
        notify("icono-i.png", "Please, first, click over a file")
    }
}

function selectFile(section,plugin)
{
    submit_data="section="+section+"&plugin="+plugin+"&type=selectFile&"+"file="+$(lastSelectedItem).attr("id");
    notify("loadinfo.net.gif", "Loading...");
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
            $("#tab1content").html(datos);
            fadenotify();
        }
    });
}

function createFile(section,plugin)
{
    submit_data="section="+section+"&plugin="+plugin+"&type=createFile&"+"file="+ $("#newFileName").val();
    notify("loadinfo.net.gif", "Loading...");
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
            $("#tab1content").html(datos);
            notify("icono-i", "New file created");
            fadenotify();
        }
    });
}

function deleteFile(section,plugin)
{
    if(confirm('Are you sure?'))
    {
        submit_data="section="+section+"&plugin="+plugin+"&type=deleteFile&"+"file="+$(lastSelectedItem).attr("id");
        notify("loadinfo.net.gif", "Loading...");
        $.ajax({
            type: "POST",
            url: "index.php",
            data: submit_data,
            success: function(datos){
                $("#tab1content").html(datos);
                notify("icono-i", "File deleted");
                fadenotify();
            }
        });
    }
}

var continous = 0;
var continousStoped = 0;
function showMeNow(section,plugin,nonStop,interval)
{

    if(nonStop=='on')
    {
        if(continousStoped == 0)
        {
            continous = 1;
            $('#showMeNowStop').show();
            $('#showMeNowStart').hide();
        }
    }
    else
    {
        continous = 0;
    }
    submit_data="section="+section+"&plugin="+plugin+"&type=showMeNow";
    notify("loadinfo.net.gif", "Loading...");
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
            $("#tab4contentScan").html(datos);
            fadenotify();
            if(continous == 1)
            {
              setTimeout( function()
              {
                 $("#tab4contentScan").html("");
                 showMeNow(section,plugin,nonStop,interval)
              }, interval*1000);
            }
        }
    });
}

function stopMeNow()
{
    $('#nonStop').attr('checked', false);
    continousStoped = 1;
    $('#showMeNowStart').show();
    $('#showMeNowStop').hide();
    continous=0;
}

function showlocalDB(section,plugin,num)
{
    submit_data="section="+section+"&plugin="+plugin+"&type=showlocalDB&num="+num;
    notify("loadinfo.net.gif", "Loading...");
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
            $("#localDataViewer").html(datos);
            fadenotify();
        }
    });
}

function showSqlScript(section,plugin)
{
    submit_data="section="+section+"&plugin="+plugin+"&type=showSqlScript";
    notify("loadinfo.net.gif", "Loading...");
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
            $("#extDataViewer").html(datos);
            fadenotify();
        }
    });
}

function useLocalFile(section,plugin, checkbox)
{
    fileExist = false;
    $('#fileList :div :div').each( function() {
        if (($(this).hasClass("fileEdit")))
        {
            fileExist = true
            fatherID = $(this).parents("div:first").attr("id");
        }
    });

    if(!fileExist && checkbox == 'on')
    {
        notify("fail.png", "You must select a file")
    }
    else
    {
        submit_data="section="+section+"&plugin="+plugin+"&type=useLocalFile"+"&state="+checkbox+"&fatherID="+fatherID;
        notify("loadinfo.net.gif", "Loading...");
        $.ajax({
            type: "POST",
            url: "index.php",
            data: submit_data,
            success: function(datos){
                if(checkbox == 'on')
                {
                    $("#localFileRunning").show();
                }
                else
                {
                    $("#localFileRunning").hide();
                }
                fadenotify();
            }
        });
    }
}

function useLocalDB(section,plugin, checkbox)
{
    submit_data="section="+section+"&plugin="+plugin+"&type=useLocalDB"+"&state="+checkbox;
    notify("loadinfo.net.gif", "Loading...");
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
            if(checkbox == 'on')
            {
                $("#localDBRunning").show();
            }
            else
            {
                $("#localDBRunning").hide();
            }
            fadenotify();
        }
    });
}

function useExtDB(section,plugin, checkbox)
{
    submit_data="section="+section+"&plugin="+plugin+"&type=useExtDB"+"&state="+checkbox;
    notify("loadinfo.net.gif", "Loading...");
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
            if(checkbox == 'on')
            {
                $("#extDBRunning").show();
            }
            else
            {
                $("#extDBRunning").hide();
            }
            fadenotify();
        }
    });
}
function checkConnection(section,plugin,formId)
{
    if(!ms_check_form_fields())
    {
        var json_field=json_encode(formId);
        submit_data="section="+section+"&plugin="+plugin+"&type=checkConnection&"+"form_fields="+json_field;
        notify("loadinfo.net.gif", "Loading...");
        $.ajax({
                   type: "POST",
                   url: "index.php",
                   data: submit_data,
                   success: function(datos){
                        $("#extDataViewer").html(datos);
                        fadenotify();
                   }
                });
    }
}
function saveDataConnection(section,plugin,formId)
{
    if(!ms_check_form_fields())
    {
        var json_field=json_encode(formId);
        submit_data="section="+section+"&plugin="+plugin+"&type=saveDataConnection&"+"form_fields="+json_field;
        notify("loadinfo.net.gif", "Saving data...");
        $.ajax({
                   type: "POST",
                   url: "index.php",
                   data: submit_data,
                   success: function(datos){
                        fadenotify();
                   }
                });
    }
}

function showextDB(section,plugin,formId,num)
{
    var json_field=json_encode(formId);
    submit_data="section="+section+"&plugin="+plugin+"&type=showextDB&"+"form_fields="+json_field+"&num="+num;
    notify("loadinfo.net.gif", "Loading...");
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
            $("#extDataViewer").html(datos);
            fadenotify();
        }
    });
}
