<?php
// Allowed HTTP Headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: origin, x-requested-with, content-type");

$words = (isset($_REQUEST['rquest']) ? explode("/",$_REQUEST['rquest']) : null);
$sensorID = strtoupper(trim(str_replace("/","",$words[0])));
$method = strtolower(trim(str_replace("/","",$words[1])));
$sensorType = strtoupper(trim(str_replace("/","",$words[2])));

$sensor_array = array("A01","A02","A03","A04","A05","A06","A07","A08","A09",
                    "B10","B11","B12","B13","B14E","B15","B16","B17","B18",
                    "C19","C20","C21","C22","C23","C24","C25","C26","C27",
                    "D28","D29","D30","D31","D32","D33","D34","D35");
//$method_array = array("hour","day","month","update");
$method_array = array("hour","day","month");
$sensorType_array = array("TCA","BAT","LUM","MCP","HUMA","DUST");

if (($_SERVER['REQUEST_METHOD']) == "GET" && isset($_GET['rquest']) && strlen($_GET['rquest'])) {
    if (in_array($sensorID,$sensor_array) && in_array($method,$method_array) 
        && in_array($sensorType,$sensorType_array)) {
        if ($method == "hour") { //select id_wasp,sensor,round(avg(value),2),date(timestamp) as date,hour(timestamp) as hour from sensorParser where id_wasp = 'a01' and sensor = 'tca' group by date, hour order by date DESC, hour DESC limit 24;
			$result = db("select id_wasp,sensor,round(avg(value),2) as value,hour(timestamp) as hour from sensorParser where id_wasp = '".$sensorID."' and sensor = '".$sensorType."' and date(timestamp)=curdate() group by hour(timestamp);");
			
			/* JSON output */ 
			header("Content-type: text/javascript");

			$waspmote = array();
			while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
				$waspmote[] = array("waspmote" => $row);
			}

			echo json_encode(array("waspmotes" => $waspmote));
		} elseif ($method == "day") { // select id_wasp,sensor,round(avg(value),2),date(timestamp) as date,hour(timestamp) as hour from sensorParser where id_wasp = 'a01' and sensor = 'tca' and hour(timestamp) = hour(curtime()) group by date, hour;
			$result = db("select id_wasp,sensor,round(avg(value),2) as value,date(timestamp) as date,hour(timestamp) as hour from sensorParser where id_wasp = '".$sensorID."' and sensor = '".$sensorType."' and hour(timestamp) = hour(curtime()) group by date, hour;");
			
			/* JSON output */ 
			header("Content-type: text/javascript");

			$waspmote = array();
			while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
				$waspmote[] = array("waspmote" => $row);
			}

			echo json_encode(array("waspmotes" => $waspmote));
		} elseif ($method == "month") { //select id_wasp,sensor,round(avg(value),2), monthname(timestamp) as month, hour(timestamp) as hour from sensorParser where id_wasp = 'B12' and sensor = 'tca' and hour(timestamp) = hour(curtime()) group by month, hour;
			$result = db("select id_wasp,sensor,round(avg(value),2) as value,monthname(timestamp) as month, hour(timestamp) as hour from sensorParser where id_wasp = '".$sensorID."' and sensor = '".$sensorType."' and hour(timestamp) = hour(curtime()) group by month, hour;");
			
			/* JSON output */ 
			header("Content-type: text/javascript");

			$waspmote = array();
			while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
				$waspmote[] = array("waspmote" => $row);
			}

			echo json_encode(array("waspmotes" => $waspmote));
		} else {
			notFound404();
		}
	}
    
} elseif (($_SERVER['REQUEST_METHOD']) == "POST" && in_array($method,$method_array)) {
		if ($method == "update") {
			// make sure request is coming from Ajax
			header($_SERVER['HTTP_X_REQUESTED_WITH']);
			$xhr = $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
			if (!$xhr){ 
				header('HTTP/1.1 500 Error: Request must come from Ajax!'); 
				exit(); 
			} 
			
			// get marker position and split it for database
			$mLatLang   = explode(',',$_POST["latlang"]);
			$mLat       = filter_var($mLatLang[0], FILTER_VALIDATE_FLOAT);
			$mLng       = filter_var($mLatLang[1], FILTER_VALIDATE_FLOAT);
			$mName      = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
		
			$result = db("UPDATE waspmote SET y = $mLat, x = $mLng WHERE name = '$mName'");
			if (!$result) {  
				  header('HTTP/1.1 500 Error: Could not update marker!');  
				  exit();
			} 
			
			$output = '<h1 class="marker-heading">'.$mName.'</h1><p>'.$mLat.'<br/>'.$mLng.'</p>';
			exit($output);
		} else {
			notFound404();
		}
		
} else {
    $result = db('CALL sensorReading()');

    /* JSON output */ 
    header("Content-type: text/javascript");

    $waspmote = array();
    while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
    $waspmote[] = array(
		"waspmote" => array(
	 		"name" => $row["id_wasp"],
			"BAT" => $row["BAT"],"HUMA" => $row["HUMA"],"LUM" => $row["LUM"],
    		"MCP" => $row["MCP"],"DUST" => $row["DUST"],"TCA" => $row["TCA"],
    		"time" => $row["timestamp"],"lat" => $row["y"],"lng" => $row["x"]
		)
	);
    }

    echo json_encode(array("waspmotes" => $waspmote));

}

function db($qstr) {
    require("dbinfo.php");

    $connection= new mysqli($dbhost, $username, $passwd, $dbname);
    if (!$connection) {  die('MySQL DB Not connected : ' . mysqli_error());}

    $result = $connection->query($qstr);
    if (!$result) {  die('MySQL Invalid query: ' . mysqli_error());}
    else { return $result; }

    mysqli_close($connection);
}

function debug($o) {
    print '<pre>';
    print_r($o);
    print '</pre>';
}

function notFound404() {
	header('HTTP/1.0 404 Not Found');
	echo('NOT FOUND');
}

?>
