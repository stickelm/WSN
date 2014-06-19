"use strict"

dojo.require("esri.map");
dojo.require("esri.layers.FeatureLayer");
dojo.require("esri.toolbars.edit");
dojo.require("esri.dijit.Geocoder");

var map, geocoder;

function init() 
{
  // create map
  map = new esri.Map("map", {
    basemap: "topo",
    center: config.center,
    zoom: 12
  });

  // create geocoder widget
  geocoder = new esri.dijit.Geocoder({
    arcgisGeocoder: {
      placeholder: 'Find a place',
      sourceCountry: 'ESP'
    },
    map: map
  }, dojo.byId('search'));

  geocoder.startup();

  // add meshlium and waspmote layers to map
  var meshliumLayer = esri.layers.FeatureLayer( config.meshliumUrl + "/0?token=visor");
  var waspmoteLayer = esri.layers.FeatureLayer( config.meshliumUrl + "/1?token=visor");
  map.addLayers([meshliumLayer,waspmoteLayer]);

  // use wasmpote layer fullExtent to center the map, once it is ready
  dojo.connect(map, "onLayerAddResult", function(layer,error)
  {
    if( layer.name == "Waspmote" )
    {
      map.setExtent( layer.fullExtent );
    }
  });

  // when the map is panned/zoomed, update the information box
  dojo.connect(map, "onExtentChange", function(extent,delta,levelChange,lod)
  {
    var center = extent.getCenter();
    center = esri.geometry.xyToLngLat(center.x,center.y);
    /*var info = "Map Center (lng,lat): " + 
      dojo.number.format(center[0], {places:4}) + ", " + 
      dojo.number.format(center[1],{places:4}) + ", Zoom Level: " + lod.level;
    console.log( info );
    dojo.byId("map-info").innerHTML = info;*/
    dojo.byId("visorx").value = center[0];
    dojo.byId("visory").value = center[1];
  });

  // events for basemap change buttons
  dojo.connect(dojo.byId('topo'),    "onclick", changeBasemap );
  dojo.connect(dojo.byId('streets'), "onclick", changeBasemap );
  dojo.connect(dojo.byId('hybrid'),  "onclick", changeBasemap );
}

function changeBasemap(e)
{
  var id = e.target.id;
  console.log(id);
  map.setBasemap(id);
  dojo.query('#controls li').removeClass('selected');
  dojo.query('#' + id).addClass('selected');
}

dojo.addOnLoad(init);
