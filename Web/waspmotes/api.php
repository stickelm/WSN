<?php
require_once ("defs.php");

// Allowed HTTP Headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: origin, x-requested-with, content-type");

$sensor_array = unserialize(SENSOR_NAME);
$method_array = unserialize(VALID_METHOD);
$sensorType_array = unserialize(VALID_SENSOR);
$sensorID = "";
$method = "";
$sensorType = "";

$words = (isset($_REQUEST['rquest']) ? explode("/",$_REQUEST['rquest']) : null);
if (isset($words[0])) { $sensorID = strtoupper(trim(str_replace("/","",$words[0]))); }
if (isset($words[1])) { $method = strtolower(trim(str_replace("/","",$words[1]))); }
if (isset($words[2])) { $sensorType = strtoupper(trim(str_replace("/","",$words[2]))); }	

if (($_SERVER['REQUEST_METHOD']) == "GET" && isset($_GET['rquest']) && strlen($_GET['rquest'])) {
    if (in_array($sensorID,$sensor_array) && in_array($method,$method_array) 
        && in_array($sensorType,$sensorType_array)) {
        if ($method == "hour") { //select id_wasp,sensor,round(avg(value),2),date(timestamp) as date,hour(timestamp) as hour from sensorParser where id_wasp = 'a01' and sensor = 'tca' group by date, hour order by date DESC, hour DESC limit 24;
			$result = db("select id_wasp,sensor,round(avg(value),2) as value,hour(timestamp) as hour from sensorParser where id_wasp = '".$sensorID."' and sensor = '".$sensorType."' and date(timestamp)=curdate() group by hour(timestamp);");
			
			// JSON output
			header("Content-type: text/javascript");

			$waspmote = array();
			while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
				$waspmote[] = array("waspmote" => $row);
			}

			echo json_encode(array("waspmotes" => $waspmote));
		} elseif ($method == "day") { // select id_wasp,sensor,round(avg(value),2),date(timestamp) as date,hour(timestamp) as hour from sensorParser where id_wasp = 'a01' and sensor = 'tca' and hour(timestamp) = hour(curtime()) group by date, hour;
			$result = db("select id_wasp,sensor,round(avg(value),2) as value,date(timestamp) as date,hour(timestamp) as hour from sensorParser where id_wasp = '".$sensorID."' and sensor = '".$sensorType."' and hour(timestamp) = hour(curtime()) group by date, hour;");
			
			// JSON output
			header("Content-type: text/javascript");

			$waspmote = array();
			while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
				$waspmote[] = array("waspmote" => $row);
			}

			echo json_encode(array("waspmotes" => $waspmote));
		} elseif ($method == "month") { //select id_wasp,sensor,round(avg(value),2), monthname(timestamp) as month, hour(timestamp) as hour from sensorParser where id_wasp = 'B12' and sensor = 'tca' and hour(timestamp) = hour(curtime()) group by month, hour;
			$result = db("select id_wasp,sensor,round(avg(value),2) as value,monthname(timestamp) as month, hour(timestamp) as hour from sensorParser where id_wasp = '".$sensorID."' and sensor = '".$sensorType."' and hour(timestamp) = hour(curtime()) group by month, hour;");
			
			// JSON output
			header("Content-type: text/javascript");

			$waspmote = array();
			while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
				$waspmote[] = array("waspmote" => $row);
			}

			echo json_encode(array("waspmotes" => $waspmote));
		} else {
			notFound404();
		}
	} else {
			notFound404();
	}
    
} elseif (($_SERVER['REQUEST_METHOD']) == "POST" && in_array($method,$method_array)) {
    if ($method == "update" && $_POST["secretid"] == SECRET_ID) {
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
