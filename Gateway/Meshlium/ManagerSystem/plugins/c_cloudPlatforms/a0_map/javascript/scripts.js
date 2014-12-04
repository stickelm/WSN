/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


function toggle() {
  var mydiv = document.getElementById('waspmoteESRINEW');
  var mybut = document.getElementById('addNWasp');
  if ($('#waspmoteESRINEW').is(':visible')) {
    $('#waspmoteESRINEW').fadeOut(800, function() {});
  }
  else {
    $('#waspmoteESRINEW').fadeIn(800, function() {});
  }
}

function toggleU() {
  var mydiv = document.getElementById('userESRINEW');
  var mybut = document.getElementById('addNUser');
  if ($('#userESRINEW').is(':visible')) {
    $('#userESRINEW').fadeOut(800, function() {});
  }
  else {
    $('#userESRINEW').fadeIn(800, function() {});
  }
}

function toggle2(id) {
  $('#sure'+id).toggle(800, function() {});
}

function toggleU2(id) {
  $('#sureU'+id).toggle(800, function() {});
}

$(document).ready(function() {
  $('#meshliumESRIb').click(function() {
    $('#meshliumESRI').toggle(800, function() {
      if ($('#meshliumESRI').is(':visible')) {
        $('#meshliumESRIb').text('Hide');
      } else {
        $('#meshliumESRIb').text('Show');
      }
    });
  });
  $('#buttonCloseX').click(function() { toggle(); });
  $('#buttonCloseUX').click(function() { toggleU(); });
});


function toggle4(id) {
  var mybut = document.getElementById('waspmoteESRIb'+id);
  $('#waspmoteESRI'+id).toggle(800, function() {
      if ($('#waspmoteESRI'+id).is(':visible')) {
        mybut.value="Hide";
      } else {
        mybut.value="Show";
      }
    });
}

function toggle4U(id) {
  var mybut = document.getElementById('userESRIb'+id);
  $('#userESRI'+id).toggle(800, function() {
      if ($('#userESRI'+id).is(':visible')) {
        mybut.value="Hide";
      } else {
        mybut.value="Show";
      }
    });
}


function enableSec(url,section,plugin) {
  $('#tab22content').toggle(800, function() {
      if ($('#tab22content').is(':visible')) 
      {
        $('#butensec').css('background-image', 'url('+url+"security_layer_on.png)");
        submit_data="section="+section+"&plugin="+plugin+"&type=enSec";
        $.ajax({type: "POST", url: "index.php", data: submit_data, success: function(){}});
      } 
      else 
      {
        $('#butensec').css('background-image', 'url('+url+"security_layer_off.png)");
        submit_data="section="+section+"&plugin="+plugin+"&type=disSec";
        $.ajax({type: "POST", url: "index.php", data: submit_data, success: function(){}});
      }
    });
}


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

function startZigbeeStorerDaemon(section,plugin)
{
    submit_data="section="+section+"&plugin="+plugin+"&type=startZigbeeStorerDaemon";
    notify("loadinfo.net.gif", "Loading...");
    $.ajax({
        type: "POST",
        url: "index.php",
        data: submit_data,
        success: function(datos){
            if(datos == 1)
               $("#daemonStatus").html("<div id='dRunning'></div> <span> <b>Daemon running</b></span>");
            fadenotify();
        }
    });
}


var lastSelectedItem;
var currentTab = "tab1";
function loadTab(tab)
{
    $("#"+currentTab).removeClass("selectedTab");
    $("#"+tab).addClass("selectedTab");
    $("#"+currentTab+"content").hide();
    $("#"+tab+"content").show();
    currentTab = tab;
}


function saveWasp(section,plugin,formId,Id) {
    var json_field=json_encode(formId);
    submit_data="section="+section+"&plugin="+plugin+"&type=saveWasp&form_fields="+json_field+"&id="+Id;
    $.ajax({
      type: "POST",
      url: "index.php",
      data: submit_data,
      success: function(data){
        notify("saving", "Waspmote "+data+" saved");
        fadenotify();
      }
    });
}

function delWasp(section,plugin,formId,Id) {
    var json_field=json_encode(formId);
    submit_data="section="+section+"&plugin="+plugin+"&type=delWasp&form_fields="+json_field+"&id="+Id;
    $.ajax({
      type: "POST",
      url: "index.php",
      data: submit_data,
      success: function(data){
        notify("fail", "Waspmote "+data+" deleted");
        fadenotify();
        document.location.reload(true);
      }
    });
}

function saveMesh(section,plugin,formId) {
    var json_field=json_encode(formId);
    submit_data="section="+section+"&plugin="+plugin+"&type=saveMesh&form_fields="+json_field;
    $.ajax({
      type: "POST",
      url: "index.php",
      data: submit_data,
      success: function(data){
        notify("saving", "Meshlium "+data+" saved");
        fadenotify();
      }
    });
}

function addWasp(section,plugin,formId) {
    var json_field=json_encode(formId);
    submit_data="section="+section+"&plugin="+plugin+"&type=addWasp&form_fields="+json_field;
    $.ajax({
      type: "POST",
      url: "index.php",
      data: submit_data,
      success: function(data){
        notify("saving", "Waspmote "+data+" added");
        fadenotify();
        document.location.reload(true);
      }
    });
}

function addUser(section,plugin,formId) {
    var json_field=json_encode(formId);
    submit_data="section="+section+"&plugin="+plugin+"&type=addUser&form_fields="+json_field;
    $.ajax({
      type: "POST",
      url: "index.php",
      data: submit_data,
      success: function(data){
        notify("saving", "User "+data+" added");
        fadenotify();
        document.location.reload(true);
      }
    });
}

function saveUser(section,plugin,formId,id) {
    var json_field=json_encode(formId);
    submit_data="section="+section+"&plugin="+plugin+"&type=saveUser&id="+id+"&form_fields="+json_field;
    $.ajax({
      type: "POST",
      url: "index.php",
      data: submit_data,
      success: function(data){
        notify("saving", "User "+data+" saved");
        fadenotify();
      }
    });
}

function delUser(section,plugin,formId,id) {
    var json_field=json_encode(formId);
    submit_data="section="+section+"&plugin="+plugin+"&type=delUser&id="+id+"&form_fields="+json_field;
    $.ajax({
      type: "POST",
      url: "index.php",
      data: submit_data,
      success: function(data){
        notify("fail", "User "+data+" deleted");
        fadenotify();
        document.location.reload(true);
      }
    });
}


function setPos(section,plugin,formId) {
    var json_field=json_encode(formId);
    submit_data="section="+section+"&plugin="+plugin+"&type=setPos&form_fields="+json_field;
    $.ajax({
      type: "POST",
      url: "index.php",
      data: submit_data,
      success: function(data){
        notify("saving", "Position "+data+" set");
        fadenotify();
        updateLayers();
      }
    });
}

function updateLayers() {
  var layers = map.getLayersVisibleAtScale();
  layers.forEach(function(l){
    if( l.type === "Feature Layer")
      l.refresh();                                        
  });
}

function request(formId,token) {
    var user = document.getElementById('meshRUser').value;
    var passwd = document.getElementById('meshRPassw').value;
    var request = document.getElementById('token');
    submit_data="f=json&username="+user+"&password="+passwd+"&client=ip&ip=2.139.174.70:11111";
    $.ajax({
      type: "POST",
      url: "../meshlium/rest/generateToken?"+submit_data,
      success: function(data){
        console.log("ola k ase");
        request.value= data;
      }
    });
}

