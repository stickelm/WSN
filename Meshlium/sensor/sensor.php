<?php

if (isset($_REQUEST['rquest'])) {
    $words = explode("/",$_REQUEST['rquest']);
    $sensorID = strtoupper(trim(str_replace("/","",$words[0])));
    $freq = strtolower(trim(str_replace("/","",$words[1])));
    $sensorType = strtoupper(trim(str_replace("/","",$words[2])));

    $sensor_array = array("A01","A02","A03","A04","A05","A06","A07","A08","A09");
    $freq_array = array("hour","week","month");
    $sensorType_array = array("TCA","BAT","LUM","MCP","HUMA","DUST");

    if (in_array($sensorID,$sensor_array) 
        && in_array($freq,$freq_array) 
        && in_array($sensorType,$sensorType_array)) {
        $result = db("select id_wasp,sensor,avg(value) as value,hour(timestamp) as hour from sensorParser where id_wasp = '".$sensorID."' and sensor = '".$sensorType."' and date(timestamp)=curdate() group by hour(timestamp);");

        header('Access-Control-Allow-Origin: *');
        header("Content-type: text/xml");

        // Start XML file, create parent node
        $dom = new DOMDocument("1.0");
        $node = $dom->createElement("sensors");
        $parnode = $dom->appendChild($node);

        // Iterate through the rows, adding XML nodes for each
        while ($row = $result->fetch_assoc()){
            // ADD TO XML DOCUMENT NODE
            $node = $dom->createElement("sensor");
            $newnode = $parnode->appendChild($node);
            $newnode->setAttribute("name",$row['id_wasp']);
            $newnode->setAttribute("type",$row['sensor']);
            $newnode->setAttribute("hour",$row['hour']);
            $newnode->setAttribute("value",$row['value']);
        }

        echo $dom->saveXML();

    } else {
        notFound404(); 
    }
    
} else {
    $result = db('CALL sensorReading()');

    header('Access-Control-Allow-Origin: *');
    header("Content-type: text/xml");

    // Start XML file, create parent node
    $dom = new DOMDocument("1.0");
    $node = $dom->createElement("sensors");
    $parnode = $dom->appendChild($node);

    // Iterate through the rows, adding XML nodes for each
    while ($row = $result->fetch_assoc()){
        // ADD TO XML DOCUMENT NODE
        $node = $dom->createElement("sensor");
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

    echo $dom->saveXML();

    /* JSON output
    $sensor = array();
    while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
    $sensor[] = array('post'=>$row);
    }

    echo json_encode(array('posts' => $sensor));
    */

    /* Debug mysql query row
    while($row = $result->fetch_assoc())
    {
    debug($row);
    }
    */
}

function db($qstr) {
    require("dbinfo.php");

    $connection= new mysqli($dbhost, $username, $passwd, $dbname);
    if (!$connection) {  die('Not connected : ' . mysqli_error());}

    $result = $connection->query($qstr);
    if (!$result) {  die('Invalid query: ' . mysqli_error());}
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
