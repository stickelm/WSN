<?php

require("dbinfo.php");

// Start XML file, create parent node

$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);

// Opens a connection to a MySQL server

$connection= new mysqli('localhost', $username, $password, $database);
if (!$connection) {  die('Not connected : ' . mysqli_error());}

// Select all the rows in the markers table
$result = $connection->query('CALL sensorReading()');
if (!$result) {
  die('Invalid query: ' . mysqli_error());
}

header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each

while ($row = $result->fetch_assoc()){
  // ADD TO XML DOCUMENT NODE
  $node = $dom->createElement("marker");
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

/*
while($row = $result->fetch_assoc())
{
debug($row);
}

function debug($o)
{
print '<pre>';
print_r($o);
print '</pre>';
}
*/

mysqli_close($connection);

?>
