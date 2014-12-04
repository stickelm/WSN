<?php
require_once ("defs.php");

session_start();
/* Using session_regenerate_id()
a- When you refresh the page you get a new session id
b- When you close the browser the session gets destroyed
c- It will prevent session stealing
*/
session_regenerate_id();
//Accepting http only cookies
ini_set('session.cookie_httponly', true);

if(!isset($_SESSION['secret_id'])) {
    if ($_SESSION['secret_id'] != SECRET_ID) {
        header("Location: login.html");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Wireless Sensor Network Test Bed</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
    body {
        background-image: url('/img/bg73.gif');
        padding-top: 50px;
        /* Required padding for .navbar-fixed-top. Remove if using .navbar-static-top. Change if height of navigation changes. */
    }

    .map_container{
        position: relative;
        width: 100%;
        margin:10px 0px;
        padding-bottom: 56.25%; /* Ratio 16:9 ( 100%/16*9 = 56.25% ) */
    }
    .map_container .map_canvas{
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        margin: 0;
        padding: 0;
    }
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
<!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">Start WSN</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="#">About</a>
                    </li>
                    <li>
                        <a href="logout.php">Logout</a>
                    </li>
                    <li>
                        <a href="#">Contact</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
 <!-- Page Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2>OTAP</h2>
                <p class="lead">Upload your Hex files and Start OTAP!</p>
            </div>
            <div class="col-lg-12">
              <button class="btn btn-primary btn-sm" id="toggleTca" title="button">Temperature</button>
              <button class="btn btn-primary btn-sm" id="toggleHuma" title="button">Humidity</button>
            </div>
            <div class="col-lg-12 map_container">
                <div id="map_canvas" class="map_canvas"></div>
            </div>
        </div>
        <!-- /.row -->

    </div>
    <!-- /.container -->

    <!-- jQuery Version 1.11.0 -->
    <script src="js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Google Map API V3 JavaScript -->
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=visualization"></script>

     <!-- OTAP and Update Customized JavaScript -->
    <script>
        $(document).ready(function() {
            var nus_center = new google.maps.LatLng(1.298796-100/1000000, 103.772143-100/1000000); //Google map Coordinates
            var map;
            var infowindow;

            map_initialize(); // load map

        function map_initialize() {

            var mapOptions = {
            zoom: 20,
            center: nus_center,
            panControl: false,
            zoomControl: true, //enable zoom control
            streetViewControl: false,
            mapTypeControlOptions: {
                mapTypeIds: []
            },
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.SMALL //zoom control size
            },
            scaleControl: true, // enable scale control
            mapTypeId: google.maps.MapTypeId.ROADMAP // google map type
            };

            var overlayOpts = {
            opacity:0.5
            };

            map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
           var imageBounds_E4_06 = new google.maps.LatLngBounds(
              new google.maps.LatLng(1.2982600-200/10000000, 103.7714118-200/10000000),
              new google.maps.LatLng(1.2992000-200/10000000, 103.7726933-200/10000000));
            var E4_06_Overlay = new google.maps.GroundOverlay(
              '/img/E4-06.png', imageBounds_E4_06, overlayOpts);
            E4_06_Overlay.setMap(map);

            //Load Markers from the JSON API Call
            $.ajax({
                type: "GET",
                url: "http://wsn.ami-lab.org/waspmotes/",
                async: false,
                dataType: "json",
                success: function(data){
                    $(data.waspmotes).each(function () {
                        var name = this.waspmote.name;
                        var number = name.match(/\d+/)*1;
                        var point = new google.maps.LatLng(parseFloat(this.waspmote.lat),parseFloat(this.waspmote.lng));

                        //call create_marker() function for json loaded maker
                        create_marker(point, name, true, false, "/img/numbers/number_" + number + ".png");
                    });
                }
            });

        }
        //############### Create Marker Function ##############
        function create_marker(MapPos, MapTitle, DragAble, Removable, iconPath)
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
            var contentString = $('<div class="row">' +
                '<div class="col-md-12">' +
                '<div><h2>Text</h2></div>' +
                '<div><p class="lead">ppp</p></div>' +
                '<button class="btn btn-primary btn-sm" id="update">Update</button>' +
                '</div></div>'
            );

            // update marker position
            var updateBtn = contentString.find("button[id=update]")[0];

            //add click listener to update marker button
            google.maps.event.addDomListener(updateBtn, 'click', function(event) {
                update_marker(marker);
            });

          //add click listener to marker
            google.maps.event.addListener(marker, 'click', function() {
                if (infowindow) infowindow.close();
                //map.panTo(marker.getPosition());
                infowindow = new google.maps.InfoWindow({content: contentString[0]});
                infowindow.open(map,marker);
            });

        }

        // Update Marker Function
        function update_marker(Marker)
        {
        //Save new marker using jQuery Ajax
        var mLatLang = Marker.getPosition().toUrlValue(); //get marker position
        var mName = Marker.getTitle();
        var secretID = '<?php echo $_SESSION['secret_id']?>';
        var myData = {name : mName, latlang : mLatLang, secretid : secretID}; //post variables

        $.ajax({
            type: "POST",
            url: "http://wsn.ami-lab.org/waspmotes/" + mName + "/update/",
            data: myData,
            success:function(data){
              //console.log(data);
              infowindow.close();
              Marker.setAnimation(google.maps.Animation.BOUNCE);
              Marker.setAnimation(null);
            },
            error:function (xhr, ajaxOptions, thrownError){
                alert(thrownError); //throw any errors
            }
        });
    }
});
    </script>

</body>

</html>