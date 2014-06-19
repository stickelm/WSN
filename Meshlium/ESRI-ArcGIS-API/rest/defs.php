<?php

/*	-- ESRI-ArcGIS integration --

														  )[            ....   
													   -$wj[        _swmQQWC   
														-4Qm    ._wmQWWWW!'    
														 -QWL_swmQQWBVY"~.____ 
														 _dQQWTY+vsawwwgmmQWV! 
										1isas,       _mgmQQQQQmmQWWQQWVY!"-    
									   .s,. -?ha     -9WDWU?9Qz~- -- -         
									   -""?Ya,."h,   <!`_mT!2-?5a,             
									   -Swa. Yg.-Q,  ~ ^`  /`   "$a.           
	 aac  <aa, aa/                aac  _a,-4c ]k +m               "1           
	.QWk  ]VV( QQf   .      .     QQk  )YT`-C.-? -Y  .                         
	.QWk       WQmymmgc  <wgmggc. QQk       wgz  = gygmgwagmmgc                
	.QWk  jQQ[ WQQQQQQW;jWQQ  QQL QQk  ]WQ[ dQk  ) QF~"WWW(~)QQ[               
	.QWk  jQQ[ QQQ  QQQ(mWQ9VVVVT QQk  ]WQ[ mQk  = Q;  jWW  :QQ[               
	 WWm,,jQQ[ QQQQQWQW']WWa,_aa. $Qm,,]WQ[ dQm,sj Q(  jQW  :QW[               
	 -TTT(]YT' TTTYUH?^  ~TTB8T!` -TYT[)YT( -?9WTT T'  ]TY  -TY(               
					 
						  www.libelium.com


	Libelium Comunicaciones Distribuidas SL

	Autor: Joaquín Ruiz

	http://2.139.174.70:11111/meshlium/rest/services/Libelium/FeatureServer
*/

//	Encode image in base 64
function base64_encode_image ($filename=string,$filetype=string) {
	if ($filename) {
		$imgbinary = fread(fopen($filename, "r"), filesize($filename));
		return base64_encode($imgbinary);
	}
}

/* 0 -> Meshlium*/

function getFieldsMesh () {
	$fields = array();
	$fields[0]["name"]="OBJECTID";
	$fields[0]["type"]="esriFieldTypeOID";
	$fields[0]["alias"]="OBJECT ID";
	$fields[1]["name"]="name";
	$fields[1]["type"]="esriFieldTypeString";
	$fields[1]["alias"]="Name";
	$fields[2]["name"]="description";
	$fields[2]["type"]="esriFieldTypeString";
	$fields[2]["alias"]="Description";
	return $fields;
}
function getTemplatesMesh () {
	$fields = array();
	$fields[0]["name"]="Meshlium";
	$fields[0]["description"]="";
	$fields[0]["prototype"]["attributes"]["name"]=null;
	$fields[0]["prototype"]["attributes"]["description"]=null;
	$fields[0]["drawingTool"]="esriFeatureEditToolPoint";
	return $fields;
}
$defs[0]["id"]=0;
$defs[0]["name"]="Meshlium";
$defs[0]["url"]="meshlium";
$defs[0]["imageData"]=base64_encode_image('mesh.png','png');
$defs[0]["width"]=20;
$defs[0]["height"]=20;
$defs[0]["fields"]=getFieldsMesh();
$defs[0]["templates"]=getTemplatesMesh();
$defs[0]["timeInfo"]=null;
$defs[0]["sql"]="SELECT objectid as OBJECTID, name as name, description as description, x as x, y as y, spatialReference as spatialReference FROM meshlium";
$defs[0]["sql2"]="SELECT OBJECTID FROM meshlium";
/* 1 -> Waspmote*/

function getFieldsWasp () {
	$fields = array();
	$fields[0]["name"]="OBJECTID";
	$fields[0]["type"]="esriFieldTypeOID";
	$fields[0]["alias"]="OBJECT ID";
	$fields[1]["name"]="meshliumid";
	$fields[1]["type"]="esriFieldTypeOID";
	$fields[1]["alias"]="Meshlium ID";
	$fields[2]["name"]="name";
	$fields[2]["type"]="esriFieldTypeString";
	$fields[2]["alias"]="Name";
	$fields[3]["name"]="description";
	$fields[3]["type"]="esriFieldTypeString";
	$fields[3]["alias"]="Description";
	$fields[4]["name"]="timestamp";
	$fields[4]["type"]="esriFieldTypeDate";
	$fields[4]["alias"]="Timestamp";
	$fields[5]["name"]="sensorCount";
	$fields[5]["type"]="esriFieldTypeInteger";
	$fields[5]["alias"]="Sensor Count";
	$iSR=0;
	while ($iSR<10)
	{
		$fields[] = array( "name" => "sensorReading".$iSR, "type" => "esriFieldTypeString", "alias" => "Sensor Reading".$iSR );
		$fields[] = array( "name" => "sensorId".$iSR, "type" => "esriFieldTypeOID", "alias" => "Sensor ID ".$iSR );
		$iSR++;
	}
	return $fields;
}
function getTemplatesWasp () {
	$fields = array();
	$fields[0]["name"]="Waspmote";
	$fields[0]["description"]="";
	$fields[0]["prototype"]["attributes"]["Nombre"]=null;
	$fields[0]["prototype"]["attributes"]["Descripcion"]=null;
	$fields[0]["prototype"]["attributes"]["TimeStamp"]=null;
	$fields[0]["drawingTool"]="esriFeatureEditToolPoint";
	return $fields;
}
function getTimeInfoWasp () {
	$fields = array();
	$fields['startTimeField'] = "TimeStamp";
	$fields['endTimeField'] = null;
	$fields["trackIdField"] = null;
	$fields["timeExtent"] = array(
		strtotime("-1week")*1000,
		strtotime("now")*1000
	);
	$fields["timeReference"] = null;
  	$fields["timeInterval"] = 5;
  	$fields["timeIntervalUnits"] = "esriTimeUnitsMinutes";
 	$fields["exportOptions"]["useTime"] = true;
 	$fields["exportOptions"]["timeDataCumulative"] = false;
 	$fields["exportOptions"]["timeOffset"] = null;
 	$fields["exportOptions"]["timeOffsetUnits"] = null;
	$fields["hasLiveData"] = true;
	return $fields;
}
$defs[1]["id"]=1;
$defs[1]["name"]="Waspmote";
$defs[1]["url"]="waspmote";
$defs[1]["imageData"]=base64_encode_image('psense.png','png');
$defs[1]["width"]=15;
$defs[1]["height"]=15;
$defs[1]["fields"]=getFieldsWasp();
$defs[1]["templates"]=getTemplatesWasp();
$defs[1]["timeInfo"]=getTimeInfoWasp();
$defs[1]["sql"]="SELECT OBJECTID as OBJECTID, name as name, description as description, x as x, y as y, spatialReference as spatialReference, sensorCount as sensorCount, meshliumid as meshliumid FROM waspmote";
$defs[1]["sql2"]="SELECT OBJECTID FROM waspmote";

/* 2 -> Sensor*/

function getFieldsSens () {
	$fields = array();
	$fields[0]["name"]="OBJECTID";
	$fields[0]["type"]="esriFieldTypeOID";
	$fields[0]["alias"]="OBJECT ID";
	$fields[1]["name"]="waspmoteid";
	$fields[1]["type"]="esriFieldTypeOID";
	$fields[1]["alias"]="Waspmote ID";
	$fields[2]["name"]="name";
	$fields[2]["type"]="esriFieldTypeString";
	$fields[2]["alias"]="Name";
	$fields[3]["name"]="description";
	$fields[3]["type"]="esriFieldTypeString";
	$fields[3]["alias"]="Description";
	$fields[4]["name"]="timestamp";
	$fields[4]["type"]="esriFieldTypeDate";
	$fields[4]["alias"]="Timestamp";
	$fields[5]["name"]="Sensor Reading";
	$fields[5]["type"]="esriFieldTypeString";
	$fields[5]["alias"]="Sensor Reading";
	$fields[6]["name"]="sensorType";
	$fields[6]["type"]="esriFieldTypeString";
	$fields[6]["alias"]="Sensor Type";
	$fields[7]["name"]="sensorValue";
	$fields[7]["type"]="esriFieldTypeDouble";
	$fields[7]["alias"]="Sensor Value";
	$fields[8]["name"]="extendedValue";
	$fields[8]["type"]="esriFieldTypeString";
	$fields[8]["alias"]="Extended Sensor Value";
	$fields[9]["name"]="units";
	$fields[9]["type"]="esriFieldTypeString";
	$fields[9]["alias"]="Units";
	return $fields;
}
function getTemplatesSens () {
	$fields = array();
	$fields[0]["name"]="Sensor";
	$fields[0]["description"]="";
	$fields[0]["prototype"]["attributes"]["Nombre"]=null;
	$fields[0]["prototype"]["attributes"]["Descripcion"]=null;
	$fields[0]["prototype"]["attributes"]["TimeStamp"]=null;
	$fields[0]["drawingTool"]="esriFeatureEditToolPoint";
	return $fields;
}
function getTimeInfoSens () {
	$fields = array();
	$fields['startTimeField'] = "TimeStamp";
	$fields['endTimeField'] = null;
	$fields["trackIdField"] = null;
	$fields["timeExtent"] = array(
		strtotime("-1week")*1000,
		strtotime("now")*1000
	);
	$fields["timeReference"] = null;
  	$fields["timeInterval"] = 5;
  	$fields["timeIntervalUnits"] = "esriTimeUnitsMinutes";
 	$fields["exportOptions"]["useTime"] = true;
 	$fields["exportOptions"]["timeDataCumulative"] = false;
 	$fields["exportOptions"]["timeOffset"] = null;
 	$fields["exportOptions"]["timeOffsetUnits"] = null;
	$fields["hasLiveData"] = true;
	return $fields;
}
$defs[2]["id"]=2;
$defs[2]["name"]="Sensor";
$defs[2]["url"]="sensor";
$defs[2]["imageData"]=base64_encode_image('sensor.png','png');
$defs[2]["width"]=15;
$defs[2]["height"]=15;
$defs[2]["fields"]=getFieldsSens();
$defs[2]["templates"]=getTemplatesSens();
$defs[2]["timeInfo"]=getTimeInfoSens();
$defs[2]["sql"]="SELECT OBJECTID as OBJECTID, waspmoteid as waspmoteid, name as name, description as description, 
						sensorReading as sensorReading, sensorType as sensorType, sensorValue as sensorValue, extendedValue as extendedValue,
						units as units, timestamp as timestamp, x as x, y as y FROM currentSensors";
$defs[2]["sql2"]="SELECT OBJECTID FROM currentSensors";
?>
