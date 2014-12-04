#!/bin/bash

#wvdial &
route | grep eth0$ | grep -i default | egrep -o [0-9]\{1,3\}\.[0-9]\{1,3\}\.[0-9]\{1,3\}\.[0-9]\{1,3\} | head -n1 > /mnt/user/.ethGateway

wvdialUP=`ps -e | grep wvdial | wc -l`; # comprobamos que wvdial está levantado
ppp0UP=`ifconfig | grep ppp0 | wc -l`;  # comprobamos que la interfaz esta funcionando

cont=0

while [ $cont -lt 7 ]
do
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
 cont=`expr $cont + 1`
done

if [ $cont -eq 7 ]
then
 killall wvdial
fi

if [ $cont -lt 7 ]
then
 ppp0IP=`ifconfig ppp0 | grep "inet addr" | cut -d: -f2 | cut -d' ' -f1`;
 echo $ppp0IP

 route del default
 route add default gw $ppp0IP

 iptables -F
 ath0=$(ifconfig ath0 2>/dev/null | egrep -o '([0-9]{1,3}\.){3}[0-9]{1,3}' | egrep -v '255|(127\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})')
 /usr/local/sbin/nat.sh ath0 ppp0 $ath0/24
fi