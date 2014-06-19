#!/bin/bash

## 0) Preparar el systema
    # Preparamos para lectura/escritura
        echo "remounting for read & write";
        sudo remountrw;
    # Limpiamos archivos de configuracion de tipos de máquinas configurados anteriormente

    #    if [ -f /etc/init.d/join.sh ]
    #    then
    #        sudo rm /etc/init.d/join.sh
    #    fi

    #    if [ -f /etc/init.d/wvdiald.sh ]
    #    then
    #        sudo rm /etc/init.d/wvdiald.sh
    #    fi

    #    if [ -f /etc/wvdial.conf ]
    #    then
    #        sudo rm /etc/wvdial.conf
    #    fi

    #    if [ -f /etc/hostapd/hostapd_ath0.conf ]
    #    then
    #        sudo rm /etc/hostapd/hostapd_ath0.conf
    #    fi



    #sudo chmod a-x /etc/init.d/wvdiald.sh
    #sudo chmod a-x /etc/rc*/*wvdial*
    #sudo cp /etc/network/interfaces /etc/network/interfaces.confirmation



## 1) Sustituimos archivos del sistema:
    #/
    #|-- bin
    #|   |-- capturer
    #|   |-- restart-secure
    #|   `-- shutdown-secure
    #|-- etc
    #|   |-- default
    #|   |   `-- olsrd
    #|   |-- dnsmasq.more.conf
    #|   |-- init.d
    #|   |   |-- confirmation.sh
    #|   |   |-- iperf_d
    #|   |   |-- join.sh
    #|   |   |-- networking
    #|   |   |-- radio_options
    #|   |   `-- wvdiald.sh
    #|   |-- motd
    #|   |-- motd.tail
    #|   |-- motd.voyage
    #|   |-- network
    #|   |   `-- interfaces
    #|   |-- olsrd.conf
    #|   `-- wvdial.conf
    #|-- mnt
    #|   |-- lib
    #|   |   `-- cfg
    #|   |       `-- currentPreset
    #|   `-- usr
    #`-- root
    #    `-- important_readme.txt

    echo "Copying System files"
    sudo cp -r /var/www/ManagerSystem/presets/MeshliumAp/defaults/etc /
    sudo cp -r /var/www/ManagerSystem/presets/MeshliumAp/defaults/bin /
    sudo cp -r /var/www/ManagerSystem/presets/MeshliumAp/defaults/mnt /
    sudo cp -r /var/www/ManagerSystem/presets/MeshliumAp/defaults/root /



## 2) Poner Rutas
    #echo "Setting new routes"
    # Quitamos el default gw que pudiera estar configurado
        #sudo route del default
    # Ponemos el default gw que nos interesa
        #sudo route add default gw 192.168.1.1



## 3) Poner Direcciones NAT
    #plugins/
    #`-- a_interfaces
    #    |-- a0_ethernet
    #    |   `-- data
    #    |       |-- dhcp_conf
    #    |       |-- eth0_conf
    #    |       |-- lo_conf
    #    |       `-- static_conf
    #    |-- b0_wifi_ap
    #    |   `-- data
    #    |       |-- addWEP_conf
    #    |       |-- addWPA_conf
    #    |       |-- ath0_conf
    #    |       |-- hostapd_ath0.conf_conf
    #    |       |-- lo_conf
    #    |       `-- static_conf
    #    `-- e0_join
    #        `-- data
    #            |-- join.conf
    #            `-- join_rules.conf

    echo "Copying ManagerSystem files"
    #sudo rm /var/www/ManagerSystem/core/structure/confirmation/data/*
    sudo rm /var/www/ManagerSystem/plugins/a_interfaces/a0_ethernet/data/*
    sudo rm /var/www/ManagerSystem/plugins/a_interfaces/b0_wifi_ap/data/*
    sudo rm /var/www/ManagerSystem/plugins/a_interfaces/c0_wifi_mesh/data/*
    sudo rm /var/www/ManagerSystem/plugins/a_interfaces/d0_gprs/data/*
    sudo rm /var/www/ManagerSystem/plugins/b_SensorData/b0_capturer/data/*
    sudo rm /var/www/ManagerSystem/plugins/c_tools/h0_wifi_scan/data/*
    sudo rm /var/www/ManagerSystem/plugins/c_tools/i0_bluetooth_scan/data/*
    sudo rm /var/www/ManagerSystem/plugins/c_tools/j0_gps/data/*
    sudo cp -r /var/www/ManagerSystem/presets/MeshliumAp/plugins /var/www/ManagerSystem/
    sudo cp /var/www/ManagerSystem/presets/MeshliumAp/confirmation/default /var/www/ManagerSystem/core/structure/confirmation/data/default



    if [ -f /etc/init.d/wvdiald.sh ]
    then
        sudo rm /etc/init.d/wvdiald.sh
    fi
    sudo chmod 777 /etc/init.d/wvdiald.tc.sh
    sudo chown -R www-data:www-data /var/www/*
    sudo chmod -R 777 /var/www/ManagerSystem

    sudo chmod 777 /etc/init.d/*
    sudo chmod a-x /etc/init.d/skeleton
    sudo chmod a-x /etc/init.d/README

## 4) Archivo de variables globales
    sudo echo "Meshlium AP" > /mnt/lib/cfg/currentPreset



## 5) Finalizando instalacion
    echo "remounting for read only";
    sudo remountro;

