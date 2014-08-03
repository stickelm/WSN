<?php

if (isset($_GET['rquest'])) {
    $words = explode("/",$_REQUEST['rquest']);
    $sensorID = strtoupper(trim(str_replace("/","",$words[0])));
    $method = strtolower(trim(str_replace("/","",$words[1])));
    $sensorType = strtoupper(trim(str_replace("/","",$words[2])));

    $sensor_array = array("A01","A02","A03","A04","A05","A06","A07","A08","A09");
    $method_array = array("hour","day","month");
    $sensorType_array = array("TCA","BAT","LUM","MCP","HUMA","DUST");
    
	if (in_array($sensorID,$sensor_array) && in_array($method,$method_array) 
        && in_array($sensorType,$sensorType_array)) {
        if ($method == "hour") { //select id_wasp,sensor,round(avg(value),2),date(timestamp) as date,hour(timestamp) as hour from sensorParser where id_wasp = 'a01' and sensor = 'tca' group by date, hour order by date DESC, hour DESC limit 24;
			$result = db("select id_wasp,sensor,avg(value) as value,hour(timestamp) as hour from sensorParser where id_wasp = '".$sensorID."' and sensor = '".$sensorType."' and date(timestamp)=curdate() group by hour(timestamp);");
			
			/* XML Format 
			header('Access-Control-Allow-Origin: *');
			header("Content-type: text/xml");

			// Start XML file, create parent node
			$dom = new DOMDocument("1.0");
			$node = $dom->createElement("waspmotes");
			$parnode = $dom->appendChild($node);

			// Iterate through the rows, adding XML nodes for each
			while ($row = $result->fetch_assoc()){
				// ADD TO XML DOCUMENT NODE
				$node = $dom->createElement("waspmote");
				$newnode = $parnode->appendChild($node);
				$newnode->setAttribute("name",$row['id_wasp']);
				$newnode->setAttribute("type",$row['sensor']);
				$newnode->setAttribute("hour",$row['hour']);
				$newnode->setAttribute("value",$row['value']);
			}

			echo $dom->saveXML();	*/

			/* JSON output */ 
			header('Access-Control-Allow-Origin: *');
			header("Content-type: text/javascript");

			$waspmote = array();
			while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
				$waspmote[] = array("waspmote" => $row);
			}

			echo json_encode(array("waspmotes" => $waspmote));  /* */
		} elseif ($method == "day") { // select id_wasp,sensor,round(avg(value),2),date(timestamp) as date,hour(timestamp) as hour from sensorParser where id_wasp = 'a01' and sensor = 'tca' and hour(timestamp) = hour(curtime()) group by date, hour;
			$result = db("select id_wasp,sensor,round(avg(value),2) as value,date(timestamp) as date,hour(timestamp) as hour from sensorParser where id_wasp = '".$sensorID."' and sensor = '".$sensorType."' and hour(timestamp) = hour(curtime()) group by date, hour;");
			
			/* JSON output */ 
			header('Access-Control-Allow-Origin: *');
			header("Content-type: text/javascript");

			$waspmote = array();
			while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
				$waspmote[] = array("waspmote" => $row);
			}

			echo json_encode(array("waspmotes" => $waspmote));  /* */
		} elseif ($method == "month") { //select id_wasp,sensor,round(avg(value),2), monthname(timestamp) as month, hour(timestamp) as hour from sensorParser where id_wasp = 'B12' and sensor = 'tca' and hour(timestamp) = hour(curtime()) group by month, hour;
			$result = db("select id_wasp,sensor,round(avg(value),2) as value,monthname(timestamp) as month, hour(timestamp) as hour from sensorParser where id_wasp = '".$sensorID."' and sensor = '".$sensorType."' and hour(timestamp) = hour(curtime()) group by month, hour;");
			
			/* JSON output */ 
			header('Access-Control-Allow-Origin: *');
			header("Content-type: text/javascript");

			$waspmote = array();
			while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
				$waspmote[] = array("waspmote" => $row);
			}

			echo json_encode(array("waspmotes" => $waspmote));  /* */
		} else {
		//echo $sensorID.",".$method.",".$sensorType;
		notFound404();
		}
	}
    
} else {
    $result = db('CALL sensorReading()');

	/* XML output 
    header('Access-Control-Allow-Origin: *');
    header("Content-type: text/xml");

    // Start XML file, create parent node
    $dom = new DOMDocument("1.0");
    $node = $dom->createElement("waspmotes");
    $parnode = $dom->appendChild($node);

    // Iterate through the rows, adding XML nodes for each
    while ($row = $result->fetch_assoc()){
        // ADD TO XML DOCUMENT NODE
        $node = $dom->createElement("waspmote");
        $newnode = $parnode->appendChild($node);
        $newnode->setAttribute("name",$row['id_wasp']);
        $newnode->setAttribute("lat", $row['y']);
        $newnode->setAttribute("lng", $row['x']);
        $newnode->setAttribute("BAT",$row['BAT']);
        $newnode->setAttribute("HUMA",$row['HUMA']);
        $newnode->setAttribute("LUM",$row['LUM']);
        $newnode->setAttribute("MCP",$row['MCP']);
        $newnode->setAttribute("DUST",$row['DUST']);
        $newnode->setAttribute("TCA",$row['TCA']);
        $newnode->setAttribute("time",$row['timestamp']);
    }

    echo $dom->saveXML();/*	*/

    /* JSON output */ 
	header('Access-Control-Allow-Origin: *');
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

    echo json_encode(array("waspmotes" => $waspmote));  /* */

    /* Debug mysql query row
    while($row = $result->fetch_assoc())
    {
    debug($row);
    }    */
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
    header('Access-Control-Allow-Origin: *');
	header('HTTP/1.0 404 Not Found');
	echo('NOT FOUND');
}

?>
