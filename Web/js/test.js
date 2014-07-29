$(document).ready(function() {
var nus_center = new google.maps.LatLng(1.298796, 103.772143); //Google map Coordinates
var map;
var infowindow;
var heatmap;
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
  
  heatmap = new HeatmapOverlay(map, {
        "radius":20,
        "visible":true, 
        "opacity":60
  });

  var tcaData={
        max: 100,
        data: [{lat: 1.298978, lng:103.771715, count: 95},{lat: 1.298952, lng:103.771671, count: 70},{lat: 1.298921, lng:103.771692, count: 65},{lat: 1.298921, lng:103.771738, count: 55}]
    };
 
  google.maps.event.addListenerOnce(map, "idle", function(){
    // this is important, because if you set the data set too early, the latlng/pixel projection doesn't work
        heatmap.setDataSet(tcaData);
  });

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
      '/wsn/img/I3-02.png',
      imageBounds_I3_02, overlayOpts);
  i3_02_Overlay.setMap(map);

  var E4_06_Overlay = new google.maps.GroundOverlay(
      '/wsn/img/E4-06.png',
     imageBounds_E4_06, overlayOpts);
  E4_06_Overlay.setMap(map);


 //Load Markers from the XML File
    $.get("http://meshliuma.ami-lab.org/sensors/", function (data) {
        $(data).find("sensor").each(function () {
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
            create_marker(point, name, bat, huma, lum, mcp, dust, tca, time, false, false, false, "/wsn/img/pin_green.png");
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
    '</span><button name="draw-bat" class="draw-bat" title="Draw Chart">Battery</button>' + ': ' + Bat + '%<br/>' + 
    '</span><button name="draw-huma" class="draw-huma" title="Draw Chart">Humidity</button>' + ': ' + Huma + '%<br/>' +
    '</span><button name="draw-tca" class="draw-tca" title="Draw Chart">Temperature</button>' + ': ' + Tca + '&degC<br/>' + 
    '</span><button name="draw-lum" class="draw-lum" title="Draw Chart">Luminosity</button>' + ': ' + Lum + '%<br/>' +
    '</span><button name="draw-mcp" class="draw-mcp" title="Draw Chart">Noise</button>' + ': ' + Mcp + 'dBm<br/>' + 
    '</span><button name="draw-dust" class="draw-dust" title="Draw Chart">Dust</button>' + ': ' + Dust + 'ppB<br/>' +
    /*'</span><button name="update-marker" class="update-marker" title="Update Location">Update Location</button>'+
    '</span><button name="daily-marker" class="daily-marker" title="Daily Reading">Daily Reading</button>'+*/
    '</div></div>');

    // Find draw chart button in infoWindow
    var drawBatBtn = contentString.find('button.draw-bat')[0];
    var drawHumaBtn = contentString.find('button.draw-huma')[0];
    var drawTcaBtn = contentString.find('button.draw-tca')[0];
    var drawLumBtn = contentString.find('button.draw-lum')[0];
    var drawMcpBtn = contentString.find('button.draw-mcp')[0];
    var drawDustBtn = contentString.find('button.draw-dust')[0];
    
    google.maps.event.addDomListener(drawBatBtn, 'click', function(event) {
       ajaxChart(marker,'hour','bat');
    });
    google.maps.event.addDomListener(drawHumaBtn, 'click', function(event) {
       ajaxChart(marker,'hour','huma');
    });
     google.maps.event.addDomListener(drawTcaBtn, 'click', function(event) {
       ajaxChart(marker,'hour','tca');
    });    
    google.maps.event.addDomListener(drawLumBtn, 'click', function(event) {
       ajaxChart(marker,'hour','lum');
    });
    google.maps.event.addDomListener(drawMcpBtn, 'click', function(event) {
       ajaxChart(marker,'hour','mcp');
    });
    google.maps.event.addDomListener(drawDustBtn, 'click', function(event) {
       ajaxChart(marker,'hour','dust');
    });
    
    /*
    var updateBtn   = contentString.find('button.update-marker')[0];
   
    //add click listener to update marker button
    google.maps.event.addDomListener(updateBtn, 'click', function(event) {
        update_marker(marker);
    });
    */

    //add click listener to marker
    google.maps.event.addListener(marker, 'click', function() {
        if (infowindow) infowindow.close();
        map.panTo(marker.getPosition());
        infowindow = new google.maps.InfoWindow({content: contentString[0]});
        infowindow.open(map,marker);
    });

    if(InfoOpenDefault) //whether info window should be open by default
    {
      if (infowindow) infowindow.close();
      infowindow = new google.maps.InfoWindow({content: contentString[0]});
      infowindow.open(map,marker);
    }
}

//############### Update Marker Function ##############
function update_marker(Marker)
{
    //Save new marker using jQuery Ajax
    var mLatLang = Marker.getPosition().toUrlValue(); //get marker position
    var mName = Marker.getTitle();
    var myData = {name : mName, latlang : mLatLang}; //post variables
    
    $.ajax({
      type: "POST",
      url: "sensor_process.php",
      data: myData,
      success:function(data){
          infowindow.close();
          Marker.setAnimation(google.maps.Animation.BOUNCE);
          Marker.setAnimation(null);
        },
        error:function (xhr, ajaxOptions, thrownError){
            alert(thrownError); //throw any errors
        }
    });
}


function ajaxChart(Marker,freq,type) {
    var sname = Marker.getTitle();
    var qUrl = "http://meshliuma.ami-lab.org/sensors/" + sname + "/" + freq + "/" + type + "/";
    $.ajax({
        url: qUrl,
        dataType: "xml",
        success: function (xml) {
            initChart(xml);
            }
    });
};

function initChart(xml) {
            var label_array = [];
            var value_array = [];

            $(xml).find('sensor').each(function () {

                var label = $(this).attr("hour");
                var value = $(this).attr("value");

                label_array.push(label);
                value_array.push(parseFloat(value).toFixed(2));
            });

var data = {
    labels : label_array,
    datasets : [
        {
            label: "My First dataset",
            fillColor: "rgba(152, 196, 44, 0.4)",
            strokeColor: "rgba(220,220,220,1)",
            pointColor: "rgba(220,220,220,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: value_array
        }
    ]
};

var options = {
    ///Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines : true,

    //String - Colour of the grid lines
    scaleGridLineColor : "rgba(0,0,0,.05)",

    //Number - Width of the grid lines
    scaleGridLineWidth : 1,

    //Boolean - Whether the line is curved between points
    bezierCurve : true,

    //Number - Tension of the bezier curve between points
    bezierCurveTension : 0.4,

    //Boolean - Whether to show a dot for each point
    pointDot : true,

    //Number - Radius of each point dot in pixels
    pointDotRadius : 4,

    //Number - Pixel width of point dot stroke
    pointDotStrokeWidth : 1,

    //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
    pointHitDetectionRadius : 20,

    //Boolean - Whether to show a stroke for datasets
    datasetStroke : true,

    //Number - Pixel width of dataset stroke
    datasetStrokeWidth : 2,

    //Boolean - Whether to fill the dataset with a colour
    datasetFill : true,

    //String - A legend template
    legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
};

var ctx = $("#chart").get(0).getContext("2d"); 
var myLineChart = new Chart(ctx).Line(data, options);
console.dir(myLineChart);

}


});

