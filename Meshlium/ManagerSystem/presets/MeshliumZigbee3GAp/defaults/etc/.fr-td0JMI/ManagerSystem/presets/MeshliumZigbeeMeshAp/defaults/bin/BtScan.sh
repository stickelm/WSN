#!/bin/bash

extDatabase=`cat /var/www/ManagerSystem/plugins/c_tools/i0_bluetooth/data/ExtDataConnection | grep -n '' | grep 1: | cut -d: -f2`
extTable=`cat /var/www/ManagerSystem/plugins/c_tools/i0_bluetooth/data/ExtDataConnection | grep -n '' | grep 2: | cut -d: -f2`
extIP=`cat /var/www/ManagerSystem/plugins/c_tools/i0_bluetooth/data/ExtDataConnection | grep -n '' | grep 3: | cut -d: -f2`
extPort=`cat /var/www/ManagerSystem/plugins/c_tools/i0_bluetooth/data/ExtDataConnection | grep -n '' | grep 4: | cut -d: -f2`
extUser=`cat /var/www/ManagerSystem/plugins/c_tools/i0_bluetooth/data/ExtDataConnection | grep -n '' | grep 5: | cut -d: -f2`
extPassword=`cat /var/www/ManagerSystem/plugins/c_tools/i0_bluetooth/data/ExtDataConnection | grep -n '' | grep 6: | cut -d: -f2`

while [ 1 ]
do
    sleep 0.5
    if [ -f /var/www/ManagerSystem/plugins/c_tools/i0_bluetooth/data/interval ]
    then
        timeToSleep=`cat /var/www/ManagerSystem/plugins/c_tools/i0_bluetooth/data/interval`
    else
        timeToSleep=10
    fi

    medio=0;

    if [ -f /var/www/ManagerSystem/plugins/c_tools/i0_bluetooth/data/localDB ]
    then
        medio=1;
    fi

    if [ -f /var/www/ManagerSystem/plugins/c_tools/i0_bluetooth/data/localFile ]
    then
        medio=1;
    fi

    if [ -f /var/www/ManagerSystem/plugins/c_tools/i0_bluetooth/data/ExtDB ]
    then
        medio=1;
    fi

    if [ $medio -eq 1 ]
    then

        hcitool scan | grep ":" > /tmp/scan
        num=`cat /tmp/scan | grep -c ""`
        cont=1

        while [ 1 ]
        do
            while [ $cont -le $num ]
            do
                linea=`cat /tmp/scan | grep -n "" | grep "^$cont:"`
                mac=`echo $linea | cut -d' ' -f2`
                id=`echo $linea | cut -d' ' -f3`

                if [ -f /var/www/ManagerSystem/plugins/c_tools/i0_bluetooth/data/localDB ]
                then
                    echo "insert into bluetoothData values (null, now(), '$mac', '$id');" | mysql -u root --password=libelium2007 MeshliumDB

                fi

                if [ -f /var/www/ManagerSystem/plugins/c_tools/i0_bluetooth/data/localFile ]
                then
                    time=`date +%Y-%m-%d-%H:%M:%S`
                    file=`cat /var/www/ManagerSystem/plugins/c_tools/i0_bluetooth/data/localFile`
                    echo "$time - mac:$mac - ID:$id" >> /mnt/user/bluetooth_data/$file
                fi

                if [ -f /var/www/ManagerSystem/plugins/c_tools/i0_bluetooth/data/extDB ]
                then
                    echo "insert into $extTable values (null, now(), '$mac', '$id');" | mysql -h $extIP -P $extPort -u $extUser --password=$extPassword $extDatabase
                fi


                cont=`expr $cont + 1`
            done

            sleep $timeToSleep
            hcitool scan | grep ":" > /tmp/scan
            num=`cat /tmp/scan | grep -c ""`
            cont=1
        done
    fi
done
