#!/bin/bash


while [ 1 ]
do
    sleep 0.5
    if [ -f /var/www/ManagerSystem/plugins/c_tools/h0_gps/data/interval ]
    then
        timeToSleep=`cat /var/www/ManagerSystem/plugins/c_tools/h0_gps/data/interval`
    else
        timeToSleep=10
    fi

    medio=0;

    if [ -f /var/www/ManagerSystem/plugins/c_tools/h0_gps/data/localDB ]
    then
        medio=1;
        localDatabase=`cat /mnt/lib/cfg/gpsDBSetup | grep -n "" | grep 1: | cut -d':' -f2`
        localTable=`cat /mnt/lib/cfg/gpsDBSetup | grep -n "" | grep 2: | cut -d':' -f2`
        localIP=`cat /mnt/lib/cfg/gpsDBSetup | grep -n "" | grep 3: | cut -d':' -f2`
        localPort=`cat /mnt/lib/cfg/gpsDBSetup | grep -n "" | grep 4: | cut -d':' -f2`
        localUser=`cat /mnt/lib/cfg/gpsDBSetup | grep -n "" | grep 5: | cut -d':' -f2`
        localPass=`cat /mnt/lib/cfg/gpsDBSetup | grep -n "" | grep 6: | cut -d':' -f2`
    fi

    if [ -f /var/www/ManagerSystem/plugins/c_tools/h0_gps/data/localFile ]
    then
        medio=1;
    fi

    if [ -f /var/www/ManagerSystem/plugins/c_tools/h0_gps/data/extDB ]
    then
        medio=1;
        extDatabase=`cat /var/www/ManagerSystem/plugins/c_tools/h0_gps/data/ExtDataConnection | grep -n '' | grep 1: | cut -d: -f2`
        extTable=`cat /var/www/ManagerSystem/plugins/c_tools/h0_gps/data/ExtDataConnection | grep -n '' | grep 2: | cut -d: -f2`
        extIP=`cat /var/www/ManagerSystem/plugins/c_tools/h0_gps/data/ExtDataConnection | grep -n '' | grep 3: | cut -d: -f2`
        extPort=`cat /var/www/ManagerSystem/plugins/c_tools/h0_gps/data/ExtDataConnection | grep -n '' | grep 4: | cut -d: -f2`
        extUser=`cat /var/www/ManagerSystem/plugins/c_tools/h0_gps/data/ExtDataConnection | grep -n '' | grep 5: | cut -d: -f2`
        extPassword=`cat /var/www/ManagerSystem/plugins/c_tools/h0_gps/data/ExtDataConnection | grep -n '' | grep 6: | cut -d: -f2`
    fi

    if [ $medio -eq 1 ]
    then

        #hcitool scan | grep ":" > /tmp/scan
        #num=`cat /tmp/scan | grep -c ""`
        #cont=1

        while [ 1 ]
        do
            sleep 0.5
            #while [ $cont -le $num ]
            #do
                #linea=`cat /tmp/scan | grep -n "" | grep "^$cont:"`
                #mac=`echo $linea | cut -d' ' -f2`
                #id=`echo $linea | cut -d' ' -f3`
                GPS_setFrames add gpvtg

                GPS_frame gpgga > /tmp/gpsFrame 2>&1
                #gpggaFrame=`cat /tmp/gpsFrame`
                gpggaLatitude=`cat /tmp/gpsFrame | cut -d',' -f3,4`
                gpggaLongitude=`cat /tmp/gpsFrame | cut -d',' -f5,6`
                gpggaSatellites=`cat /tmp/gpsFrame | cut -d',' -f8`
                gpggaAltitude=`cat /tmp/gpsFrame | cut -d',' -f10,11`


                GPS_frame gprmc > /tmp/gpsFrame 2>&1
                #gprmcFrame=`cat /tmp/gpsFrame`
                gprmcHour=`cat /tmp/gpsFrame | cut -d',' -f2 | cut -b1,2`
                gprmcMinute=`cat /tmp/gpsFrame | cut -d',' -f2 | cut -b3,4`
                gprmcSecond=`cat /tmp/gpsFrame | cut -d',' -f2 | cut -b5,6`
                gprmcDay=`cat /tmp/gpsFrame | cut -d',' -f10 | cut -b1,2`
                gprmcMonth=`cat /tmp/gpsFrame | cut -d',' -f10 | cut -b3,4`
                gprmcYear=`cat /tmp/gpsFrame | cut -d',' -f10 | cut -b5,6`


                GPS_frame gpvtg > /tmp/gpsFrame 2>&1
                gpvtgSpeed=`cat /tmp/gpsFrame | cut -d',' -f8-9`


                #GPSToBBDD gpgsv > /tmp/gpsFrame 2>&1
                #gpgsvFrame=`cat /tmp/gpsFrame`

                if [ -f /var/www/ManagerSystem/plugins/c_tools/h0_gps/data/showMeNowCheck ]
                then
                    echo "<b>Date: $gprmcYear-$gprmcMonth-$gprmcDay $gprmcHour:$gprmcMinute:$gprmcSecond</b>" > /mnt/user/gps_data/.showMeNowFile
                    echo "<span style='margin-left: 15px;'>Longitude: $gpggaLongitude</span>" >> /mnt/user/gps_data/.showMeNowFile
                    echo "<span style='margin-left: 15px;'>Latitude: $gpggaLatitude</span>" >> /mnt/user/gps_data/.showMeNowFile
                    echo "<span style='margin-left: 15px;'>Altitude: $gpggaAltitude</span>" >> /mnt/user/gps_data/.showMeNowFile
                    echo "<span style='margin-left: 15px;'>Satellites: $gpggaSatellites</span>" >> /mnt/user/gps_data/.showMeNowFile
                    echo "<span style='margin-left: 15px;'>Speed: $gpvtgSpeed</span>" >> /mnt/user/gps_data/.showMeNowFile
                    echo "" >> /mnt/user/gps_data/$file
                    echo "---------------------------" >> /mnt/user/gps_data/$file
                    echo "" >> /mnt/user/gps_data/$file
                fi


                if [ -f /var/www/ManagerSystem/plugins/c_tools/h0_gps/data/localDB ]
                then
                    localDatabase=`cat /mnt/lib/cfg/gpsDBSetup | grep -n "" | grep 1: | cut -d':' -f2`
                    localTable=`cat /mnt/lib/cfg/gpsDBSetup | grep -n "" | grep 2: | cut -d':' -f2`
                    localIP=`cat /mnt/lib/cfg/gpsDBSetup | grep -n "" | grep 3: | cut -d':' -f2`
                    localPort=`cat /mnt/lib/cfg/gpsDBSetup | grep -n "" | grep 4: | cut -d':' -f2`
                    localUser=`cat /mnt/lib/cfg/gpsDBSetup | grep -n "" | grep 5: | cut -d':' -f2`
                    localPass=`cat /mnt/lib/cfg/gpsDBSetup | grep -n "" | grep 6: | cut -d':' -f2`
                    echo "insert into $localTable values (null, now(), '$gprmcYear-$gprmcMonth-$gprmcDay $gprmcHour:$gprmcMinute:$gprmcSecond', '$gpggaLongitude', '$gpggaLatitude', '$gpggaAltitude', '$gpggaSatellites', '$gpvtgSpeed');" | mysql -u $localUser --password=$localPass $localDatabase
                fi

                if [ -f /var/www/ManagerSystem/plugins/c_tools/h0_gps/data/localFile ]
                then
                    file=`cat /var/www/ManagerSystem/plugins/c_tools/h0_gps/data/localFile`
                    echo "<b>Date: $gprmcYear-$gprmcMonth-$gprmcDay $gprmcHour:$gprmcMinute:$gprmcSecond</b>" >> /mnt/user/gps_data/$file
                    echo "<span style='margin-left: 15px;'>Longitude: $gpggaLongitude</span>" >> /mnt/user/gps_data/$file
                    echo "<span style='margin-left: 15px;'>Latitude: $gpggaLatitude</span>" >> /mnt/user/gps_data/$file
                    echo "<span style='margin-left: 15px;'>Altitude: $gpggaAltitude</span>" >> /mnt/user/gps_data/$file
                    echo "<span style='margin-left: 15px;'>Satellites: $gpggaSatellites</span>" >> /mnt/user/gps_data/$file
                    echo "<span style='margin-left: 15px;'>Speed: $gpvtgSpeed</span>" >> /mnt/user/gps_data/$file
                    echo "" >> /mnt/user/gps_data/$file
                    echo "---------------------------" >> /mnt/user/gps_data/$file
                    echo "" >> /mnt/user/gps_data/$file
                    #echo "$gprmcYear-$gprmcMonth-$gprmcDay - $gprmcHour:$gprmcMinute:$gprmcSecond ; Longitude: $gpggaLongitude ; Latitude: $gpggaLatitude ; Altitude : $gpggaAltitude ; Satellites: $gpggaSatellites ;" >> /mnt/user/gps_data/$file
                fi

                if [ -f /var/www/ManagerSystem/plugins/c_tools/h0_gps/data/extDB ]
                then
                    extDatabase=`cat /var/www/ManagerSystem/plugins/c_tools/h0_gps/data/ExtDataConnection | grep -n '' | grep 1: | cut -d: -f2`
                    extTable=`cat /var/www/ManagerSystem/plugins/c_tools/h0_gps/data/ExtDataConnection | grep -n '' | grep 2: | cut -d: -f2`
                    extIP=`cat /var/www/ManagerSystem/plugins/c_tools/h0_gps/data/ExtDataConnection | grep -n '' | grep 3: | cut -d: -f2`
                    extPort=`cat /var/www/ManagerSystem/plugins/c_tools/h0_gps/data/ExtDataConnection | grep -n '' | grep 4: | cut -d: -f2`
                    extUser=`cat /var/www/ManagerSystem/plugins/c_tools/h0_gps/data/ExtDataConnection | grep -n '' | grep 5: | cut -d: -f2`
                    extPassword=`cat /var/www/ManagerSystem/plugins/c_tools/h0_gps/data/ExtDataConnection | grep -n '' | grep 6: | cut -d: -f2`
                    #echo "insert into $extTable values (null, now(), '$mac', '$id');" | mysql -h $extIP -P $extPort -u $extUser --password=$extPassword $extDatabase
                    echo "insert into $extTable values (null, now(), '$gprmcYear-$gprmcMonth-$gprmcDay $gprmcHour:$gprmcMinute:$gprmcSecond'', '$gpggaLongitude', '$gpggaLatitude', '$gpggaAltitude', '$gpggaSatellites', '$gpvtgSpeed');" | mysql -h $extIP -P $extPort -u $extUser --password=$extPassword $extDatabase
#echo "insert into gpsData values (null, now(), '$gprmcYear-$gprmcMonth-$gprmcDay', '$gpggaLongitude', '$gpggaLatitude', '$gpggaAltitude', '$gpggaSatellites', '$gpvtgSpeed'); | mysql -h $extIP -P $extPort -u $extUser --password=$extPassword $extDatabase"
                fi


                #cont=`expr $cont + 1`
            #done

            sleep $timeToSleep
            #hcitool scan | grep ":" > /tmp/scan
            #num=`cat /tmp/scan | grep -c ""`
            #cont=1
        done
    fi

done
