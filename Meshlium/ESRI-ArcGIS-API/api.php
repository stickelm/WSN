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
	 WWm,,jQQ[ QQQQQWQW')WWa,_aa. $Qm,,]WQ[ dQm,sj Q(  jQW  :QW[               
	 -TTT(]YT' TTTYUH?^  ~TTB8T!` -TYT[)YT( -?9WTT T'  ]TY  -TY(               
					 
						  www.libelium.com


	Libelium Comunicaciones Distribuidas SL

	Autor: Joaquín Ruiz

	http://2.139.174.70:11111/meshlium/rest/services/Libelium/FeatureServer
*/

require_once("Rest.inc.php");
require_once("defs.php");

//  Get parameter ($_GET o $_POST)
function getParameter($par,$default=null){
	if(isset($_GET[$par]) && strlen($_GET[$par]))
		return $_GET[$par];
	elseif(isset($_POST[$par]) && strlen($_POST[$par]))
		return $_POST[$par];
	else
		return $default;
}

function parseSpatialReference($sr)
{
	$wkid = intval($sr);
	if($wkid)
	{
		return $wkid;
	}
	else
	{
		$sr = json_decode($sr);
		return $sr->wkid;
	}
}

function parseGeometry($geoq)
{
	$geometry = json_decode(stripslashes($geoq));
	if(!$geometry)
	{
		$coords = split(',',$geoq);
		$geometry->xmin=$coords[0];
		$geometry->ymin=$coords[1];
		$geometry->xmax=$coords[2];
		$geometry->ymax=$coords[3];
	}
	return $geometry; 
}

// xToLng
function xToLng($lng){
	return ($lng / 20037508.34) * 180;
}
// yToLat
function yToLat($lat){
	$yweno = ($lat / 20037508.34) * 180;
	$yweno = 180/M_PI * (2 * atan(exp($yweno * M_PI / 180)) - M_PI / 2);
	return $yweno;
}
//lngToX
function lngToX($x){
	return (floatval($x) * 20037508.34) / 180;
}
//latToY
function latToY($y){
	$ymeters=log(tan((90 + floatval($y)) * M_PI / 360)) / (M_PI / 180);
	$ymeters=$ymeters* 20037508.34 / 180;
	return $ymeters;
}
	
class API extends REST {

	public $data = "";
	
	const DB_SERVER = "localhost";
	const DB_USER = "root";
	const DB_PASSWORD = "libelium2007";
	const DB = "MeshliumDB";
	
	private $db = NULL;
	
	private $extent_xmin = -0.02;
	private $extent_xmax = 0.02;
	private $extent_ymin = -0.02;
	private $extent_ymax = 0.02;	
	
	/****************** Basic Functions *******************/
	public function __construct(){
		parent::__construct();			// Init parent contructor
		$this->dbConnect();				// Initiate Database connection
		$this->takeFullExtent();		// Get center of Map (Meshlium position)
	}

	//	map extent
	private function takeFullExtent()
	{
		$sql0 = mysql_query("SELECT min(x) xmin, min(y) ymin, max(x) xmax, 
			max(y) ymax FROM meshlium LIMIT 1", $this->db);
		$row0 = mysql_fetch_array($sql0);			
		$this->extent_xmin = floatval($row0["xmin"]) - 0.001;
		$this->extent_xmax = floatval($row0["xmax"]) + 0.001;
		$this->extent_ymin = floatval($row0["ymin"]) - 0.001;
		$this->extent_ymax = floatval($row0["ymax"]) + 0.001;
	}

	//  Database connection 
	private function dbConnect(){
		$this->db = mysql_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD);
		if($this->db)
        {
			if (!mysql_select_db(self::DB))
			{
     			die('Could not select Database: '.mysql_error());
			}
        }
        if (!$this->db)
	    {
        	die('Could not connect to Database: '.mysql_error());
        }			
	}

	//	Encode array into JSON
	private function json($data){
		if(is_array($data)){
			if (getParameter('f')) {
				if (getParameter('callback')) {
					return getParameter('callback')."(".json_encode($data).");";
				}
				switch(getParameter('f')){
					case "json":
						return json_encode($data);
						break;
					case "pjson":
						if(defined(JSON_PRETTY_PRINT))
						{	
							return json_encode($data, JSON_PRETTY_PRINT);
						}
						else
						{	
							return json_encode($data);
						}
						break;						
				}
			}
		}
	}

	//	Encode image in base 64
	function base64_encode_image ($filename=string,$filetype=string) {
		if ($filename) {
			$imgbinary = fread(fopen($filename, "r"), filesize($filename));
			return base64_encode($imgbinary);
		}
	}

	//	Initialite array.

	function createArray ($id) {
		global $defs;
		$fields = array();
		$fields["currentVersion"] = 10.11;
		$fields["id"] = $defs[$id]["id"];
		$fields["name"] = $defs[$id]["name"]; 
		$fields["serviceName"]    = "Libelium";
		$fields["serviceUrl"] 	   = "/meshlium/rest/services/" . $fields['serviceName'] . '/FeatureServer';
		$fields["fullServiceUrl"] = "http://" . $_SERVER['HTTP_HOST']. $fields['serviceUrl'];
		return $fields;
	}

	// Public method for access api.
	public function processApi(){
		$words = explode("/",$_REQUEST['rquest']);
		$func = strtolower(trim(str_replace("/","",$words[0])));
		$serverSP = explode(":",$_SERVER['HTTP_HOST']);
		/*INFO*/
		if ($func =="info"){
			$this->info($words[1],$words[2],$words[3],$words[4],$words[5]);
		}
		else if ($func =="generatetoken"){
			$this->generateToken();
		}
		/*FEATURE SERVER*/
		else if((int)method_exists($this,$func) > 0){
			if(file_exists("../../ManagerSystem/plugins/b_SensorData/f0_map/data/security")/*&&
				($serverSP[0]!=$_SERVER["REMOTE_ADDR"])*/) 
			{
				//error_log($serverSP[0]." -vs- ".$_SERVER["REMOTE_ADDR"]);
				if (getParameter("token")==null)
				{
					$answerm = array();
					$answerm["error"]["code"]=499;
					$answerm["error"]["message"]="Token Required";
					$answerm["error"]["messageCode"]="GWM_0003";
					$answerm["error"]["details"][]="Token Required";
					$this->responseWithTemplate($answerm,"info.html", 403);
				}
				else{
					$this->checkToken(getParameter("token"));
				}
			}
			$this->$func($words[1],$words[2],$words[3],$words[4],$words[5]);
		}
		/*404 NOT FOUND*/
		else
			$this->response('',404);	// response would be "Page not found".
	}
	
	/****************************************************/
	private function checkToken($token)
	{
		if (rand(0,10)==5)
		{	//clean old tokens 1/10 times
			$sql = mysql_query("DELETE from tokens WHERE expires < '".time()."';", $this->db);
		}
	
		$sql = mysql_query("SELECT * from tokens WHERE token = '".$token."';", $this->db);
		$row = mysql_fetch_array($sql);
		/* check if exists*/
		if ($token=="visor") return 0;
		if ($row["token"]!=null){
			if (($row["expires"]!=null)&&(time()<$row["expires"]))
			{
				if ($row["referer"]!=null)
				{	/*comprobar que el valor del header “Referer” de la petición coincide 
					con el referer para el cual se solicitó el token*/
					if($_SERVER["HTTP_REFERER"]==$row['ip'])
						return 0; //OK!
				}
				else if($row["ip"]!=null)
				{	/*comprobar que la petición viene efectivamente de la IP a la cual se 
					le concedió el token*/
					if ($_SERVER['REMOTE_ADDR']==$row['ip'])
						return 0; //OK!
				}
				else return 0;
			}
		}
		/*bad*/
		$answerm = array();
		$answerm["error"]["code"]=498;
		$answerm["error"]["message"]="Invalid token";
		$answerm["error"]["details"][]="Invalid token.";
		$this->responseWithTemplate($answerm,"info.html", 200);	
	}

	/****************** rest/services *******************/
	private function services($id,$id1,$id2,$id3,$id4)
	{	
		$answer = array();
		$answer["currentVersion"] = 10.11;
		if (!isset($id)){
			$answer["folders"]=array();
			$answer["services"][0]["name"]="Libelium";
			$answer["services"][0]["type"]="FeatureServer";
			$this->_template = "services.html";
		}
		else {
			switch($id){
				case "Libelium":
					if(!isset($id1)){
						header("Location: $id/FeatureServer");
						exit();
					}
					elseif (strstr($id1,"FeatureServer")){
						$answer = $this->libelium($id2,$id3,$id4);
					}
					else
						$this->response('',404);
				break;
				default:
					$answer["folders"]=array();
					$answer["services"][0]["name"]="Libelium";
					$answer["services"][0]["type"]="FeatureServer";
					$this->_template = "services.html";
				break;
			}

		}
		$this->responseWithTemplate($answer,$this->_template, 200);
	}
	
	/****************** rest/generateToken *******************/
	private function generateToken()
	{
		$answer = array();
		$username = getParameter('username');
		$pass = getParameter('password');
		$ref = getParameter('referer');
		$cli = getParameter('client');

		if (getParameter('request')=="getToken")
			$ref="http://www.arcgis.com";

		if ($username ==null || $pass == null || ($ref == null && $cli ==null) || 
			(($cli=="ip" || $cli=="requestip") && getParameter('ip')==null)) {
			$answer["error"]["code"]=400;
			$answer["error"]["message"]="Unable to generate token.";
			if ($username == null) 
				$answer["error"]["details"][]="'username' must be specified.";
			if ($pass == null) 
				$answer["error"]["details"][]="'password' must be specified.";
			if ($ref == null && $cli ==null) 
				$answer["error"]["details"][]="'referer' must be specified.";
			if (($cli=="ip" || $cli=="requestip") && getParameter('ip')==null) 
				$answer["error"]["details"][]="'ip' must be specified.";
		}
		else
		{
			$sql = mysql_query("SELECT * from users WHERE user = '".$username."';", $this->db);
			$row = mysql_fetch_array($sql);

			if (($row["user"]==null) || ((crypt($pass, $row["passwd"]) != $row["passwd"])))
	        {
				$answer["error"]["code"]=400;
				$answer["error"]["message"]="Unable to generate token.";
				$answer["error"]["details"][]="Invalid username or password.";
	        }
	        else
	        {
		        /* SI LLEGA AQUI PASS OK*/
				$client = getParameter('client','referer');
				switch($client){
					case "referer":
						$ref= getParameter('referer');
						break;
					case "ip":
					case "requestip":
						$ip = getParameter('ip');
						break;
				}
				$exp = getParameter('expiration',60);
				$expires=  time() + $exp*60;
				$answer["token"]=md5(rand());
				$answer["expires"]=$expires;

				$sql = mysql_query("INSERT INTO tokens (token, expires, referer, ip) 
					VALUES ('".$answer["token"]."','".$expires."','".$ref."','".$ip."');", $this->db);
	        }
		} 
		$this->responseWithTemplate($answer,"info.html",200);
	}

	/****************** rest/info *******************/
	private function info($id,$id1,$id2,$id3)
	{	
		$answer = array();
		$answer["currentVersion"]=10.11;
		$answer["fullVersion"]="10.1.1";
		$answer["soapUrl"]="not supported";
		$answer["secureSoapUrl"]=null;
		$answer["authInfo"]["isTokenBasedSecurity"]=true;
		$answer["authInfo"]["tokenServicesUrl"]="http://".$_SERVER['HTTP_HOST']."/meshlium/rest/generateToken";
		$answer["authInfo"]["shortLivedTokenValidity"]=60;
		$this->responseWithTemplate($answer,"info.html",200);
	}
	
	/****************** rest/services/Libelium/ *******************/
	private function libelium($id,$idQ,$idImg)
	{
		$answerm = array();
		$answerm["serviceName"]    = "Libelium";
		$answerm["serviceUrl"] 	   = "/meshlium/rest/services/".$answerm['serviceName'].'/FeatureServer';
		$answerm["fullServiceUrl"] = "http://".$_SERVER['HTTP_HOST'].$answerm['serviceUrl'];
		$answerm["currentVersion"] = 10.11;
		if (!isset($id))
		{
			$answerm["allowGeometryUpdates"]=false;
			$answerm["capabilities"]="Query";
			$answerm["copyrightText"]="";
			$answerm["description"]="Meshlium Location point";
			$answerm["documentInfo"]["Title"]="Meshlium position";
			$answerm["documentInfo"]["Author"]="Joaquín Ruiz";
			$answerm["documentInfo"]["Comments"]="meshlium, libelium";
			$answerm["documentInfo"]["Subject"]="meshlium, libelium";
			$answerm["documentInfo"]["Category"]="";
			$answerm["documentInfo"]["Keywords"]="meshlium, libelium";
			$answerm["enableZDefaults"]=false;
			$answerm["fullExtent"]["spatialReference"]["wkid"]=4326;

			$answerm["fullExtent"]["xmin"]=$this->extent_xmin;
			$answerm["fullExtent"]["ymin"]=$this->extent_ymin;
			$answerm["fullExtent"]["xmax"]=$this->extent_xmax;
			$answerm["fullExtent"]["ymax"]=$this->extent_ymax;
			$answerm["hasVersionedData"]=false;
			$answerm["initialExtent"]["xmin"]=$this->extent_xmin;
			$answerm["initialExtent"]["ymin"]=$this->extent_ymin;
			$answerm["initialExtent"]["xmax"]=$this->extent_xmax;
			$answerm["initialExtent"]["ymax"]=$this->extent_ymax;
			
			$answerm["initialExtent"]["spatialReference"]["wkid"]=4326;
			$answerm["layers"][0]["id"]=0;
			$answerm["layers"][0]["name"]="Meshlium";
			$answerm["layers"][1]["id"]=1;
			$answerm["layers"][1]["name"]="Waspmote";
			$answerm["layers"][2]["id"]=2;
			$answerm["layers"][2]["name"]="Sensor";
			$answerm["maxRecordCount"]=1000;
			$answerm["serviceDescription"]="libelium";
			$answerm["spatialReference"]["wkid"]=4326;
			$answerm["supportedQueryFormats"]="JSON";
			$answerm["supportsDisconnectedEditing"]=true;
			$answerm["units"]="esriMeters";
			$answerm["tables"] = array();
			$this->_template = "featureservice.html";
		}
		else{
			if(!isset($idQ)) {
				$answerm = $this->definition($id);
			}
			else if(strstr($idQ,"images")){
				$answerm = $this->image($id,$idImg);
			}
			else if(strstr($idQ,"query")) { 
				$answerm = $this->query($id);
			}
		}	
		return $answerm;
	}

	/****************** DEFINITION *******************/
	private function definition($id)
	{
		global $defs;

		$answerm = $this->createArray($id);

		$this->_template = "featurelayer.html";
		$answerm["allowGeometryUpdates"]=false;
		$answerm["capabilities"]="Query";
		$answerm["copyrightText"]="";
		$answerm["defaultVisibility"]=true;						
		$answerm["description"]="";
		$answerm["displayField"]="name";
		$answerm["drawingInfo"]["renderer"]["type"]="simple";
		$answerm["drawingInfo"]["renderer"]["symbol"]["type"]="esriPMS";
		$answerm["drawingInfo"]["renderer"]["symbol"]["url"]=$defs[$id]["url"];
		$answerm["drawingInfo"]["renderer"]["symbol"]["imageData"]=$defs[$id]["imageData"];
		$answerm["drawingInfo"]["renderer"]["symbol"]["contentType"]="image/png";
		$answerm["drawingInfo"]["renderer"]["symbol"]["width"]=$defs[$id]["width"];
		$answerm["drawingInfo"]["renderer"]["symbol"]["height"]=$defs[$id]["height"];
		$answerm["drawingInfo"]["renderer"]["symbol"]["angle"]=0;
		$answerm["drawingInfo"]["renderer"]["symbol"]["xoffset"]=0;
		$answerm["drawingInfo"]["renderer"]["symbol"]["yoffset"]=0;
		$answerm["drawingInfo"]["renderer"]["label"]="";
		$answerm["drawingInfo"]["renderer"]["description"]="";
		$answerm["drawingInfo"]["transparency"]=0;
		$answerm["drawingInfo"]["labelingInfo"]=null;
		$answerm["editFieldsInfo"]=null;
		$answerm["extent"]["xmin"]=$this->extent_xmin;
		$answerm["extent"]["ymin"]=$this->extent_ymin;
		$answerm["extent"]["xmax"]=$this->extent_xmax;
		$answerm["extent"]["ymax"]=$this->extent_ymax;
		$answerm["extent"]["spatialReference"]["wkid"]=4326;
		$answerm["fields"]=$defs[$id]["fields"];
		$answerm["geometryType"]="esriGeometryPoint";
		$answerm["objectIdField"]="OBJECTID";
		$answerm["globalIdField"]="GlobalID";
		$answerm["hasAttachments"]=false;
		$answerm["hasM"]=false;
		$answerm["hasZ"]=false;
		$answerm["htmlPopupType"]="esriServerHTMLPopupTypeAsHTMLText";				
		$answerm["id"]=$defs[$id]["id"];
		$answerm["isDataVersioned"]=false;
		$answerm["minScale"]=0;
		$answerm["maxScale"]=0;
		$answerm["maxRecordCount"]=1000;
		$answerm["name"]= $defs[$id]["name"];
		$answerm["ownershipBasedAccessControlForFeatures"]=null;
		$answerm["relationships"]=array();
		$answerm["supportedQueryFormats"]="JSON";
		$answerm["supportsAdvancedQueries"]=true;
		$answerm["supportsRollbackOnFailureParameter"]=false;
		$answerm["supportsStatistics"]=false;
		$answerm["syncCanReturnChanges"]=false;
		$answerm["templates"]=$defs[$id]["templates"];
		$answerm["type"]= "Feature Layer";
		$answerm["typeIdField"]="";
		$answerm["types"]=array();
		$answerm["timeInfo"]=$defs[$id]["timeInfo"];
		return $answerm;
	}

	/****************** IMAGE *******************/
	private function image($id,$idImg)
	{
		global $defs;

		$answerm = $this->createArray($id);
		if (($id==0)&&strstr($idImg,"meshlium")) {
			$imgsrc = $defs[$id]["imageData"];
			$this->responseImg(base64_decode($imgsrc),200);
		}
		else if (($id==1)&&strstr($idImg,"waspmote")) {
			$imgsrc = $defs[$id]["imageData"];
			$this->responseImg(base64_decode($imgsrc),200);
		}
		else if (($id==2)&&strstr($idImg,"sensor")) {
			$imgsrc = $defs[$id]["imageData"];
			$this->responseImg(base64_decode($imgsrc),200);
		}
		return $answerm;
	}

	/****************** QUERY *******************/
	private function query($id)
	{			
		global $defs;

		$answerm = $this->createArray($id);
		$this->_template = "query.html";

		if ($id==2)
		{	// PREPARE SENSORS
			mysql_query("TRUNCATE TABLE currentSensors", $this->db);
		
			$sql = mysql_query("SELECT OBJECTID as OBJECTID, x as x, y as y, name as name, 
				sensorCount as sensorCount FROM waspmote", $this->db);
			while($row = mysql_fetch_array($sql))
			{
				$sql2 = mysql_query("SELECT sensor as sensor, value as value, 
					unix_timestamp(timestamp)*1000 js_timestamp FROM sensorParser 
					WHERE id_wasp='".$row["name"]."' ORDER BY timestamp DESC LIMIT ".$row["sensorCount"], $this->db);
				while($row2 = mysql_fetch_array($sql2))
				{	
					$sql3 = mysql_query("SELECT id as id, name as name, description as description, 
						units as units, value as value FROM sensors WHERE id_ascii='".$row2["sensor"]."' LIMIT 1", $this->db);
					$row3 = mysql_fetch_array($sql3);
					
					mysql_query("INSERT INTO currentSensors VALUES (".(floatval($row["OBJECTID"])*1000+floatval($row3["id"])).",".
						floatval($row["OBJECTID"]).",'".$row3["name"]."_".$row["OBJECTID"]."','".$row3["description"]."','".
						$row3["name"]." ".$row2["value"]." ".utf8_encode($row3["units"])."','".$row2["sensor"]."','".
						(($row3["value"]!=3)? floatval($row2["value"]): NULL)."','".
						(($row3["value"]!=3)? NULL: $row2["value"])."','".
						$row3["units"]."','".$row2["js_timestamp"]."',".$row["x"].",".$row["y"]." )",$this->db);
				}
			}
		}
		$answerm["objectIdFieldName"]="OBJECTID";

		$where = null;
		if( getParameter('where') )
		{
			$where = getParameter('where');
			$where = str_replace('"', '', $where);
			$where = str_replace('\\', '', $where);
		}

		if (getParameter('returnIdsOnly','false')=="false")
		{			
			$answerm["globalIdFieldName"]="";
			$answerm["features"]=array();
			
			$geoq = getParameter('geometry','{"xmin":-180.0,"xmax":180.0,"ymin":-90.0,"ymax":90.0,"spatialReference":{"wkid":4326}}');
			$geometry = parseGeometry($geoq);
			
			$inSR  = parseSpatialReference( getParameter('inSR',4326) );
			$outSR = parseSpatialReference( getParameter('outSR',4326) );
			if ($inSR == 102100)
			{
				$xminweno=xToLng($geometry->xmin);
				$yminweno=yToLat($geometry->ymin);
				$xmaxweno=xToLng($geometry->xmax);
				$ymaxweno=yToLat($geometry->ymax);
			}
			else
			{
				$xmaxweno = $geometry->xmax;
				$ymaxweno = $geometry->ymax;
				$xminweno = $geometry->xmin;
				$yminweno = $geometry->ymin;
			}

			// $outFields
			$outFields = split(",",getParameter('outFields','*'));
			// orderBy
			$orderBy = (getParameter('orderByFields')!=null)? " ORDER BY ".getParameter('orderByFields'): "";
			// where and objectids check
			if (getParameter('objectIds')!=null)
			{
				$objectIds = mysql_real_escape_string(getParameter('objectIds'));
				$objectIds = split(",",$objectIds);
				
				$sql = mysql_query($defs[$id]["sql"]." WHERE OBJECTID IN (".join(',',$objectIds).")" .
					" ORDER BY OBJECTID", $this->db);
			}
			else
			{					
				$sql = mysql_query($defs[$id]["sql"].($where? " WHERE ".$where : "") .$orderBy, $this->db);
			}
			$i=0;
			
			while($row = mysql_fetch_array($sql))
			{			
				if (($row["x"] >= $xminweno)&&($row["x"] <= $xmaxweno)
				&&($row["y"] >= $yminweno)&&($row["y"] <= $ymaxweno))
				{
					if ($outSR == 102100)
					{
						$xmeters=lngToX($row["x"]);
						$ymeters=latToY($row["y"]);
						$wkid = 102100;
					}
					else
					{
						$xmeters=floatval($row["x"]);
						$ymeters=floatval($row["y"]);
						$wkid=4326;
					}
					
					$answerm["geometryType"]="esriGeometryPoint";
					$answerm["spatialReference"]["wkid"]=$wkid;
					
					$answerm["fields"]=$defs[$id]["fields"];

					// returnGeometry check
					if ((getParameter('returnGeometry')==null)||(getParameter('returnGeometry')=="true"))
					{
						$answerm["features"][$i]["geometry"]["x"]=$xmeters;
						$answerm["features"][$i]["geometry"]["y"]=$ymeters;
					}

					$answerm["features"][$i]["attributes"]["OBJECTID"]=floatval($row["OBJECTID"]);
					switch($id){
						case 0:
						/* MESHLIUM QUERY */
							if (in_array('*',$outFields)||in_array("name",$outFields))
								$answerm["features"][0]["attributes"]["name"]=$row["name"];
							if (in_array('*',$outFields)||in_array("description",$outFields))
								$answerm["features"][0]["attributes"]["description"]=$row["description"];
							break;
						case 1:
						/* WASPMOTE QUERY */
							if (in_array('*',$outFields)||in_array("meshliumid",$outFields))
								$answerm["features"][$i]["attributes"]["meshliumid"]=floatval($row["meshliumID"]);
							if (in_array('*',$outFields)||in_array("name",$outFields))
								$answerm["features"][$i]["attributes"]["name"]=$row["name"];
							if (in_array('*',$outFields)||in_array("description",$outFields))
								$answerm["features"][$i]["attributes"]["description"]=$row["description"];
							if (in_array('*',$outFields)||in_array("sensorCount",$outFields))
								$answerm["features"][$i]["attributes"]["sensorCount"]=floatval($row["sensorCount"]);
							
							$sql2 = mysql_query("SELECT sensor as sensor, value as value, unix_timestamp(timestamp)*1000 js_timestamp FROM sensorParser WHERE id_wasp='".$row["name"]."' ORDER BY timestamp DESC LIMIT ".$row["sensorCount"], $this->db);
							$iX2=0;
							while($row2 = mysql_fetch_array($sql2))
							{	
								if (in_array('*',$outFields)||in_array("timestamp",$outFields))
									$answerm["features"][$i]["attributes"]["timestamp"]=floatval($row2["js_timestamp"]);
								$sql3 = mysql_query("SELECT name as name, units as units, id as id FROM sensors WHERE id_ascii='".$row2["sensor"]."' LIMIT 1", $this->db);
								$row3 = mysql_fetch_array($sql3);
								if (in_array('*',$outFields)||in_array("sensorReading".$iX2,$outFields))
									$answerm["features"][$i]["attributes"]["sensorReading".$iX2]=$row3["name"]." ".$row2["value"]." ".$row3["units"];
								if (in_array('*',$outFields)||in_array("Sensor ID ".$iX2,$outFields))
									$answerm["features"][$i]["attributes"]["sensorId".$iX2]=floatval($row["objectID"])*1000+floatval($row3["id"]);
								$iX2++;
							}
							while( $iX2 < 10 )
							{
								if (in_array('*',$outFields)||in_array("sensorReading".$iX2,$outFields))
									$answerm["features"][$i]["attributes"]["sensorReading".$iX2]=null;
								if (in_array('*',$outFields)||in_array("sensorId".$iX2,$outFields))
									$answerm["features"][$i]["attributes"]["sensorId".$iX2]=null;
								$iX2++;
							}
							break;
						case 2:
						/* SENSOR QUERY */
							if (in_array('*',$outFields)||in_array("waspmoteid",$outFields))
								$answerm["features"][$i]["attributes"]["waspmoteid"]=floatval($row["waspmoteid"]);
							if (in_array('*',$outFields)||in_array("name",$outFields))
								$answerm["features"][$i]["attributes"]["name"]=$row["name"];
							if (in_array('*',$outFields)||in_array("description",$outFields))
								$answerm["features"][$i]["attributes"]["description"]=$row["description"];
							if (in_array('*',$outFields)||in_array("timestamp",$outFields))
								$answerm["features"][$i]["attributes"]["timestamp"]=floatval($row["timestamp"]);
							if (in_array('*',$outFields)||in_array("sensorReading",$outFields))
								$answerm["features"][$i]["attributes"]["sensorReading"]=$row["sensorReading"];
							if (in_array('*',$outFields)||in_array("sensorType",$outFields))
								$answerm["features"][$i]["attributes"]["sensorType"]=$row["sensorType"];
							if (in_array('*',$outFields)||in_array("sensorValue",$outFields))
								$answerm["features"][$i]["attributes"]["sensorValue"]=$row["sensorValue"];
							if (in_array('*',$outFields)||in_array("extendedValue",$outFields))
								$answerm["features"][$i]["attributes"]["extendedValue"]=$row["extendedValue"];
							if (in_array('*',$outFields)||in_array("units",$outFields))
								$answerm["features"][$i]["attributes"]["units"]=utf8_encode($row["units"]);
							break;
					}
					$i++;
				}
			}
		}
		else
		{	//ObjectId only
			$sql = mysql_query($defs[$id]["sql2"].($where? " WHERE ".$where : "") ." ORDER BY OBJECTID", $this->db);
			$i=0;
			while($row = mysql_fetch_array($sql))
			{
				$answerm['objectIds'][$i]=intval($row['OBJECTID']); $i++;
			}
		}

		return $answerm;
	}
	
	public function responseWithTemplate($data,$template,$status)
	{
		$format = getParameter('f','html');
		$this->_code = ($status)?$status:200;
		if($format == 'html')
		{
			$this->_content_type = "text/html;charset=utf-8";
			$this->set_headers();
			extract($data);
			include("templates/$template");
			exit();
		}
		else
		{
			$this->_content_type = "text/plain;charset=utf-8";
			$this->set_headers();
			$json = $this->json($data);
			print($json);
			exit();
		}
	}
}
	
// Initiate Library
if( getParameter('debug') )
{
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	print "<pre>";
}
date_default_timezone_set('Europe/Madrid');
$api = new API;
$api->processApi();

?>
