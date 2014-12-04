#!/bin/bash


    GPSToBBDD gpgga > /tmp/gpsFrameNow 2>&1
    gpggaLatitude=`cat /tmp/gpsFrameNow | cut -d',' -f3,4`
    gpggaLongitude=`cat /tmp/gpsFrameNow | cut -d',' -f5,6`
    gpggaSatellites=`cat /tmp/gpsFrameNow | cut -d',' -f8`
    gpggaAltitude=`cat /tmp/gpsFrameNow | cut -d',' -f10,11`


    GPSToBBDD gprmc > /tmp/gpsFrameNow 2>&1
    gprmcHour=`cat /tmp/gpsFrameNow | cut -d',' -f2 | cut -b1,2`
    gprmcMinute=`cat /tmp/gpsFrameNow | cut -d',' -f2 | cut -b3,4`
    gprmcSecond=`cat /tmp/gpsFrameNow | cut -d',' -f2 | cut -b5,6`
    gprmcDay=`cat /tmp/gpsFrameNow | cut -d',' -f10 | cut -b1,2`
    gprmcMonth=`cat /tmp/gpsFrameNow | cut -d',' -f10 | cut -b3,4`
    gprmcYear=`cat /tmp/gpsFrameNow | cut -d',' -f10 | cut -b5,6`


    GPS_frame gpvtg > /tmp/gpsFrameNow 2>&1
    gpvtgSpeed=`cat /tmp/gpsFrameNow | cut -d',' -f8-9`



    echo "<b>Date: $gprmcYear-$gprmcMonth-$gprmcDay $gprmcHour:$gprmcMinute:$gprmcSecond</b><br><br>";
    echo "<span style='margin-left: 15px;'>Longitude: $gpggaLongitude</span><br>";
    echo "<span style='margin-left: 15px;'>Latitude: $gpggaLatitude</span><br>";
    echo "<span style='margin-left: 15px;'>Altitude: $gpggaAltitude</span><br>";
    echo "<span style='margin-left: 15px;'>Satellites: $gpggaSatellites</span><br>";
    echo "<span style='margin-left: 15px;'>Speed: $gpvtgSpeed</span><br>";
    echo "<br>" ;

