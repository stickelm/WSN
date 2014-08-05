$(document).ready(function() {
	var nus_center = new google.maps.LatLng(1.298796, 103.772143); //Google map Coordinates
    var map;
	var infowindow;
    var waspID = "";
    var sensor;
	
	map_initialize(); // load map
    
    $(window).resize(function(){
        if (!waspID == "") {
            drawCharts(waspID,sensor);
        }
    });
 
    $('button').click(function(){
        $(this).toggleClass("down");
        var button_id = this.id;
        console.log(button_id);
        switch(button_id){
            case "toggleTca":
                //heatmap.setMap(heatmap.getMap() ? null : map);
                
                break;
            case "toggleHuma":
                
                break;
            case "toggleLum":
                
                break;
            case "toggleMcp":
                
                break;
            case "toggleDust":
                
                break;
            case "toggleBat":
                
                break;
        }
    });
    
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
		opacity:0.5
		};

		map = new google.maps.Map(document.getElementById('nus_map'), mapOptions);

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

		//Load Markers from the JSON API Call
		$.ajax({
			type: "GET",
			url: "http://libelium-wsn.ami-lab.org/waspmotes/",
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
            waspID = MapTitle;
            sensor = "bat";
            drawCharts(MapTitle, sensor);
		});
		google.maps.event.addDomListener(drawHumaBtn, 'click', function(event) {
		   waspID = MapTitle;
            sensor = "huma";
            drawCharts(MapTitle, sensor);
		});
		 google.maps.event.addDomListener(drawTcaBtn, 'click', function(event) {
		   waspID = MapTitle;
            sensor = "tca";
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
		
		/* update marker position 
		var updateBtn   = contentString.find('button.update-marker')[0];
	   
		//add click listener to update marker button
		google.maps.event.addDomListener(updateBtn, 'click', function(event) {
			update_marker(marker);
		});	/*	*/

		//add click listener to marker
		google.maps.event.addListener(marker, 'click', function() {
			if (infowindow) infowindow.close();
			map.panTo(marker.getPosition());
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
			url: "http://libelium-wsn.ami-lab.org/waspmotes/" + mName + "/update/",
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
	}	*/
	
	function drawCharts(waspID, sensor)
	{
		google.load("visualization", "1", {packages:["corechart"], callback:draw_3_Charts});
		
		function draw_3_Charts() {
			var freqArray = ["Hour","Day","Month"];
			var chartDivArray =["hour_chart","day_chart","month_chart"];
			var i = 0;
			
			while (freqArray[i]) {
				var apiUrl = "http://libelium-wsn.ami-lab.org/waspmotes/" 
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
					title: waspID + " " + sensor + "-" + freqArray[i] + " Line Chart"
				};
		 
				var chart = new google.visualization.LineChart(document.getElementById(chartDivArray[i]));
				chart.draw(data, options);
				i++; 
			} 
		}
	}
    
});
