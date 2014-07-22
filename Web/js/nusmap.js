$(document).ready(function() {
var nus_center = new google.maps.LatLng(1.298796, 103.772143); //Google map Coordinates
var map;
map_initialize(); // load map

function map_initialize() {

 var mapOptions = {
    zoom: 20,
    center: nus_center,
    panControl: true,
    zoomControl: true, //enable zoom control
    zoomControlOptions: {
    style: google.maps.ZoomControlStyle.SMALL //zoom control size
    },
    scaleControl: true, // enable scale control
    mapTypeId: google.maps.MapTypeId.ROADMAP // google map type
  };
  var overlayOpts = {
    opacity:0.3
  };

  map = new google.maps.Map(document.getElementById('nus_map'), mapOptions);


  location_Marker = new google.maps.Marker({
    position: nus_center,
    draggable: true
  });
  location_Marker.setMap(map);

  google.maps.event.addListener(location_Marker, 'dragend', function(evt){
    document.getElementById('lat_lng_display').innerHTML = '<p>Marker dropped:<br/>Current Lat: ' + evt.latLng.lat().toFixed(6) + '<br/>Current Lng: ' + evt.latLng.lng().toFixed(6) + '</p>';
  });

  google.maps.event.addListener(location_Marker, 'dragstart', function(evt){
    document.getElementById('lat_lng_display').innerHTML = '<p>Currently dragging marker...</p>';
  });

  var imageBounds_I3_02 = new google.maps.LatLngBounds(
      new google.maps.LatLng(1.291936, 103.775114),
      new google.maps.LatLng(1.292843, 103.776310));
  var imageBounds_E4_06 = new google.maps.LatLngBounds(
      new google.maps.LatLng(1.2982600-200/10000000, 103.7714118-200/10000000),
      new google.maps.LatLng(1.2992000-200/10000000, 103.7726933-200/10000000));
  
  var i3_02_Overlay = new google.maps.GroundOverlay(
      '/img/I3-02.png',
      imageBounds_I3_02, overlayOpts);
  i3_02_Overlay.setMap(map);

  var E4_06_Overlay = new google.maps.GroundOverlay(
      '/img/E4-06.png',
     imageBounds_E4_06, overlayOpts);
  E4_06_Overlay.setMap(map);


 //Load Markers from the XML File
    $.get("proxy.php", function (data) {
        $(data).find("marker").each(function () {
            //Get user input values for the marker from the form
            var name = $(this).attr('name');
            var point = new google.maps.LatLng(parseFloat($(this).attr('lat')),parseFloat($(this).attr('lng')));
            var bat = $(this).attr('BAT');
            var huma = $(this).attr('HUMA');
            var lum = $(this).attr('LUM');
            var mcp = $(this).attr('MCP');
            var dust = $(this).attr('DUST');
            var tca = $(this).attr('TCA');
            var time = $(this).attr('time');

            //call create_marker() function for xml loaded maker
            create_marker(point, name, bat, huma, lum, mcp, dust, tca, time, false, false, false, "/img/pin_green.png");
        });
    });
 
}


//############### Create Marker Function ##############
function create_marker(MapPos, MapTitle, Bat, Huma, Lum, Mcp, Dust, Tca, Time, InfoOpenDefault, DragAble, Removable, iconPath)
{
    //new marker
    var marker = new google.maps.Marker({
        position: MapPos,
        map: map,
        draggable:DragAble,
        animation: google.maps.Animation.DROP,
        title:MapTitle,
        icon: iconPath
    });

    //Content structure of info Window for the Markers
    var contentString = $('<div class="marker-info-win">'+
    '<div class="marker-inner-win"><span class="info-content">'+
    '<h1 class="marker-heading">'+MapTitle+'</h1>'+
    'Battery: ' + Bat + '%<br/>' + 'Humidity: ' + Huma + '%<br/>' +
    '</span><button name="daily-marker" class="daily-marker" title="Daily Reading">Daily Reading</button>'+
    '</div></div>');

    //Create an infoWindow
    var infowindow = new google.maps.InfoWindow();
    //set the content of infoWindow
    infowindow.setContent(contentString[0]);

    //Find daily button in infoWindow
    var dailyBtn   = contentString.find('button.daily-marker')[0];

   //Find weekly button in infoWindow
    var weeklyBtn     = contentString.find('button.weekly-marker')[0];

    //add click listner to daily marker button
    google.maps.event.addListener(dailyBtn, "click", function() {
        //call daily_marker function to display the daily sensor reading
        daily_marker(marker);
    });

    //add click listner to save marker button
    google.maps.event.addListener(marker, 'click', function() {
            infowindow.open(map,marker); // click on marker opens info window
    });

    if(InfoOpenDefault) //whether info window should be open by default
    {
      infowindow.open(map,marker);
    }
}


});
