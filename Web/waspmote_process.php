<?php
// database settings 
require("dbinfo.php");

//mysqli
$mysqli = new mysqli($hostname, $username, $password, $database);
if (mysqli_connect_errno()) 
{
    header('HTTP/1.1 500 Error: Could not connect to db!'.mysqli_error()); 
    exit();
}

################ Save & delete markers #################
if($_POST) //run only if there's a post data
{
    //make sure request is comming from Ajax
    $xhr = $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'; 
    if (!$xhr){ 
        header('HTTP/1.1 500 Error: Request must come from Ajax!'); 
        exit(); 
    }
    
    // get marker position and split it for database
    $mLatLang   = explode(',',$_POST["latlang"]);
    $mLat       = filter_var($mLatLang[0], FILTER_VALIDATE_FLOAT);
    $mLng       = filter_var($mLatLang[1], FILTER_VALIDATE_FLOAT);

/*    
    //Delete Marker
    if(isset($_POST["del"]) && $_POST["del"]==true)
    {
        $results = $mysqli->query("DELETE FROM table WHERE lat=$mLat AND lng=$mLng");
        if (!$results) {  
          header('HTTP/1.1 500 Error: Could not delete Markers!');  
          exit();
        } 
        exit("Done!");
    }
*/
  
    //more validations are encouraged, empty fields etc.
    $mName      = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
    
    $results = $mysqli->query("UPDATE table_name SET y = $mLat, x = $mLng WHERE name = '$mName'");
    if (!$results) {  
          header('HTTP/1.1 500 Error: Could not update marker!');  
          exit();
    } 
    
    $output = '<h1 class="marker-heading">'.$mName.'</h1><p>'.$mLat.'<br/>'.$mLng.'</p>';
    exit($output);
}

?>
