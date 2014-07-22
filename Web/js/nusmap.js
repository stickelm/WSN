// This example uses a GroundOverlay to place an image on the map
// showing some building floors in the NUS, Singapore.

/*
    var customIcons = {
      waspmote: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png'
      },
      gateway: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_red.png'
      }
    };
*/

  function initialize() {

  var nus_center = new google.maps.LatLng(1.298796, 103.772143);
  var imageBounds_I3_02 = new google.maps.LatLngBounds(
      new google.maps.LatLng(1.291936, 103.775114),
      new google.maps.LatLng(1.292843, 103.776310));
  var imageBounds_E4_06 = new google.maps.LatLngBounds(
      new google.maps.LatLng(1.2982600-200/10000000, 103.7714118-200/10000000),
      new google.maps.LatLng(1.2992000-200/10000000, 103.7726933-200/10000000));
  var mapOptions = {
    zoom: 20,
    center: nus_center,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  var overlayOpts = {
    opacity:0.3
  };
  var location_Marker = new google.maps.Marker({
    position: nus_center,
    draggable: true
  });

  var map = new google.maps.Map(document.getElementById('nus_map'),
      mapOptions);

  google.maps.event.addListener(location_Marker, 'dragend', function(evt){
    document.getElementById('lat_lng_display').innerHTML = '<p>Marker dropped:<br/>Current Lat: ' + evt.latLng.lat().toFixed(6) + '<br/>Current Lng: ' + evt.latLng.lng().toFixed(6) + '</p>';
  });

  google.maps.event.addListener(location_Marker, 'dragstart', function(evt){
    document.getElementById('lat_lng_display').innerHTML = '<p>Currently dragging marker...</p>';
  });

  var infoWindow = new google.maps.InfoWindow;
  // icon url details: https://developers.google.com/chart/image/docs/gallery/dynamic_icons#scalable_pins
  // var iconBase = 'http://chart.apis.google.com/chart?chst=d_map_spin&chld=0.7|0|D9D61A|11.5|_|';
  var iconBase = 'http://chart.googleapis.com/chart?chst=d_simple_text_icon_above&chld=';
  
  downloadUrl("proxy.php",function(data) {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName("marker");
        for (var i = 0; i < markers.length; i++) {
          var name = markers[i].getAttribute("name");
          var point = new google.maps.LatLng(
              parseFloat(markers[i].getAttribute("lat")),
              parseFloat(markers[i].getAttribute("lng")));
          var bat = markers[i].getAttribute("BAT");
          var huma = markers[i].getAttribute("HUMA");
          var lum = markers[i].getAttribute("LUM");
          var mcp = markers[i].getAttribute("MCP");
          var dust = markers[i].getAttribute("DUST");
          var tca = markers[i].getAttribute("TCA");
          var time = markers[i].getAttribute("time");
          var marker = new google.maps.Marker({
            map: map,
            position: point,
            icon: iconBase + name + '|16|DD0606|flag|16|DD0606|CBC9C9'
          });
          var html = "<b>" + name + "</b> <br/>Battery: " + bat + "%" 
                    + "<br/>Humidity: " + huma + "%" + "<br/>Luminosity: " + lum + "%" 
                    + "<br/>Noise: " + mcp + "dBm" + "<br/>Dust: " + dust + "ppB" 
                    + "<br/>Temperature: " + tca + "&degC" + "<br/>Time: " + time;
          bindInfoWindow(marker, map, infoWindow, html);
        }
  });
   
    function bindInfoWindow(marker, map, infoWindow, html) {
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
    }

    function downloadUrl(url, callback) {
      var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

      request.onreadystatechange = function() {
        if (request.readyState == 4) {
          request.onreadystatechange = doNothing;
          callback(request, request.status);
        }
      };

      request.open('GET', url, true);
      request.send(null);
    }

    function doNothing() {}

  i3_02_Overlay = new google.maps.GroundOverlay(
      '/img/I3-02.png',
      imageBounds_I3_02, overlayOpts);
  i3_02_Overlay.setMap(map);

  E4_06_Overlay = new google.maps.GroundOverlay(
      '/img/E4-06.png',
     imageBounds_E4_06, overlayOpts);
  E4_06_Overlay.setMap(map);

  location_Marker.setMap(map);
}

google.maps.event.addDomListener(window, 'load', initialize);
