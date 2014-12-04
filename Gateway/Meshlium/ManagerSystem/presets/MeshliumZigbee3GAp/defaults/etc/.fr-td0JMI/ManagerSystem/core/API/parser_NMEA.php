<?php
/*
 * Copyright (C) 2008 Libelium Comunicaciones Distribuidas S.L.
 *
 * This file is part of Meshlium Manager System.
 * Meshlium Manager System will be released as free software; until then you cannot redistribute it
 * without express permission by libelium. 
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * 
 *
 * Version 0.1.0 
 * Author: Octavio Benedí Sánchez  
 */

function parse_NMEA($input) {
    unset($response);
    unset($data);
    $response = Array();

    $input = trim($input);
    $data = explode(',', $input); // we have the chain type and the geostring.
    switch ($data[0]) {
        case '$GPGGA':
            $response['type'] = substr($data[0], 1); // take out the initial $
            $response['utc'] = $data[1];
            $response['lat'] = $data[2]; // Convertir a latitud que entienda google.
            $response['ns'] = $data[3];
            if ($data[3]=='N') {
                $lat_google = ($data[2]/100);
                $response['lat-google'] = round(floor($lat_google) + ($lat_google-floor($lat_google))/0.6, 4);
                unset($lat_google);
            } else {
                $lat_google = ($data[2]/100);
                $response['lat-google'] = -(round(floor($lat_google) + ($lat_google-floor($lat_google))/0.6, 4));
                unset($lat_google);
            }
            $response['long'] = $data[4]; // Convertir a longitud que entienda google.
            $response['ew'] = $data[5];
            if ($data[5]=='E') {
                $long_google = ($data[4]/100);
                $response['long-google'] = round(floor($long_google) + ($long_google-floor($long_google))/0.6, 4);
                unset($long_google);
            } else {
                $long_google = ($data[4]/100);
                $response['long-google'] = -(round(floor($long_google) + ($long_google-floor($long_google))/0.6, 4));
                unset($long_google);
            }
            $response['gpsqual'] = $data[6];
            $response['numsat'] = $data[7];
            $response['hdp'] = $data[8];
            $response['alt'] = $data[9];
            while (($response['alt'][0]=='0')&& ( $response['alt'][1]!='.')) {
                $response['alt'] = substr($response['alt'], 1);
            }
            $response['un_alt'] = $data[10];
            $response['geoidal'] = $data[11];
            $response['un_geoidal'] = $data[12];
            $response['dgps'] = $data[13];
            //$response['diffstat']=$data[14];
            //$response['diffref']=$data[15];
            break;
        case '$GPVTG':
            $response['type'] = substr($data[0], 1);
            $response['trkdeg1'] = $data[1];
            $response['t'] = $data[2];
            $response['trkdeg2'] = $data[3];
            $response['m'] = $data[4];
            $response['spdknots'] = $data[5];
            $response['knots'] = $data[6];
            $response['spdkmph'] = $data[7];
            $response['kph'] = substr($data[8], 0, -3);
            break;
        case '$GPRMC':
            $response['type'] = substr($data[0], 1);
            $response['utc'] = $data[1];
            $response['statusrmc'] = $data[2];
            $response['lat'] = $data[3];
            $response['ns'] = $data[4];
            $response['long'] = $data[5];
            $response['ew'] = $data[6];
            $response['speed'] = $data[7];
            $response['track'] = $data[8];
            $response['date'] = $data[9];
            $response['magvar'] = $data[10];
            $response['mag_ew'] = substr($data[11], 0, -3);
            /*
             * f (file_exists(BASE_PATH.'/data/gps.conf'))
             {
             * ini=file(BASE_PATH.'/data/gps.conf');
             * f (substr($ini[0],0,2)=='on')
             {
             * xec('sudo '.EXEC_PATH.'set_date "20'.substr($data[9],4,2).'-'.substr($data[9],2,2).'-'.substr($data[9],0,2).' '.substr($data[1],0,2).':'.substr($data[1],2,2).'"');
             }
             }
             *
             * / Check the gps time status
             * f (file_exists('/etc/gps_time.conf'))
             {
             * gps_file=file('/etc/gps_time.conf');
             * oreach($gps_file as $gps_conf_line)
             {
             * gps_active=explode('=',trim($gps_conf_line));
             * f (($gps_active[0]=='gps_mode')&&($gps_active[1]=='on'))
             {
             * xec('sudo '.EXEC_PATH.'set_date "20'.substr($data[9],4,2).'-'.substr($data[9],2,2).'-'.substr($data[9],0,2).' '.substr($data[1],0,2).':'.substr($data[1],2,2).'"');
             }
             }
             * nset($gps_file);
             * nset($gps_conf_line);
             * nset($gps_active);
             } */
            break;
        case '$GPGSA':
            $response['type'] = substr($data[0], 1);
            $response['selectmode'] = $data[1];
            $response['mode'] = $data[2];
            $response['sat1'] = $data[3];
            $response['sat2'] = $data[4];
            $response['sat3'] = $data[5];
            $response['sat4'] = $data[6];
            $response['sat5'] = $data[7];
            $response['sat6'] = $data[8];
            $response['sat7'] = $data[9];
            $response['sat8'] = $data[10];
            $response['sat9'] = $data[11];
            $response['sat10'] = $data[12];
            $response['sat11'] = $data[13];
            $response['sat12'] = $data[14];
            $response['pdop'] = $data[15];
            $response['hdop'] = $data[16];
            $response['vdop'] = substr($data[17], 0, -3);
            break;
        case '$GPGSV':
            $response['type'] = substr($data[0], 1);
            $response['satmessages'] = $data[1];
            $response['messnum'] = $data[2];
            $response['satview'] = $data[3];
            $response['satnum_1'] = $data[4];
            $response['elevdeg_1'] = $data[5];
            $response['azimuthdeg_1'] = $data[6];
            $response['SNR_1'] = $data[7];
            $response['satnum_2'] = $data[8];
            $response['elevdeg_2'] = $data[9];
            $response['azimuthdeg_2'] = $data[10];
            $response['SNR_2'] = $data[11];
            $response['satnum_3'] = $data[12];
            $response['elevdeg_3'] = $data[13];
            $response['azimuthdeg_3'] = $data[14];
            $response['SNR_3'] = $data[15];
            $response['satnum_4'] = $data[16];
            $response['elevdeg_4'] = $data[17];
            $response['azimuthdeg_4'] = $data[18];
            $response['SNR_4'] = substr($data[19], 0, -3);
            break;
        default:
            break;
    }
    return $response;
}
?>