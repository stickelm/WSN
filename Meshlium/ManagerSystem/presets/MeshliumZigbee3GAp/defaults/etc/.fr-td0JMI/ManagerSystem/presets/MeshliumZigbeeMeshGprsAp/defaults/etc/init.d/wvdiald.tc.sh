#!/bin/bash


case "$1" in
  start)
	     route | grep eth0$ | grep -i default | egrep -o [0-9]\{1,3\}\.[0-9]\{1,3\}\.[0-9]\{1,3\}\.[0-9]\{1,3\} | head -n1 > /mnt/user/.ethGateway
        wvdial &

        wvdialUP=`ps -e | grep wvdial | wc -l`; # comprobamos que wvdial está levantado
        ppp0UP=`ifconfig | grep ppp0 | wc -l`;  # comprobamos que la interfaz esta funcionando

        while [ 1 ]
        do
         echo .;
         if [ $wvdialUP -eq 1 ]
         then
            if [ $ppp0UP -eq 1 ]
            then
               break;
            fi
         fi
         sleep 10;
         wvdialUP=`ps -e | grep wvdial | wc -l`;
         ppp0UP=`ifconfig | grep ppp0 | wc -l`;
        done

        ppp0IP=`ifconfig ppp0 | grep "inet addr" | cut -d: -f2 | cut -d' ' -f1`;

        route del default
        route add default gw $ppp0IP
        ;; 
  stop)
        /usr/bin/killall wvdial
        ;;
  *)
        echo "Uso: $0 start|stop" >&2
        exit 3
        ;;
esac
exit 0