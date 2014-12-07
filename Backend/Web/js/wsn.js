$(document).ready(function() {
	var nus_center = new google.maps.LatLng(1.298796-100/1000000, 103.772143-100/1000000); //Google map Coordinates
    var map;
	var infowindow;
    var waspID = "";
    var sensor;
    var heatmap;
    var tcaArray = [];
    var humaArray = [];
    var lumArray = [];
    var mcpArray = [];
    var dustArray = [];
    var batArray = [];
	var buttonIdPressed;
    
	map_initialize(); // load map
    
    $(window).resize(function(){
        if (!waspID == "") {
            drawCharts(waspID,sensor);
        }
    });
    
    $('button').click(function(){
        if ($('button').hasClass('down') && !$(this).hasClass("down")) {
            $('button').removeClass('down');
            heatmap.setMap(null);
        }
        $(this).toggleClass("down");
        
        var button_id = this.id;
        switch(button_id){
            case "toggleTca":
                var heatMapData = tcaArray;
                break;
            case "toggleHuma":
                var heatMapData = humaArray;
                break;
            case "toggleLum":
                var heatMapData = lumArray;
                break;
            case "toggleMcp":
                var heatMapData = mcpArray;
                break;
            case "toggleDust":
                var heatMapData = dustArray;
                break;
            case "toggleBat":
                var heatMapData = batArray;
                break;
        }
        
        if (!heatmap || !heatmap.map) {
            heatmap = new google.maps.visualization.HeatmapLayer({
            radius: 50,
            data: heatMapData
        });
        }
        
        if ($(this).hasClass('down')) {
            heatmap.setMap(map);
            //This way its working, the heatmap is deleted after 3 sec:
            //setTimeout(function () { heatmap.setMap(null); console.log(heatmap); }, 3000);
        } else {
            heatmap.setMap(null);
        }
    });
    
    
	function map_initialize() {

		var mapOptions = {
		zoom: 20,
		center: nus_center,
		panControl: false,
		zoomControl: true, //enable zoom control
        streetViewControl: false,
        mapTypeControlOptions: { 
            mapTypeIds: [] 
        },
		zoomControlOptions: {
            style: google.maps.ZoomControlStyle.SMALL //zoom control size
		},
		scaleControl: true, // enable scale control
        mapTypeId: google.maps.MapTypeId.ROADMAP // google map type
		};
		
        var overlayOpts = {
		opacity:0.5
		};

		map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);

		/*
        var imageBounds_I3_02 = new google.maps.LatLngBounds(
		  new google.maps.LatLng(1.291936, 103.775114),
		  new google.maps.LatLng(1.292843, 103.776310));
        */
		var imageBounds_E4_06 = new google.maps.LatLngBounds(
		  new google.maps.LatLng(1.2982600-200/10000000, 103.7714118-200/10000000),
		  new google.maps.LatLng(1.2992000-200/10000000, 103.7726933-200/10000000));

		/*
        var i3_02_Overlay = new google.maps.GroundOverlay(
		  '/img/I3-02.png',
		  imageBounds_I3_02, overlayOpts);
		i3_02_Overlay.setMap(map);
        */

		var E4_06_Overlay = new google.maps.GroundOverlay(
		  '/img/E4-06.png',
		 imageBounds_E4_06, overlayOpts);
		E4_06_Overlay.setMap(map);

		//Load Markers from the JSON API Call
		$.ajax({
			type: "GET",
			url: "http://wsn.ami-lab.org/waspmotes/",
			async: false,
			dataType: "json",
			success: function(data){
				$(data.waspmotes).each(function () {
					var name = this.waspmote.name;
                    var number = name.match(/\d+/)*1;
					var point = new google.maps.LatLng(parseFloat(this.waspmote.lat),parseFloat(this.waspmote.lng));
					var bat = this.waspmote.BAT;
					var huma = this.waspmote.HUMA;
					var lum = this.waspmote.LUM;
					var mcp = this.waspmote.MCP;
					var dust = this.waspmote.DUST;
					var tca = this.waspmote.TCA;
					var time = this.waspmote.time;
                       
                    saveHeatData(point, bat, huma, lum, mcp, dust, tca);
					//call create_marker() function for json loaded maker
					create_marker(point, name, bat, huma, lum, mcp, dust, tca, time, 
                        false, false, "/img/numbers/number_" + number + ".png");
				});
			}
		});
		
	}

	//############### Create Marker Function ##############
	function create_marker(MapPos, MapTitle, Bat, Huma, Lum, Mcp, Dust, Tca, Time, DragAble, Removable, iconPath)
	{
		//draw new marker
		var marker = new google.maps.Marker({
			position: MapPos,
			map: map,
			draggable:DragAble,
			animation: google.maps.Animation.DROP,
			title:MapTitle,
			icon: iconPath
		});

		//Content structure of info Window for the Markers
		var contentString = $(
            '<div><table><tbody>' + 
            '<tr><td colspan="2"><h3>Sensor '+MapTitle+'</h3></td></tr>' + 
            '<tr><td><div name="draw-tca" class="infowindowbutton" title="Draw Chart">Temperature</div></td>' + '<td> : ' + Tca + '&degC</td></tr>' + 
            '<tr><td><div name="draw-huma" class="infowindowbutton" title="Draw Chart">Humidity</div></td>' + '<td> : ' + Huma + '%</td></tr>' + 
            '<tr><td><div name="draw-lum" class="infowindowbutton" title="Draw Chart">Luminosity</div></td>' + '<td> : ' + Lum + '%</td></tr>' + 
            '<tr><td><div name="draw-mcp" class="infowindowbutton" title="Draw Chart">Noise</div></td>' + '<td> : ' + Mcp + 'dBm</td></tr>' + 
            '<tr><td><div name="draw-dust" class="infowindowbutton" title="Draw Chart">Dust</div></td>' + '<td> : ' + Dust + 'ppB</td></tr>' + 
            '<tr><td><div name="draw-bat" class="infowindowbutton" title="Draw Chart">Battery</div></td>' + '<td> : ' + Bat + '%</td></tr>' + 
            '</tbody></table>' + 
            //'<div name="update-marker" class="infowindowbutton" title="Update Location">Update Location</div>' + 
            '</div>'
        );

		// Find draw chart button in infoWindow
		var drawTcaBtn = contentString.find("div[name=draw-tca]")[0];
		var drawHumaBtn = contentString.find("div[name=draw-huma]")[0];
		var drawLumBtn = contentString.find("div[name=draw-lum]")[0];
		var drawMcpBtn = contentString.find("div[name=draw-mcp]")[0];
		var drawDustBtn = contentString.find("div[name=draw-dust]")[0];
        var drawBatBtn = contentString.find("div[name=draw-bat]")[0];
		
		google.maps.event.addDomListener(drawTcaBtn, 'click', function(event) {
		   waspID = MapTitle;
            sensor = "tca";
            drawCharts(MapTitle, sensor);
		}); 
        google.maps.event.addDomListener(drawHumaBtn, 'click', function(event) {
		   waspID = MapTitle;
            sensor = "huma";
            drawCharts(MapTitle, sensor);
		});   
		google.maps.event.addDomListener(drawLumBtn, 'click', function(event) {
		   waspID = MapTitle;
            sensor = "lum";
            drawCharts(MapTitle, sensor);
		});
		google.maps.event.addDomListener(drawMcpBtn, 'click', function(event) {
            waspID = MapTitle;
            sensor = "mcp";
            drawCharts(MapTitle, sensor);
		});
		google.maps.event.addDomListener(drawDustBtn, 'click', function(event) {
            waspID = MapTitle;
            sensor = "dust";
            drawCharts(MapTitle, sensor);
		});
        google.maps.event.addDomListener(drawBatBtn, 'click', function(event) {
            waspID = MapTitle;
            sensor = "bat";
            drawCharts(MapTitle, sensor);
		});
		
		/* update marker position 
		var updateBtn   = contentString.find("p[name=update-marker]")[0];
	   
		//add click listener to update marker button
		google.maps.event.addDomListener(updateBtn, 'click', function(event) {
			update_marker(marker);
		});	/*	*/

		//add click listener to marker
		google.maps.event.addListener(marker, 'click', function() {
			if (infowindow) infowindow.close();
			//map.panTo(marker.getPosition());
			infowindow = new google.maps.InfoWindow({content: contentString[0]});
			infowindow.open(map,marker);
		});
		
	}
	
	/* Update Marker Function 
	function update_marker(Marker)
	{
		//Save new marker using jQuery Ajax
		var mLatLang = Marker.getPosition().toUrlValue(); //get marker position
		var mName = Marker.getTitle();
		var myData = {name : mName, latlang : mLatLang}; //post variables
		
		$.ajax({
			type: "POST",
			url: "http://wsn.ami-lab.org/waspmotes/" + mName + "/update/",
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
	} /* */	
	
	function drawCharts(waspID, sensor)
	{
		google.load("visualization", "1", {packages:["corechart"], callback:draw_3_Charts});
		
		function draw_3_Charts() {
			var freqArray = ["Hour","Day","Month"];
			var chartDivArray =["hour_chart","day_chart","month_chart"];
			var i = 0;
			
			while (freqArray[i]) {
				var apiUrl = "http://wsn.ami-lab.org/waspmotes/" 
					+ waspID + "/" + freqArray[i].toLowerCase() + "/" + sensor + "/";
				var data = new google.visualization.DataTable();
				data.addColumn('string', freqArray[i]);
				data.addColumn('number', 'Value');
				
				$.ajax({
					url: apiUrl,
					dataType:"json",
					async: false,
					success: function(jsonData){        
						$.each(jsonData.waspmotes, function() {
							var itemsArray = [];
							var item = [this.waspmote.hour, 
								Math.round(this.waspmote.value * 100) / 100];
							itemsArray.push(item);
							data.addRow(item);
						});
					}
				});
		 
				var options = {
					title: "Sensor " + waspID + " " + sensorName(sensor) + " (Every " + freqArray[i] +")",
                    curveType: 'function'
				};
		 
				var chart = new google.visualization.LineChart(document.getElementById(chartDivArray[i]));
				chart.draw(data, options);
				i++; 
			} 
		}
	}

    function saveHeatData(point, bat, huma, lum, mcp, dust, tca) {
        
        tcaItem = {location: point, weight: tca};
        tcaArray.push(tcaItem);
        humaItem = {location: point, weight: huma};
        humaArray.push(humaItem);
        lumItem = {location: point, weight: lum};
        lumArray.push(lumItem);
        mcpItem = {location: point, weight: mcp};
        mcpArray.push(mcpItem);
        dustItem = {location: point, weight: dust*10};
        dustArray.push(dustItem);
        batItem = {location: point, weight: bat};
        batArray.push(batItem);
    }
    
    function sensorName(sensorType) {
        var sensorName = "";
        switch(sensorType.toLowerCase()){
            case "tca":
                sensorName = "Temperature";
                return sensorName;
                break;
            case "huma":
                sensorName = "Humidity";
                return sensorName;
                break;
            case "lum":
                sensorName = "Luminosity";
                return sensorName;
                break;
            case "mcp":
                sensorName = "Noise";
                return sensorName;
                break;
            case "dust":
                sensorName = "Dust";
                return sensorName;
                break;
            case "bat":
                sensorName = "Battery";
                return sensorName;
                break;
            default:
                sensorName = "";
                return sensorName;
        }
    }
    
});